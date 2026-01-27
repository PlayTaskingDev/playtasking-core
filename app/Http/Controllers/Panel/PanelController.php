<?php

namespace App\Http\Controllers\Panel;

use App\Exports\UserInteractionsExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\Panel\SaveSettingsRequest;
use App\Models\MemoryQuiz;
use App\Models\Quiz;
use App\Traits\UploadImageTrait;
use App\Models\AwardCode;
use App\Models\Award;
use App\Models\Setting;
use App\Models\Option;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class PanelController extends Controller
{
    use UploadImageTrait;

    public function index()
    {
        
        $options  = Option::all();
        $settings = [];
        foreach ($options as $k => $o){
            $settings[$o->option_name] = $o->option_value;
        }
        //dd(json_decode(json_encode($settings)));
        // $settings = Setting::first();
        // dd($settings);

        $quizzes = Quiz::select(['id','title'])->get()->append('model_name');
        $memory_quizzes = MemoryQuiz::select(['id','title'])->get()->append('model_name');

        $games = $memory_quizzes->concat($quizzes);

        return view('panel.index', [
            'title'         => get_app_setting('app_name'),
            'description'   => get_app_setting('app_description'),
            'settings'      => json_decode(json_encode($settings)),
            'games'         => $games
        ]);
    }

    public function save_settings(Request $request)
    {
        $rules = [
            'app_name'                      => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'app_description'               => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'app_logo'                      => ['image:jpg,png,jpeg,svg','max:2024'],
            'favicon'                       => ['image:jpg,png,jpeg,svg','max:2024'],
            'app_background_color'          => ['required','regex:/^[A-Za-z0-9#]+$/'],
            'header_background_color'       => ['required','regex:/^[A-Za-z0-9#]+$/'],
            'app_background'                => ['image:jpg,png,jpeg,svg','max:2024'],
            'app_animated_background'       => ['image:jpg,png,jpeg,svg','max:2024'],
            'home_content'                  => ['required'],
            'terms_text'                    => ['required','string'],
            'privacy_text'                  => ['required','string'],
            'ga4_id'                        => ['nullable','string'],
            'social_login_active'           => ['boolean'],
            'app_active'                    => ['boolean'],
            'ranking_enabled'               => ['boolean'],
            'ranking_enabled_games'         => ['boolean'],
            'ranking_enabled_tickets'       => ['boolean'],
            'reg_form_name_label'           => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'reg_form_email_label'          => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'reg_form_email_conf_label'     => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'primary_button_color'          => ['required','regex:/^[A-Za-z0-9#]+$/'],
            'primary_button_background'     => ['required','regex:/^[A-Za-z0-9#]+$/'],
            'disabled_gradient_1'           => ['required','regex:/^[A-Za-z0-9#]+$/'],
            'disabled_gradient_2'           => ['required','regex:/^[A-Za-z0-9#]+$/'],
            'out_of_coupons_title'          => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'out_of_coupons_image'          => ['image:jpg,png,jpeg,svg','max:2024'],
            'members_number'                => ['boolean'],
            'allow_city'                    => ['boolean'],
            'members_legend'                => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'members_placeholder'           => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'members_url'                   => ['required','url'],
            'tickets_quiz_validation'       => ['boolean'],
            'tickets_success_response'      => ['image:jpg,png,jpeg,svg','max:2024'],
            'tickets_failed_response'       => ['image:jpg,png,jpeg,svg','max:2024'],
            'tickets_duplicated_image'      => ['image:jpg,png,jpeg,svg','max:2024'],
            'tickets_form_legend'           => ['regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'tickets_points'                => ['nullable','numeric'],
            'ranking_color_1'               => ['regex:/^[A-Za-z0-9#]+$/'],
            'ranking_color_2'               => ['regex:/^[A-Za-z0-9#]+$/'],
            'first_place_icon'              => ['image:jpg,png,jpeg,svg','max:2024'],
            'second_place_icon'             => ['image:jpg,png,jpeg,svg','max:2024'],
            'third_place_icon'              => ['image:jpg,png,jpeg,svg','max:2024'],
            'first_place_icon_games'        => ['image:jpg,png,jpeg,svg','max:2024'],
            'second_place_icon_games'       => ['image:jpg,png,jpeg,svg','max:2024'],
            'third_place_icon_games'        => ['image:jpg,png,jpeg,svg','max:2024'],
            'cards_background_color'        => ['regex:/^[A-Za-z0-9#]+$/'],
            'cards_font_color'              => ['regex:/^[A-Za-z0-9#]+$/'],
            'coupons_form_legend'           => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'ranking_icon'                  => ['image:jpg,png,jpeg,svg','max:2024'],
            'ranking_icon_active'           => ['image:jpg,png,jpeg,svg','max:2024'],
            'cards_shadow'                  => ['boolean'],
            'coupons_field_placeholder'     => ['required','regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñⓇ$,.;:!"¡?¿#\(\)\' \-]+$/'],
            'ranking_banner'                => ['image:jpg,png,jpeg,svg','max:2024'],
            'delete_image_holder_hidden'    => ['nullable','boolean'],
            'code_hunter_incorrect'         => ['image:jpg,png,jpeg,svg','max:2024'],
            'code_hunter_duplicated'        => ['image:jpg,png,jpeg,svg','max:2024'],
            'ocr_ticket_active'             => ['boolean'],
            'ocr_ticket_phrases'            => ['nullable','required_if:ocr_ticket_active,1','string'],
            'ocr_date_string'               => ['nullable','string'],
            'ocr_date_characters'           => ['numeric'],
            'ocr_date_format'               => ['string'],
            'ocr_time_string'               => ['nullable','string'],
            'ocr_time_characters'           => ['numeric'],
            'ocr_transaction_string'        => ['nullable','string'],
            'ocr_transaction_characters'    => ['numeric'],
            'aplazo_api_token'              => ['nullable','string'],
            'aplazo_merchant_id'            => ['nullable','string'],
            'aplazo_endpoint'               => ['nullable','string'],
            'custom_css'                    => ['nullable', 'regex:/^[\s\S]*{[\s\S]*}[\s\S]*$/i'],
            'award_show_title'              => ['nullable','string'],
            'awards_section_title'          => ['nullable','string'],
        ];


        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if (isset(($data['ocr_ticket_phrases']))) {
            $lines = preg_split('/\r\n|\r|\n/', trim($data['ocr_ticket_phrases']));
            // Remove empty lines or trim if needed
            $lines = array_filter(array_map('trim', $lines));
            $data['ocr_ticket_phrases'] = $lines;
        }

        if($request->file('ranking_banner')){
            $data['ranking_banner'] = $this->uploadImage('gcs','settings',$request->file('ranking_banner'));
        }

        if (isset(($data['delete_image_holder_hidden']))) {
            $data['ranking_banner'] = null;
        }

        if($request->file('code_hunter_incorrect')){
            $data['code_hunter_incorrect'] = $this->uploadImage('gcs','settings',$request->file('code_hunter_incorrect'));
        }

        if($request->file('code_hunter_duplicated')){
            $data['code_hunter_duplicated'] = $this->uploadImage('gcs','settings',$request->file('code_hunter_duplicated'));
        }

        if($request->file('ranking_icon')){
            $data['ranking_icon'] = $this->uploadImage('gcs','settings',$request->file('ranking_icon'));
        }

        if($request->file('ranking_icon_active')){
            $data['ranking_icon_active'] = $this->uploadImage('gcs','settings',$request->file('ranking_icon_active'));
        }

        if($request->file('tickets_duplicated_image')){
            $data['tickets_duplicated_image'] = $this->uploadImage('gcs','settings',$request->file('tickets_duplicated_image'));
        }

        if($request->file('app_logo')){
            $data['app_logo'] = $this->uploadImage('gcs','settings',$request->file('app_logo'));
        }

        if($request->file('favicon')){
            $data['favicon'] = $this->uploadImage('gcs','settings',$request->file('favicon'));
        }

        if($request->file('app_background')){
            $data['app_background'] = $this->uploadImage('gcs','settings',$request->file('app_background'));
        }

        if($request->file('app_animated_background')){
            $data['app_animated_background'] = $this->uploadImage('gcs','settings',$request->file('app_animated_background'));
        }

        if($request->file('out_of_coupons_image')){
            $data['out_of_coupons_image'] = $this->uploadImage('gcs','settings',$request->file('out_of_coupons_image'));
        }

        if($request->file('tickets_success_response')){
            $data['tickets_success_response'] = $this->uploadImage('gcs','settings',$request->file('tickets_success_response'));
        }

        if($request->file('tickets_failed_response')){
            $data['tickets_failed_response'] = $this->uploadImage('gcs','settings',$request->file('tickets_failed_response'));
        }

        if($request->file('first_place_icon')){
            $data['first_place_icon'] = $this->uploadImage('gcs','settings',$request->file('first_place_icon'));
        }

        if($request->file('second_place_icon')){
            $data['second_place_icon'] = $this->uploadImage('gcs','settings',$request->file('second_place_icon'));
        }

        if($request->file('third_place_icon')){
            $data['third_place_icon'] = $this->uploadImage('gcs','settings',$request->file('third_place_icon'));
        }
        
        if($request->file('first_place_icon_games')){
            $data['first_place_icon_games'] = $this->uploadImage('gcs','settings',$request->file('first_place_icon_games'));
        }

        if($request->file('second_place_icon_games')){
            $data['second_place_icon_games'] = $this->uploadImage('gcs','settings',$request->file('second_place_icon_games'));
        }

        if($request->file('third_place_icon_games')){
            $data['third_place_icon_games'] = $this->uploadImage('gcs','settings',$request->file('third_place_icon_games'));
        }

        if( !$request->has('tickets_quiz_validation') ){
            $data['tickets_quiz_validation'] = false;
        }

        if( !$request->has('social_login_active') ){
            $data['social_login_active'] = false;
        }

        if( !$request->has('app_active') ){
            $data['app_active'] = false;
        }

        if( !$request->has('ranking_enabled') ){
            $data['ranking_enabled'] = false;
        }
        if( !$request->has('ranking_enabled_games') ){
            $data['ranking_enabled_games'] = false;
        }
        if( !$request->has('ranking_enabled_tickets') ){
            $data['ranking_enabled_tickets'] = false;
        }

        if( !$request->has('members_number') ){
            $data['members_number'] = false;
        }
        if( !$request->has('allow_city') ){
            $data['allow_city'] = false;
        }

        if( !$request->has('cards_shadow') ){
            $data['cards_shadow'] = false;
        }

        if ($data['cards_background_color'] == 'TRANSPARENT') {
            $data['cards_background_color'] = NULL;
        }

        foreach($data as $k => $v){
            Option::updateOrCreate(
                ['option_name' => $k],
                ['option_value' => $v]
            );
        }
        // $settings = Setting::first();

        // $settings->fill($data);
        //$settings->save();

        return redirect(route('panel.index', ['tenant' => tenant('id')]))->with('status', trans('Settings saved successful'));
    }

    public function statistics()
    {
        $users = User::count();
        $top_ten_users = User::select(['name','ranking','points','email'])
            ->where('points','>',0)
            ->whereNotNull('ranking')
            ->orderBy('ranking','asc')
            ->limit(10)->get();
        $coupons = AwardCode::count();
        $coupons_delivered = AwardCode::where('active',true)->count();
        $coupons_dynamic = Award::withCount('codes_delivered')->with('awardable')->get();

        return view('panel.statistics', [
            'title'             => get_app_setting('app_name'),
            'description'       => get_app_setting('app_description'),
            'users'             => $users,
            'top_ten_users'     => $top_ten_users,
            'coupons'           => $coupons,
            'coupons_delivered' => $coupons_delivered,
            'coupons_dynamic'   => $coupons_dynamic,
            'coupons_remaining' => $coupons - $coupons_delivered
        ]);
    }

    public function export_user_interactions($model_id)
    {
        $rows_collection = DB::table("user_interactions as ui")->where('ui.model_id', $model_id)
            ->join('users as u', 'u.id', '=', 'ui.user_id')
            ->selectRaw('
                ui.model_id as game_id,
                ui.model_title as game_title,
                u.name as user_name,
                u.email,
                u.created_at as user_created_at,
                ui.hit as pivot_hit,
                ui.hit_created_at as hit_created_at,
                ui.hit_updated_at as hit_updated_at,
                ui.code as award_code
            ')
            ->get();

        return Excel::download(
            new UserInteractionsExport($rows_collection),
            'user_interactions.xlsx'
        );
    }

}
