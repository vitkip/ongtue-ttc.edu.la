<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;

class ApplicationController extends Controller
{
    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['photo'] = $request->file('photo')->store('applications/photos', 'public');

        if ($request->hasFile('certificate')) {
            $data['certificate'] = $request->file('certificate')->store('applications/certificates', 'public');
        }

        if ($request->hasFile('transcript')) {
            $data['transcript'] = $request->file('transcript')->store('applications/transcripts', 'public');
        }

        Application::create([
            ...$data,
            'source_page' => substr((string) $request->header('Referer'), 0, 50),
        ]);

        return back();
    }
}
