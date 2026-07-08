<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Islamikit\Starterkit\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        Gate::authorize('view settings');

        $settings = app(GeneralSettings::class);

        return Inertia::render('Admin/Setting/Index', [
            'settings' => [
                'app_name' => $settings->app_name,
                'app_description' => $settings->app_description,
                'date_format' => $settings->date_format,
            ],
            'urls' => [
                'update' => route('admin.settings.update'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        Gate::authorize('edit settings');

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'date_format' => 'required|string|in:d/m/Y,m/d/Y,Y-m-d',
        ]);

        $settings = app(GeneralSettings::class);
        $settings->app_name = $validated['app_name'];
        $settings->app_description = $validated['app_description'] ?? '';
        $settings->date_format = $validated['date_format'];
        $settings->save();

        return back()->with('message', 'Settings updated successfully.');
    }
}