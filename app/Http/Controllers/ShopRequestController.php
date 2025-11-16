<?php

namespace App\Http\Controllers;

use App\Models\ShopRequest;
use Illuminate\Http\Request;

class ShopRequestController extends Controller
{
    public function index()
    {
        $requests = ShopRequest::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $shopRequest = ShopRequest::create([
            'user_id' => $request->user()->id,
            ...$validated
        ]);

        return response()->json($shopRequest, 201);
    }

    public function updateStatus(Request $request, ShopRequest $shopRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $shopRequest->update($validated);
        return response()->json($shopRequest);
    }
}
