<?php

namespace App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavePenalGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'campaign_id' => [
                'required',
                'exists:campaigns,id'
            ],

            'content_type_id' => [
                'required',
                'exists:content_types,id'
            ],

            'title' => [
                'required',
                'regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿\$#\(\)\' \-]+$/'
            ],

            'description' => [
                'required',
                'regex:/^[A-Za-z0-9áéíóúÁÉÍÓÚÑñ,.;:!"¡?¿\$#\(\)\' \-]+$/'
            ],

            'slug' => [
                'required',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('penal_games', 'slug')->ignore($this->id)
            ],

            'init_date' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],

            'end_date' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],

            'gradient_1' => [
                'required',
                'string'
            ],

            'gradient_2' => [
                'required',
                'string'
            ],

            'max_points' => [
                'required',
                'numeric'
            ],

            'points_per_goal' => [
                'required',
                'numeric'
            ],

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            'featured_image' => [
                Rule::requiredIf(!$this->id),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'featured_image_disabled' => [
                Rule::requiredIf(!$this->id),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'game_bg_image_desktop' => [
                Rule::requiredIf(!$this->id),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096'
            ],

            /*
             * Por ahora no utilizamos background móvil.
             * La columna permanece en la base de datos,
             * pero el formulario no está obligado a enviarla.
             */
            'game_bg_image_movil' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096'
            ],

            'failed_image' => [
                Rule::requiredIf(!$this->id),
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            /*
            |--------------------------------------------------------------------------
            | Banner
            |--------------------------------------------------------------------------
            */

            'game_banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

            'game_banner_url' => [
                'nullable',
                'url'
            ],

            'game_banner_video' => [
                'nullable',
                'url'
            ],

            /*
            |--------------------------------------------------------------------------
            | Button configuration
            |--------------------------------------------------------------------------
            */

            'btn_background_color_1' => [
                'nullable',
                'string'
            ],

            'btn_background_color_2' => [
                'nullable',
                'string'
            ],

            'btn_border_color' => [
                'nullable',
                'string'
            ],

            'btn_border' => [
                'nullable',
                'boolean'
            ],

            'btn_text_active' => [
                'nullable',
                'string'
            ],

            'btn_text_inactive' => [
                'nullable',
                'string'
            ],

            'btn_shadow' => [
                'nullable',
                'boolean'
            ],

            'btn_text_color' => [
                'nullable',
                'string'
            ],

            'delete_image_holder_hidden' => [
                'nullable',
                'boolean'
            ],
        ];
    }
}