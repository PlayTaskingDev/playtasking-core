<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert(
            [
                [
                    'option_name'    => 'app_name',
                    'option_value'   => 'Your Brand',
                ],
                [
                    'option_name'    => 'app_description',
                    'option_value'   => 'Participate in our quizzes to win fantastic rewards.',
                ],
                [
                    'option_name'    => 'app_logo',
                    'option_value'   => '/storage/dummy_assets/logo.png',
                ],
                [
                    'option_name'    => 'app_background_color',
                    'option_value'   => '#f06c0e',
                ],
                [
                    'option_name'    => 'app_background',
                    'option_value'   => '/storage/dummy_assets/background.png',
                ],
                [
                    'option_name'    => 'app_animated_background',
                    'option_value'   => '/storage/dummy_assets/background_animated.png',
                ],
                [
                    'option_name'    => 'primary_button_color',
                    'option_value'   => '#ffffff',
                ],
                [
                    'option_name'    => 'primary_button_background',
                    'option_value'   => '#000000',
                ],
                [
                    'option_name'    => 'home_content',
                    'option_value'   => '<img src="/storage/assets/images/logo-trivias.png" loading="lazy" alt="Logo Cinepolis con Takis" class="w-full">
                    <div class="text-center">
                    <img src="/storage/assets/images/logos.png" loading="lazy" alt="Logo Cinepolis con Takis" class="mx-auto">
                    <img src="/storage/assets/images/bienvenida.png" loading="lazy" alt="Logo Cinepolis con Takis" class="w-full">
                    <a href="/cinepolis/dinamica-de-la-promocion" class="underline text-3xl text-white text-center py-6">
                        Conoce la dinámica
                    </a>
                    </div>',
                ],
                [
                    'option_name'    => 'terms_text',
                    'option_value'   => '<a href="/cinepolis/terminos-y-condiciones" class="underline">Estoy de acuerdo con los términos y condiciones.</a>',
                ],
                [
                    'option_name'    => 'privacy_text',
                    'option_value'   => '<a href="/cinepolis/aviso-de-privacidad" class="underline">He leído la politica de privacidad.</a>',
                ],
                [
                    'option_name'    => 'header_background_color',
                    'option_value'   => '#000000',
                ],
                [
                    'option_name'    => 'disabled_gradient_1',
                    'option_value'   => '#27272e',
                ],
                [
                    'option_name'    => 'disabled_gradient_2',
                    'option_value'   => '#4a4a4a',
                ],
                [
                    'option_name'    => 'ranking_icon',
                    'option_value'   => '/storage/dummy_assets/podium-white.png',
                ],
                [
                    'option_name'    => 'ranking_icon_active',
                    'option_value'   => '/storage/dummy_assets/podium-active.png',
                ],
                [
                    'option_name'    => 'members_legend',
                    'option_value'   => 'Si no eres miembro Club Cinépolis, regístrate ahora.',
                ],
                [
                    'option_name'    => 'members_placeholder',
                    'option_value'   => 'Ingresa tu número de Cineclub',
                ],
                [
                    'option_name'    => 'members_url',
                    'option_value'   => 'https://cinepolis.com/club-cinepolis-id',
                ],
                [
                    'option_name'    => 'out_of_coupons_title',
                    'option_value'   => 'Lo sentimos :(',
                ],
                [
                    'option_name'    => 'out_of_coupons_image',
                    'option_value'   => '/storage/dummy_assets/800x1180.png',
                ],
                [
                    'option_name'    => 'tickets_quiz_validation',
                    'option_value'   => true,
                ],
                [
                    'option_name'    => 'tickets_success_response',
                    'option_value'   => '/storage/dummy_assets/800x1180.png',
                ],
                [
                    'option_name'    => 'tickets_failed_response',
                    'option_value'   => '/storage/dummy_assets/800x1180.png',
                ],
                [
                    'option_name'    => 'tickets_points',
                    'option_value'   => '10',
                ],
                [
                    'option_name'    => 'ranking_color_1',
                    'option_value'   => '#020024',
                ],
                [
                    'option_name'    => 'ranking_color_2',
                    'option_value'   => '#080878',
                ],
                [
                    'option_name'    => 'cards_font_color',
                    'option_value'   => '#ffffff',
                ],
                [
                    'option_name'    => 'favicon',
                    'option_value'   => '/storage/dummy_assets/favicon.png',
                ],
                [
                    'option_name'    => 'reg_form_name_label',
                    'option_value'   => 'Nombre Completo',
                ],
                [
                    'option_name'    => 'reg_form_email_label',
                    'option_value'   => 'Email',
                ],
                [
                    'option_name'    => 'reg_form_email_conf_label',
                    'option_value'   => 'Confirmar Email',
                ],
                [
                    'option_name'    => 'tickets_form_legend',
                    'option_value'   => 'Please enter your ticket details correctly and answer the question.',
                ],
                [
                    'option_name'    => 'tickets_duplicated_image',
                    'option_value'   => '/storage/dummy_assets/800x1180.png',
                ],
                [
                    'option_name'    => 'coupons_form_legend',
                    'option_value'   => 'Capture your coupon code in the field below.',
                ],
                [
                    'option_name'    => 'coupons_field_placeholder',
                    'option_value'   => 'Type here the code you discovered.',
                ],
                [
                    'option_name'    => 'cards_shadow',
                    'option_value'   => true,
                ],
                [
                    'option_name'    => 'ranking_banner',
                    'option_value'   => '/storage/dummy_assets/ranking_banner.png',
                ],
                [
                    'option_name'    => 'code_hunter_duplicated',
                    'option_value'   => '/storage/dummy_assets/800x1180.png',
                ],
                [
                    'option_name'    => 'code_hunter_incorrect',
                    'option_value'   => '/storage/dummy_assets/800x1180.png',
                ],
                [
                    'option_name'    => 'ocr_ticket_phrases',
                    'option_value'   => json_encode(['petalo']),
                ],
                [
                    'option_name'    => 'ocr_ticket_active',
                    'option_value'   => true,
                ],
                [
                    'option_name'    => 'ocr_date_format',
                    'option_value'   => 'd/m/Y',
                ],
                [
                    'option_name'    => 'ocr_date_string',
                    'option_value'   => 'Fecha:',
                ],
                [
                    'option_name'    => 'ocr_date_characters',
                    'option_value'   => 13,
                ],
                [
                    'option_name'    => 'ocr_time_string',
                    'option_value'   => '/2025',
                ],
                [
                    'option_name'    => 'ocr_time_characters',
                    'option_value'   => 9,
                ],
                [
                    'option_name'    => 'ocr_transaction_string',
                    'option_value'   => 'Folio:',
                ],
                [
                    'option_name'    => 'ocr_transaction_characters',
                    'option_value'   => 21,
                ],
                [
                    'option_name'    => 'award_show_title',
                    'option_value'   => 'Ya ganaste',
                ],
                [
                    'option_name'    => 'awards_section_title',
                    'option_value'   => 'Mis compras recientes',
                ],
                [
                    'option_name'    => 'ga4_id',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'custom_css',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'ranking_enabled',
                    'option_value'   => false,
                ],
                [
                    'option_name'    => 'ranking_enabled_games',
                    'option_value'   => false,
                ],
                [
                    'option_name'    => 'ranking_enabled_tickets',
                    'option_value'   => false,
                ],
                [
                    'option_name'    => 'first_place_icon',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'second_place_icon',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'third_place_icon',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'first_place_icon_games',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'second_place_icon_games',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'third_place_icon_games',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'aplazo_endpoint',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'aplazo_merchant_id',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'aplazo_api_token',
                    'option_value'   => '',
                ],
                [
                    'option_name'    => 'cards_background_color',
                    'option_value'   => '#fff',
                ],
                [
                    'option_name'    => 'app_active',
                    'option_value'   => '#fff',
                ]
                
            ]
        );
    }
}
