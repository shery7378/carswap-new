<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update section display name (left panel + header) for mailing list info
        DB::table('cms_sections')
            ->where('id', 3)
            ->orWhere('slug', 'mailing-list-info')
            ->update([
                'name' => 'Csatlakozz a levelezőlistához',
                'title' => 'Csatlakozz a levelezőlistához',
            ]);

        // Update the default item title shown as "Main Info" in the UI
        $sectionId = DB::table('cms_sections')
            ->where('id', 3)
            ->orWhere('slug', 'mailing-list-info')
            ->value('id');

        if ($sectionId) {
            DB::table('cms_items')
                ->where('section_id', $sectionId)
                ->where('title', 'Main Info')
                ->update(['title' => 'Fő információk']);
        }
    }

    public function down(): void
    {
        // Intentionally no-op: we don't want to revert user-visible CMS labels.
    }
};

