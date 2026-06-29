<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transmissions', function (Blueprint $table) {
            if (!Schema::hasColumn('transmissions', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('transmissions')->nullOnDelete();
            }
        });

        $automaticId = DB::table('transmissions')
            ->whereIn('name', ['Automatic', 'Automata'])
            ->value('id');

        if ($automaticId) {
            DB::table('transmissions')
                ->whereIn('name', ['6-speed', '8-speed', '9-speed'])
                ->update(['parent_id' => $automaticId, 'updated_at' => now()]);

            DB::table('transmissions')
                ->where('id', '!=', $automaticId)
                ->where(function ($query) {
                    $query->where('name', 'like', '%6%')
                        ->orWhere('name', 'like', '%8%')
                        ->orWhere('name', 'like', '%9%');
                })
                ->where(function ($query) {
                    $query->where('name', 'like', '%Automatic%')
                        ->orWhere('name', 'like', '%Automata%');
                })
                ->update(['parent_id' => $automaticId, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::table('transmissions', function (Blueprint $table) {
            if (Schema::hasColumn('transmissions', 'parent_id')) {
                $table->dropConstrainedForeignId('parent_id');
            }
        });
    }
};
