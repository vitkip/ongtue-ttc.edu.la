<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Students', [
            'students' => Application::with(['major', 'academicYear'])
                ->where('is_public', true)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn (Application $application) => [
                    'id' => $application->id,
                    'full_name' => trim("{$application->first_name} {$application->last_name}"),
                    'photo_url' => $application->photo_url,
                    'major' => $application->major?->title,
                    'academic_year' => $application->academicYear?->label,
                ]),
        ]);
    }
}
