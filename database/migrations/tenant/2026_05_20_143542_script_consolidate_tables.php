<?php

use App\Enums\CodeTypeEnum;
use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run all consolidated migrations.
     */
    public function up(): void
    {
        // 2014_10_12_000000_create_users_table
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone',10)->nullable();
            $table->string('avatar',500)->nullable();
            $table->string('members_number')->nullable();
            $table->json('extra_info')->nullable();
            $table->string('password')->nullable();
            $table->integer('points')->default(0);
            $table->integer('ranking')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2014_10_12_100000_create_password_resets_table
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 2019_08_19_000000_create_failed_jobs_table
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // 2019_12_14_000001_create_personal_access_tokens_table
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 2023_10_28_145656_create_permission_tables
        $teams = config('permission.teams');
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }
        if ($teams && empty($columnNames['team_foreign_key'] ?? null)) {
            throw new \Exception('Error: team_foreign_key on config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames) {
            $table->bigIncrements('id');
            if ($teams || config('permission.testing')) {
                $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
            }
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            if ($teams || config('permission.testing')) {
                $table->unique([$columnNames['team_foreign_key'], 'name', 'guard_name']);
            } else {
                $table->unique(['name', 'guard_name']);
            }
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotPermission, $teams) {
            $table->unsignedBigInteger($pivotPermission);
            $table->string('model_type');
            $table->uuid($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');
            $table->foreign($pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');
                $table->primary([$columnNames['team_foreign_key'], $pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            } else {
                $table->primary([$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
            }
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $pivotRole, $teams) {
            $table->unsignedBigInteger($pivotRole);
            $table->string('model_type');
            $table->uuid($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');
            $table->foreign($pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            if ($teams) {
                $table->unsignedBigInteger($columnNames['team_foreign_key']);
                $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');
                $table->primary([$columnNames['team_foreign_key'], $pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            } else {
                $table->primary([$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
            }
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $pivotRole, $pivotPermission) {
            $table->unsignedBigInteger($pivotPermission);
            $table->unsignedBigInteger($pivotRole);
            $table->foreign($pivotPermission)
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            $table->foreign($pivotRole)
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            $table->primary([$pivotPermission, $pivotRole], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));

        // 2023_10_28_153243_create_quizzes_table
        Schema::create('quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('description');
            $table->integer('seconds')->nullable();
            $table->boolean('enable_chronometer')->default(false);
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('failed_response');
            $table->string('failed_image');
            $table->string('slug');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->timestamps();
        });

        // 2023_10_28_153417_create_questions_table 
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('featured_image',500)->nullable();
            $table->foreignUuid('quiz_id')->index();
            $table->timestamps();
        });

        // 2023_10_30_115925_create_answers_table
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('featured_image',500)->nullable();
            $table->boolean('is_correct')->default(false);
            $table->foreignUuid('question_id')->index();
            $table->timestamps();
        });

        // 2023_10_30_223426_create_pages_table
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->longText('content');
            $table->string('icon',500)->nullable();
            $table->string('slug');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        // 2023_11_01_164230_create_award_user_table
        Schema::create('award_user', function (Blueprint $table) {
            $table->foreignUuid('model_id');
            $table->foreignUuid('user_id');
            $table->string('model_type');
            $table->uuid('award_id')->nullable();
            $table->boolean('hit');
            $table->index(['model_id', 'model_type', 'user_id', 'hit'], 'idx_award_user_full');
            $table->timestamps();
        });

        // 2023_11_03_212510_create_awards_table
        Schema::create('awards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('awardable_id')->index();
            $table->string('awardable_type');
            $table->text('title');
            $table->longText('content');
            $table->timestamps();
        });

        // 2023_11_03_213119_create_award_codes_table
        Schema::create('award_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('image_url',500)->nullable();
            $table->string('product')->nullable();
            $table->string('validity')->nullable();
            $table->boolean('active')->default(false);
            $table->foreignUuid('award_id');
            $table->foreignUuid('user_id')->nullable();
            $table->timestamps();
        });

        // 2023_12_02_135111_create_memory_quizzes_table
        Schema::create('memory_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('seconds');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('back_card_image',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->timestamps();
        });

        // 2023_12_02_135314_create_memory_cards_table
        Schema::create('memory_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('featured_image',500)->nullable();
            $table->string('name');
            $table->foreignUuid('memory_quiz_id')->index();
            $table->timestamps();
        });

        // 2023_12_02_163311_create_settings_table
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->nullable();
            $table->string('app_description')->nullable();
            $table->string('app_logo')->nullable();
            $table->string('app_background_color')->nullable();
            $table->string('app_background')->nullable();
            $table->string('app_animated_background')->nullable();
            $table->string('primary_button_color')->nullable();
            $table->string('primary_button_background')->nullable();
            $table->text('home_content')->nullable();
            $table->text('terms_text')->nullable();
            $table->text('privacy_text')->nullable();
            $table->string('disabled_gradient_1');
            $table->string('disabled_gradient_2');
            $table->string('cards_background_color')->nullable();
            $table->string('cards_font_color');
            $table->string('out_of_coupons_title');
            $table->string('out_of_coupons_image',500);
            $table->boolean('tickets_quiz_validation')->default(false);
            $table->string('tickets_success_response',500);
            $table->string('tickets_failed_response',500);
            $table->integer('tickets_points')->default(1);
            $table->string('ranking_color_1')->nullable();
            $table->string('ranking_color_2')->nullable();
            $table->string('header_background_color')->nullable();
            $table->string('ga4_id')->nullable();
            $table->boolean('social_login_active')->default(false);
            $table->boolean('members_number')->default(true);
            $table->string('members_legend');
            $table->string('members_placeholder');
            $table->string('members_url');
            $table->string('first_place_icon',500)->nullable();
            $table->string('second_place_icon',500)->nullable();
            $table->string('third_place_icon',500)->nullable();
            $table->string('favicon',500)->nullable();
            $table->string('reg_form_name_label',256)->nullable();
            $table->string('reg_form_email_label',256)->nullable();
            $table->string('reg_form_email_conf_label',256)->nullable();
            $table->string('tickets_form_legend',256)->nullable();
            $table->string('tickets_duplicated_image',500)->nullable();
            $table->boolean('app_active')->default(true);
            $table->boolean('ranking_enabled')->default(true);
            $table->string('coupons_field_placeholder')->nullable();
            $table->boolean('cards_shadow')->default(false);
            $table->string('ranking_banner',500)->nullable();
            $table->string('code_hunter_duplicated',500);
            $table->string('code_hunter_incorrect',500);
            $table->string('coupons_icon',500);
            $table->string('coupons_icon_active',500);
            $table->string('coupons_form_legend',500);
            $table->json('ocr_ticket_phrases')->nullable();
            $table->boolean('ocr_ticket_active')->default(false);
            $table->string('ocr_date_string')->nullable();
            $table->integer('ocr_date_characters')->nullable();
            $table->string('ocr_date_format')->default('d/m/Y');
            $table->string('ocr_time_string')->nullable();
            $table->integer('ocr_time_characters')->nullable();
            $table->string('ocr_transaction_string')->nullable();
            $table->integer('ocr_transaction_characters')->nullable();
            $table->string('aplazo_api_token')->nullable();
            $table->string('aplazo_merchant_id')->nullable();
            $table->string('aplazo_endpoint')->nullable();
            $table->text('custom_css')->nullable();
            $table->text('award_show_title')->nullable();
            $table->string('awards_section_title')->nullable();
            $table->boolean('ranking_enabled_games')->default(true);
            $table->boolean('ranking_enabled_tickets')->default(true);
            $table->string('first_place_icon_games',250)->nullable();
            $table->string('second_place_icon_games',250)->nullable();
            $table->string('third_place_icon_games',250)->nullable();
            $table->boolean('allow_city')->default(false);
            $table->timestamps();
        });

        // 2023_12_08_101930_create_media_elements_table
        Schema::create('media_elements', function (Blueprint $table) {
            $table->id();
            $table->string('asset');
            $table->string('mime_type');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 2024_07_24_162932_create_cache_table
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // 2024_07_25_200640_create_campaigns_table
        Schema::create('campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description');
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->boolean('active')->default(false);
            $table->string('slug');
            $table->timestamps();
        });

        // 2024_07_25_201737_create_campaign_splash_pages_table
        Schema::create('campaign_splash_pages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('campaign_id')->index();
            $table->string('featured_video_url')->nullable();
            $table->string('featured_image_url')->nullable();
            $table->text('instructions');
            $table->timestamps();
        });

        // 2024_07_27_232254_create_content_types_table
        Schema::create('content_types', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('name');
            $table->string('system_name');
            $table->string('description');
            $table->string('icon',500)->nullable();
            $table->string('icon_active',500)->nullable();
            $table->string('gradient_1')->nullable();
            $table->string('gradient_2')->nullable();
            $table->string('section_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->timestamps();
        });

        // 2024_07_28_004843_create_campaign_content_type_table
        Schema::create('campaign_content_type', function (Blueprint $table) {
            $table->foreignUuid('campaign_id');
            $table->foreignUuid('content_type_id');
        });

        // 2024_07_31_183157_create_share_quizzes_table
        Schema::create('share_quizzes', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('title');
            $table->string('description');
            $table->string('slug');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('featured_video_url')->nullable();
            $table->string('featured_image_url',500)->nullable();
            $table->string('share_url',500);
            $table->string('share_text',255);
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id');
            $table->foreignUuid('content_type_id');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->timestamps();
        });

        // 2024_08_01_154609_create_tickets_table
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('transaction_number');
            $table->date('transaction_date');
            $table->string('store');
            $table->float('transaction_amount',8,2);
            $table->string('img_url',500);
            $table->integer('points');
            $table->foreignUuid('campaign_id');
            $table->foreignUuid('user_id');
            $table->boolean('guessed')->default(false);
            $table->timestamps();
        });

        // 2024_08_06_000000_create_fileponds_table
        Schema::create('fileponds', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('filepath');
            $table->string('extension', 100);
            $table->string('mimetypes', 100);
            $table->string('disk', 100);
            $table->uuid('created_by')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 2024_08_06_175321_create_ticket_answers_table
        Schema::create('ticket_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->boolean('is_correct')->default(false);
            $table->foreignUuid('ticket_question_id')->index();
            $table->timestamps();
        });

        // 2024_08_06_175455_create_ticket_questions_table
        Schema::create('ticket_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',150);
            $table->timestamps();
        });

        // 2024_11_10_161431_create_codes_table
        Schema::create('codes', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->enum('type',CodeTypeEnum::values());
            $table->string('title');
            $table->integer('points');
            $table->string('featured_image',500)->nullable();
            $table->string('gradient_1')->nullable();
            $table->string('gradient_2')->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color')->nullable();
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->boolean('active');
            $table->timestamps();
        });

        // 2025_01_07_210203_create_vote_contests_table
        Schema::create('vote_contests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug')->unique();
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('asset_type');
            $table->string('asset_kb_size');
            $table->integer('points_per_vote');
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->boolean('show_ranking')->default(false);
            $table->timestamps();
        });

        // 2025_01_09_202509_create_vote_contest_assets_table
        Schema::create('vote_contest_assets', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('title', 600);
            $table->string('asset_url',500);
            $table->integer('points')->default(0);
            $table->foreignUuid('user_id');
            $table->foreignUuid('vote_contest_id');
            $table->timestamps();
        });

        // 2025_01_11_143225_create_vote_contest_votations_table
        Schema::create('vote_contest_votations', function (Blueprint $table) {
            $table->uuid('id')->primary()->index();
            $table->string('email');
            $table->integer('points');
            $table->foreignUuid('vote_contest_asset_id');
            $table->timestamps();
        });

        // 2025_03_01_174051_create_click_wins_table
        Schema::create('click_wins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug')->unique();
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->timestamps();
        });

        // 2025_04_03_074002_create_ocr_tickets_table
        Schema::create('ocr_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('ocr_string');
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('img_url',500);
            $table->foreignUuid('campaign_id');
            $table->foreignUuid('user_id')->index();
            $table->timestamps();
        });

        // 2025_04_16_231407_create_aplazo_loans_table
        Schema::create('aplazo_loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('url',250);
            $table->string('loan_id');
            $table->string('cart_id');
            $table->string('token');
            $table->string('status');
            $table->foreignUuid('aplazo_game_id')->index();
            $table->foreignUuid('user_id')->index();
            $table->timestamps();
        });

        // 2025_04_16_232747_create_aplazo_games_table
        Schema::create('aplazo_games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title',100);
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug')->unique();
            $table->float('price',8,2);
            $table->string('product_name');
            $table->string('product_description',250);
            $table->string('promo_image',500);
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->string('game_banner',500)->nullable();
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id')->index();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->timestamps();
        });

        // 2025_07_19_131546_create_puzzles_table
        Schema::create('puzzles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('seconds');
            $table->integer('pieces');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('puzzle_image',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->timestamps();
        });

        // 2025_08_02_113325_create_user_interactions_table
        Schema::create('user_interactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('model_id')->index();
            $table->string('model_title');
            $table->foreignUuid('user_id')->index();
            $table->boolean('hit')->default(false);
            $table->dateTime('hit_created_at',6)->nullable();
            $table->dateTime('hit_updated_at',6)->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });

        // 2025_09_03_220635_create_catch_objects_table
        Schema::create('catch_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('object_image',500);
            $table->foreignUuid('catch_game_id')->index();
            $table->timestamps();
        });

        // 2025_09_03_221331_create_catch_games_table
        Schema::create('catch_games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('seconds');
            $table->integer('max_points');
            $table->integer('points_per_object');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('game_bg_image',500);
            $table->string('basket_image',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->timestamps();
        });

        // 2025_11_18_165656_create_smash_games_table
        Schema::create('smash_games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('seconds');
            $table->integer('max_points');
            $table->integer('points_per_object');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('game_bg_image',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->timestamps();
        });

        // 2025_11_18_165709_create_smash_objects_table
        Schema::create('smash_objects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('object_image',500);
            $table->foreignUuid('smash_game_id')->index();
            $table->timestamps();
        });

        // 2026_03_05_152225_create_flappy_game_table
        Schema::create('flappy_games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('max_points');
            $table->integer('points_per_pipe');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('game_bg_image',500);
            $table->string('game_pipe_image',500);
            $table->string('flappy_image',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->timestamps();
        });

        // 2026_03_29_154024_create_penal_games_table
        Schema::create('penal_games', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('gradient_1');
            $table->string('gradient_2');
            $table->string('slug');
            $table->integer('max_points');
            $table->integer('points_per_goal');
            $table->string('featured_image',500);
            $table->string('featured_image_disabled',500);
            $table->string('game_bg_image_desktop',500);
            $table->string('game_bg_image_movil',500);
            $table->string('failed_image',500);
            $table->dateTime('init_date');
            $table->dateTime('end_date');
            $table->string('game_banner',500)->nullable();
            $table->string('game_banner_url',500)->nullable();
            $table->string('game_banner_video',500)->nullable();
            $table->string('btn_background_color_1')->nullable();
            $table->string('btn_background_color_2')->nullable();
            $table->string('btn_border_color')->nullable();
            $table->boolean('btn_border')->default(false);
            $table->string('btn_text_active')->default('Jugar ahora');
            $table->string('btn_text_inactive')->default('Ver resultado');
            $table->boolean('btn_shadow')->default(false);
            $table->string('btn_text_color');
            $table->foreignUuid('campaign_id')->index();
            $table->foreignUuid('content_type_id');
            $table->timestamps();
        });

        // 2025_12_15_170701_create_options_table
        Schema::create('options', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('option_name')->unique();
            $table->longText('option_value')->nullable();
            $table->boolean('autoload')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse all consolidated migrations.
     */
    public function down(): void
    {
        // Drop all tables in reverse order of creation
        Schema::dropIfExists('options');
        Schema::dropIfExists('penal_games');
        Schema::dropIfExists('flappy_games');
        Schema::dropIfExists('smash_objects');
        Schema::dropIfExists('smash_games');
        Schema::dropIfExists('catch_games');
        Schema::dropIfExists('catch_objects');
        Schema::dropIfExists('user_interactions');
        Schema::dropIfExists('puzzles');
        Schema::dropIfExists('aplazo_games');
        Schema::dropIfExists('aplazo_loans');
        Schema::dropIfExists('ocr_tickets');
        Schema::dropIfExists('click_wins');
        Schema::dropIfExists('vote_contest_votations');
        Schema::dropIfExists('vote_contest_assets');
        Schema::dropIfExists('vote_contests');
        Schema::dropIfExists('codes');
        Schema::dropIfExists('ticket_questions');
        Schema::dropIfExists('ticket_answers');
        Schema::dropIfExists('fileponds');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('share_quizzes');
        Schema::dropIfExists('campaign_content_type');
        Schema::dropIfExists('content_types');
        Schema::dropIfExists('campaign_splash_pages');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('media_elements');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('memory_cards');
        Schema::dropIfExists('memory_quizzes');
        Schema::dropIfExists('award_codes');
        Schema::dropIfExists('awards');
        Schema::dropIfExists('award_user');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');

        $tableNames = config('permission.table_names');
        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);

        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
};
