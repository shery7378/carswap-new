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
            'brand_id'             => 'sometimes|integer|exists:brands,id',
            'model_id'             => 'sometimes|integer|exists:vehicle_models,id',
            'body_type_id'         => 'sometimes|integer|exists:body_types,id',
            'vehicle_status_id'    => 'sometimes|integer|exists:vehicle_statuses,id',
            'year'                 => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
            'mileage'              => 'sometimes|integer|min:0',
            'fuel_type_id'         => 'sometimes|integer|exists:fuel_types,id',
            'cylinder_capacity'    => 'sometimes|integer|min:1',
            'performance'          => 'sometimes|integer|min:1',
            'transmission_id'      => 'sometimes|integer|exists:transmissions,id',
            'drive_type_id'        => 'sometimes|integer|exists:drive_types,id',
            'exterior_color_id'    => 'nullable|integer|exists:colors,id',
            'interior_color_id'    => 'nullable|integer|exists:colors,id',
            'technical_expiration' => 'nullable|date',
            'document_type_id'     => 'nullable|integer|exists:document_types,id',
            'sales_method_id'      => 'nullable|integer|exists:sales_methods,id',
            'vin_number'           => 'nullable|string|max:191',
            'history_report'       => 'nullable|string|max:500',
            'location'             => 'nullable|string|max:191',
            'address'              => 'nullable|string|max:255',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'properties'           => 'nullable|array',
            'properties.*'         => 'integer|exists:properties,id',
            'main_image'           => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'gallery_images'       => 'nullable|array|max:8',
            'gallery_images.*'     => 'image|mimes:jpg,jpeg,png|max:10240',
            'video_url'            => 'nullable|url|max:500',
            'documents'            => 'nullable|array|max:5',
            'documents.*'          => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
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
        ];
    }

    protected function prepareForValidation(): void
    {
        $intFields = [
            'brand_id', 'model_id', 'body_type_id', 'vehicle_status_id',
            'year', 'mileage', 'fuel_type_id', 'cylinder_capacity',
            'performance', 'transmission_id', 'drive_type_id',
            'exterior_color_id', 'interior_color_id', 'document_type_id',
            'sales_method_id',
        ];

        $data = [];
        foreach ($intFields as $field) {
            if ($this->has($field) && $this->input($field) !== null) {
                $data[$field] = (int) $this->input($field);
            }
        }

        if ($this->has('price') && $this->input('price') !== null) {
            $data['price'] = (float) $this->input('price');
        }

        $this->merge($data);
    }
}
