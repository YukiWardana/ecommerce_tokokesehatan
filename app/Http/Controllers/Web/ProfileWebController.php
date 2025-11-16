<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ShopRequest;
use Illuminate\Http\Request;

class ProfileWebController extends Controller
{
    public function show()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        auth()->user()->update($validated);
        return back()->with('success', 'Profile updated successfully');
    }

    public function storeShopRequest(Request $request)
    {
        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        ShopRequest::create([
            'user_id' => auth()->id(),
            'shop_name' => $validated['shop_name'],
            'description' => $validated['description'],
        ]);

        return back()->with('success', 'Shop request submitted successfully');
    }
}
