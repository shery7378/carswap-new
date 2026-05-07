<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }

    public function rules(): array
    {
        return [
            'brand_id'             => 'sometimes',
            'model_id'             => 'sometimes',
            'body_type_id'         => 'sometimes',
            'vehicle_status_id'    => 'sometimes',
            'year'                 => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            'mileage'              => 'sometimes|integer|min:0',
            'fuel_type_id'         => 'sometimes',
            'cylinder_capacity'    => 'sometimes|integer|min:1',
            'performance'          => 'sometimes|integer|min:1',
            'battery_capacity'     => 'nullable|numeric|min:0',
            'range'                => 'nullable|integer|min:0',
            'transmission_id'      => 'sometimes',
            'drive_type_id'        => 'sometimes',
            'exterior_color_id'    => 'nullable',
            'interior_color_id'    => 'nullable',
            'technical_expiration' => 'nullable|date',
            'document_type_id'     => 'nullable',
            'sales_method_id'      => 'nullable',
            'vin_number'           => 'nullable|string|max:191',
            'history_report'       => 'nullable|string|max:500',
            'location'             => 'nullable|string|max:191',
            'address'              => 'nullable|string|max:255',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'properties'           => 'nullable|array',
            'properties.*'         => 'integer|exists:properties,id',
            'main_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_images'       => 'nullable|array|max:12',
            'gallery_images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:10240',
            'video_url'            => 'nullable|url|max:500',
            'documents'            => 'nullable|array|max:5',
            'documents.*'          => 'file|mimes:pdf|max:10240',
            'price'                => 'sometimes|numeric|min:0',
            'currency'             => 'nullable|string|max:10',
            'ad_status'            => 'nullable|in:published,rejected,pending,draft',
            'owner_type'           => 'nullable|in:private,dealer',
            'title'                => 'nullable|string|max:191',
            'description'          => 'nullable|string',
            'exchange_preferences' => 'nullable|array',
            'exchange_preferences.*.brand_id' => 'nullable|exists:brands,id',
            'exchange_preferences.*.model_id' => 'nullable|exists:vehicle_models,id',
            'exchange_preferences.*.body_type_id' => 'nullable|exists:body_types,id',
            'exchange_preferences.*.fuel_type_id' => 'nullable|exists:fuel_types,id',
            'exchange_preferences.*.transmission_id' => 'nullable|exists:transmissions,id',
            'exchange_preferences.*.drive_type_id' => 'nullable|exists:drive_types,id',
            'exchange_preferences.*.year_from' => 'nullable|integer',
            'exchange_preferences.*.cylinder_capacity' => 'nullable|integer',
            'exchange_preferences.*.battery_capacity' => 'nullable|numeric|min:0',
            'exchange_preferences.*.range' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'gallery_images.max'         => 'You can upload a maximum of 12 pictures.',
            'gallery_images.*.max'       => 'Each image must be less than 10 MB.',
            'gallery_images.*.mimes'     => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.mimes'           => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.max'             => 'Main image must be less than 10 MB.',
            'documents.*.mimes'          => 'Supported document types: pdf.',
            'documents.*.max'            => 'Each document must be less than 10 MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $intFields = [
            'brand_id', 'model_id', 'body_type_id', 'vehicle_status_id',
            'year', 'mileage', 'fuel_type_id', 'cylinder_capacity',
            'performance', 'transmission_id', 'drive_type_id',
            'exterior_color_id', 'interior_color_id', 'document_type_id',
            'sales_method_id', 'range',
        ];

        $data = [];
        foreach ($intFields as $field) {
            if ($this->has($field) && $this->input($field) !== null) {
                if (is_numeric($this->input($field))) {
                    $data[$field] = (int) $this->input($field);
                }
            }
        }

        if ($this->has('price') && $this->input('price') !== null) {
            $data['price'] = (float) $this->input('price');
        }

        if ($this->has('battery_capacity') && $this->input('battery_capacity') !== null) {
            $data['battery_capacity'] = (float) $this->input('battery_capacity');
        }

        $this->merge($data);
    }
}
