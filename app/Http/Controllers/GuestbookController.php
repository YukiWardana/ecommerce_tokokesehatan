<?php

namespace App\Http\Controllers;

use App\Models\Guestbook;
use Illuminate\Http\Request;

class GuestbookController extends Controller
{
    public function index()
    {
        $entries = Guestbook::orderBy('created_at', 'desc')->get();
        return response()->json($entries);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $entry = Guestbook::create($validated);
        return response()->json($entry, 201);
    }

    public function destroy(Guestbook $guestbook)
    {
        $guestbook->delete();
        return response()->json(['message' => 'Entry deleted']);
    }
}
