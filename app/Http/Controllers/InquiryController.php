<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        Inquiry::create([
            ...$request->validated(),
            'source_page' => substr((string) $request->header('Referer'), 0, 50),
        ]);

        return back();
    }
}
