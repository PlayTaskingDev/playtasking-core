<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        DB::table('settings')->truncate();

        DB::table('settings')->insert(
            [
                [
                    'app_logo'                      => '/storage/dummy_assets/logo.png',
                    'app_background_color'          => '#f06c0e',
                    'app_background'                => '/storage/dummy_assets/background.png',
                    'app_animated_background'       => '/storage/dummy_assets/background_animated.png',
                    'app_name'                      => 'Your Brand',
                    'app_description'               => 'Participate in our quizzes to win fantastic rewards.',
                    'primary_button_color'          => '#ffffff',
                    'primary_button_background'     => '#000000',
                    'home_content'                  => '
                    <img src="/storage/assets/images/logo-trivias.png" loading="lazy" alt="Logo Cinepolis con Takis" class="w-full">
                    <div class="text-center">
                    <img src="/storage/assets/images/logos.png" loading="lazy" alt="Logo Cinepolis con Takis" class="mx-auto">
                    <img src="/storage/assets/images/bienvenida.png" loading="lazy" alt="Logo Cinepolis con Takis" class="w-full">
                    <a href="/cinepolis/dinamica-de-la-promocion" class="underline text-3xl text-white text-center py-6">
                        Conoce la dinámica
                    </a>
                    </div>
                    ',
                    'terms_text'                    => '<a href="/cinepolis/terminos-y-condiciones" class="underline">Estoy de acuerdo con los términos y condiciones.</a>',
                    'privacy_text'                  => '<a href="/cinepolis/aviso-de-privacidad" class="underline">He leído la politica de privacidad.</a>',
                    'header_background_color'       => '#000000',
                    'disabled_gradient_1'           => '#27272e',
                    'disabled_gradient_2'           => '#4a4a4a',
                    'ranking_icon'                  => '/storage/dummy_assets/podium-white.png',
                    'ranking_icon_active'           => '/storage/dummy_assets/podium-active.png',
                    'members_legend'                => 'Si no eres miembro Club Cinépolis, regístrate ahora.',
                    'members_placeholder'           => 'Ingresa tu número de Cineclub',
                    'members_url'                   => 'https://cinepolis.com/club-cinepolis-id',
                    'out_of_coupons_title'          => 'Lo sentimos :(',
                    'out_of_coupons_image'          => '/storage/dummy_assets/800x1180.png',
                    'tickets_quiz_validation'       => true,
                    'tickets_success_response'      => '/storage/dummy_assets/800x1180.png',
                    'tickets_failed_response'       => '/storage/dummy_assets/800x1180.png',
                    'ranking_color_1'               => '#020024',
                    'ranking_color_2'               => '#080878',
                    'cards_font_color'              => '#ffffff',
                    'favicon'                       => '/storage/dummy_assets/favicon.png',
                    'reg_form_name_label'           => 'Fullname',
                    'reg_form_email_label'          => 'Email',
                    'reg_form_email_conf_label'     => 'Email confirmation',
                    'tickets_form_legend'           => 'Please enter your ticket details correctly and answer the question.',
                    'tickets_duplicated_image'      => '/storage/dummy_assets/800x1180.png',
                    'coupons_form_legend'           => 'Capture your coupon code in the field below.',
                    'coupons_field_placeholder'     => 'Type here the code you discovered.',
                    'cards_shadow'                  => true,
                    'ranking_banner'                => '/storage/dummy_assets/ranking_banner.png',
                    'code_hunter_duplicated'        => '/storage/dummy_assets/800x1180.png',
                    'code_hunter_incorrect'         => '/storage/dummy_assets/800x1180.png',
                    'ocr_ticket_phrases'            => json_encode(['petalo']),
                    'ocr_date_string'               => 'Fecha:',
                    'ocr_date_characters'           => 13,
                    'ocr_time_string'               => '/2025',
                    'ocr_time_characters'           => 9,
                    'ocr_transaction_string'        => 'Folio:',
                    'ocr_transaction_characters'    => 21,
                    'award_show_title'              => 'Ya ganaste',
                    'awards_section_title'          => 'Mis compras recientes',
                ]
            ]
        );
    }
}
