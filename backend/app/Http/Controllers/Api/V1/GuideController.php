<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GuideController extends Controller
{
    private function apiResponse($data, $message = '', $success = true, $status = 200)
    {
        return response()->json(['success' => $success, 'data' => $data, 'message' => $message, 'errors' => []], $status);
    }

    public function index(Request $request)
    {
        $query = Guide::where('is_published', true);
        if ($request->category) $query->where('category', $request->category);
        if ($request->search) $query->where('title', 'like', "%{$request->search}%");
        return $this->apiResponse($query->latest()->paginate($request->per_page ?? 9));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'image' => 'nullable|string',
            'author' => 'nullable|string',
            'read_time' => 'nullable|integer',
            'is_published' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
        return $this->apiResponse(Guide::create($data), 'Guide created', true, 201);
    }

    public function show($id)
    {
        return $this->apiResponse(Guide::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $guide = Guide::findOrFail($id);
        $guide->update($request->validate([
            'title' => 'sometimes|string',
            'category' => 'sometimes|string',
            'excerpt' => 'nullable|string',
            'body' => 'sometimes|string',
            'image' => 'nullable|string',
            'author' => 'nullable|string',
            'read_time' => 'nullable|integer',
            'is_published' => 'boolean',
        ]));
        return $this->apiResponse($guide, 'Guide updated');
    }

    public function destroy($id)
    {
        Guide::findOrFail($id)->delete();
        return $this->apiResponse([], 'Guide deleted');
    }
}
