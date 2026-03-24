<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = EmailTemplate::all()->groupBy('category');
        
        $selectedId = $request->get('template');
        $selectedTemplate = $selectedId 
            ? EmailTemplate::findOrFail($selectedId) 
            : EmailTemplate::first();

        // Get TinyMCE API key from settings
        $tinymce_api_key = \App\Models\Setting::where('key', 'tinymce_api_key')->first()->value ?? 'zhq60pp8shp0wjkatmio4l9686eu1aqofwzmu475rtgnd098';

        return view('content.apps.email-templates.index', compact('templates', 'selectedTemplate', 'tinymce_api_key'));
    }

    public function updateEditorSettings(Request $request)
    {
        $request->validate([
            'tinymce_api_key' => 'nullable|string|max:255',
        ]);

        \App\Models\Setting::updateOrCreate(
            ['key' => 'tinymce_api_key'],
            ['value' => $request->tinymce_api_key]
        );

        return back()->with('success', 'Editor settings updated successfully!');
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update([
            'name' => $request->name,
            'subject' => $request->subject,
            'body' => $request->body,
        ]);

        return redirect()->route('admin.email-templates.index', ['template' => $template->id])
            ->with('success', 'Email template updated successfully!');
    }
}
