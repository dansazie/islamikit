<?php

namespace Islamikit\Starterkit\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view activity logs');

        $logs = Activity::with('causer')
            ->when($request->search, fn($q, $search) => $q->where('description', 'like', "%{$search}%"))
            ->when($request->subject_type, fn($q, $type) => $q->where('subject_type', $type))
            ->latest()
            ->paginate(15)
            ->through(fn ($log) => [
                'id' => $log->id,
                'description' => $log->description,
                'subject_type' => $log->subject_type ? class_basename($log->subject_type) : null,
                'causer' => $log->causer ? $log->causer->name : 'System',
                'created_at' => $log->created_at->diffForHumans(),
                'properties' => $this->formatProperties($log->properties),
            ]);

        $subjectTypes = Activity::distinct()->pluck('subject_type')
            ->map(fn($type) => ['label' => class_basename($type), 'value' => $type])
            ->values()
            ->toArray();

        return Inertia::render('Admin/ActivityLog/Index', [
            'logsList' => $logs->items(),
            'subjectTypes' => $subjectTypes,
            'totalLogs' => $logs->total(),
            'perPage' => $logs->perPage(),
            'urls' => [
                'index' => route('admin.activity-logs.index'),
            ],
        ]);
    }

    private function formatProperties($properties)
    {
        $hidden = ['password', 'password_confirmation', 'remember_token', 'current_password'];
        $formatted = [];

        if ($properties->has('old') || $properties->has('attributes')) {
            $old = $properties->get('old', []);
            $new = $properties->get('attributes', []);

            foreach ($new as $key => $value) {
                if (in_array($key, $hidden)) continue;
                $formatted[] = [
                    'field' => $key,
                    'old' => $old[$key] ?? null,
                    'new' => $value,
                ];
            }
        } else {
            foreach ($properties->except(['causer_id', 'causer_type', 'subject_id', 'subject_type'])->toArray() as $key => $value) {
                if (in_array($key, $hidden)) continue;
                $formatted[] = [
                    'field' => $key,
                    'old' => null,
                    'new' => is_array($value) ? json_encode($value) : $value,
                ];
            }
        }

        return $formatted;
    }
}