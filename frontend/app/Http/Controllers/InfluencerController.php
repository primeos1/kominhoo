<?php

namespace App\Http\Controllers;

use App\Services\InfluencerService;
use Illuminate\Http\Request;

class InfluencerController extends Controller
{
    public function __construct(private InfluencerService $influencerService)
    {
    }

    public function show()
    {
        return view('pages.be-an-influencer');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|max:150',
            'phone'     => 'required|string|max:20',
            'instagram' => 'required|string|max:80',
            'tiktok'    => 'nullable|string|max:80',
            'followers' => 'required|string',
            'niche'     => 'required|string',
            'location'  => 'required|string|max:100',
            'skin_type' => 'nullable|string',
            'message'   => 'required|string|min:20|max:1000',
        ]);

        if ($this->influencerService->emailExists($request->email)) {
            return back()
                ->withErrors(['email' => 'An application with this email address already exists.'])
                ->withInput();
        }

        $this->influencerService->create($request->only([
            'name', 'email', 'phone', 'instagram', 'tiktok',
            'followers', 'niche', 'location', 'skin_type', 'message',
        ]));

        return redirect()->route('influencer.show')
            ->with('success', "Your application has been submitted! We'll be in touch within 3–5 business days.");
    }
}
