<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = ['name', 'image', 'is_active'];

    public static function logoPlaceholder(): string
    {
        return '/assets/img/brand-placeholder.svg';
    }

    public static function logoSlug(?string $name): ?string
    {
        if (!$name) {
            return null;
        }

        return Str::slug(Str::ascii($name));
    }

    public static function localLogoPath(?string $name): ?string
    {
        $slug = self::logoSlug($name);

        return $slug ? public_path("assets/img/brands/{$slug}.png") : null;
    }

    public static function localLogoUrl(?string $name): string
    {
        $path = self::localLogoPath($name);

        if (!$path || !file_exists($path)) {
            return self::logoPlaceholder();
        }

        return '/assets/img/brands/' . self::logoSlug($name) . '.png';
    }

    public static function logoUrl(?string $name, ?string $image = null): string
    {
        if (!empty($image)) {
            return asset('storage/' . $image);
        }

        return self::localLogoUrl($name);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    public function models()
    {
        return $this->hasMany(VehicleModel::class);
    }
}
