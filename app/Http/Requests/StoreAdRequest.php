<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Any authenticated user can upload an ad.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Force JSON response for validation errors in this API request.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // ----------------------------------------------------------------
            // Core vehicle identification
            // ----------------------------------------------------------------
            'brand_id'           => 'required|integer|exists:brands,id',
            'model_id'           => 'required|integer|exists:vehicle_models,id',
            'body_type_id'       => 'required|integer|exists:body_types,id',
            'vehicle_status_id'  => 'required|integer|exists:vehicle_statuses,id',
            'year'               => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'mileage'            => 'required|integer|min:0',
            'fuel_type_id'       => 'required|integer|exists:fuel_types,id',
            'cylinder_capacity'  => 'required|integer|min:1',
            'performance'        => 'required|integer|min:1',
            'transmission_id'    => 'required|integer|exists:transmissions,id',
            'drive_type_id'      => 'required|integer|exists:drive_types,id',

            // ----------------------------------------------------------------
            // Optional vehicle attributes
            // ----------------------------------------------------------------
            'exterior_color_id'  => 'nullable|integer|exists:colors,id',
            'interior_color_id'  => 'nullable|integer|exists:colors,id',
            'technical_expiration' => 'nullable|date',
            'document_type_id'   => 'nullable|integer|exists:document_types,id',
            'sales_method_id'    => 'nullable|integer|exists:sales_methods,id',
            'vin_number'         => 'nullable|string|max:191',   // chassis number
            'history_report'     => 'nullable|string|max:500',   // URL or text

            // ----------------------------------------------------------------
            // Location
            // ----------------------------------------------------------------
            'location'           => 'nullable|string|max:191',
            'address'            => 'nullable|string|max:255',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',

            // ----------------------------------------------------------------
            // Extra features / properties (many-to-many)
            // ----------------------------------------------------------------
            'properties'         => 'nullable|array',
            'properties.*'       => 'integer|exists:properties,id',

            // ----------------------------------------------------------------
            // Images & Video
            // Accepts up to 8 gallery images (landscape, max 10 MB each)
            // ----------------------------------------------------------------
            'main_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_images'     => 'nullable|array|max:12',
            'gallery_images.*'   => 'image|mimes:jpg,jpeg,png,webp|max:10240',
            'video_url'          => 'nullable|url|max:500',

            // ----------------------------------------------------------------
            // Documents
            // ----------------------------------------------------------------
            'documents'          => 'nullable|array|max:5',
            'documents.*'        => 'file|mimes:pdf|max:10240',

            // ----------------------------------------------------------------
            // Pricing
            // ----------------------------------------------------------------
            'price'              => 'required|numeric|min:0',
            'currency'           => 'nullable|string|max:10',

            // ----------------------------------------------------------------
            // Ad publication settings
            // ----------------------------------------------------------------
            'ad_status'          => 'nullable|in:published,rejected,pending,draft',
            // 'published' => publicly listed
            // 'pending'   => waiting for admin approval
            // 'rejected'  => not approved by admin
            // 'draft'     => saved but not published

            'owner_type'         => 'nullable|in:private,dealer',

            // ----------------------------------------------------------------
            // Ad title / description (optional — auto-generated if absent)
            // ----------------------------------------------------------------
            'title'              => 'nullable|string|max:191',
            'description'        => 'nullable|string',
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

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'brand_id.required'          => 'Please select a brand.',
            'brand_id.exists'            => 'The selected brand is invalid.',
            'model_id.required'          => 'Please select a model.',
            'model_id.exists'            => 'The selected model is invalid.',
            'body_type_id.required'      => 'Please select a design / body type.',
            'vehicle_status_id.required' => 'Please select the vehicle status.',
            'year.required'              => 'Year of manufacture is required.',
            'mileage.required'           => 'Odometer reading is required.',
            'fuel_type_id.required'      => 'Please select a fuel type.',
            'cylinder_capacity.required' => 'Cylinder capacity is required.',
            'performance.required'       => 'Power (kW) is required.',
            'transmission_id.required'   => 'Please select a gearbox type.',
            'drive_type_id.required'     => 'Please select a drive type.',
            'price.required'             => 'Price is required.',
            'gallery_images.max'         => 'You can upload a maximum of 12 pictures.',
            'gallery_images.*.max'       => 'Each image must be less than 10 MB.',
            'gallery_images.*.mimes'     => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.mimes'           => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.max'             => 'Main image must be less than 10 MB.',
            'documents.*.mimes'          => 'Supported document types: pdf.',
            'documents.*.max'            => 'Each document must be less than 10 MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     * Ensures numeric fields sent as strings are cast properly.
     */
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
