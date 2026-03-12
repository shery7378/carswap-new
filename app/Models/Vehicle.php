<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'brand_id',
        'model_id',
        'body_type_id',
        'fuel_type_id',
        'transmission_id',
        'drive_type_id',
        'exterior_color_id',
        'interior_color_id',
        'sales_method_id',
        'document_type_id',
        'vehicle_status_id',
        'title',
        'description',
        'price',
        'regular_price_label',
        'regular_price_description',
        'sale_price',
        'sale_price_label',
        'instant_savings_label',
        'request_price_option',
        'currency',
        'mileage',
        'year',
        'vin_number',
        'engine_number',
        'cylinder_capacity',
        'performance',
        'location',
        'address',
        'latitude',
        'longitude',
        'main_image',
        'gallery_images',
        'technical_expiration',
        'is_featured',
        'video_url'
    ];

    protected $casts = [
        'gallery_images' => 'json',
        'is_featured' => 'boolean',
        'technical_expiration' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }
    public function bodyType()
    {
        return $this->belongsTo(BodyType::class);
    }
    public function fuelType()
    {
        return $this->belongsTo(FuelType::class);
    }
    public function transmission()
    {
        return $this->belongsTo(Transmission::class);
    }
    public function driveType()
    {
        return $this->belongsTo(DriveType::class);
    }
    public function exteriorColor()
    {
        return $this->belongsTo(Color::class, 'exterior_color_id');
    }
    public function interiorColor()
    {
        return $this->belongsTo(Color::class, 'interior_color_id');
    }
    public function salesMethod()
    {
        return $this->belongsTo(SalesMethod::class);
    }
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
    public function vehicleStatus()
    {
        return $this->belongsTo(VehicleStatus::class);
    }
    public function properties()
    {
        return $this->belongsToMany(Property::class);
    }
}
