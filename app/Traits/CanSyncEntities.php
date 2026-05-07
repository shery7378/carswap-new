<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait CanSyncEntities
{
    /**
     * Resolve an entity ID from a name or existing ID.
     * If the value is a string name, it will search for it or create it.
     *
     * @param string $modelClass The fully qualified class name of the model.
     * @param mixed $value The name (string) or ID (numeric).
     * @param array $extraAttrs Additional attributes for creation (e.g., brand_id for models).
     * @return int|null
     */
    protected function resolveEntityId($modelClass, $value, $extraAttrs = [])
    {
        if (empty($value)) {
            return null;
        }

        // If it's already a numeric ID, return it as integer
        if (is_numeric($value)) {
            return (int) $value;
        }

        // If it's a string, try to find it by name
        try {
            // Case-insensitive search if supported, otherwise standard where
            $record = $modelClass::where('name', $value)->first();

            if (!$record) {
                // Not found, create new record
                $data = array_merge(['name' => $value], $extraAttrs);
                $record = $modelClass::create($data);
                
                Log::info("Created new vehicle entity: {$modelClass} with name '{$value}'");
            }

            return $record->id;
        } catch (\Exception $e) {
            Log::error("Error resolving vehicle entity '{$value}' for model '{$modelClass}': " . $e->getMessage());
            return null;
        }
    }
}
