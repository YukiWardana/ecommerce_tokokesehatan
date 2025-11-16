<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Guestbook;
use Illuminate\Http\Request;

class GuestbookWebController extends Controller
{
    public function index()
    {
        $entries = Guestbook::orderBy('created_at', 'desc')->paginate(10);
        return view('guestbook.index', compact('entries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Guestbook::create($validated);
        return back()->with('success', 'Thank you for your message!');
    }

    public function destroy(Guestbook $guestbook)
    {
        $guestbook->delete();
        return back()->with('success', 'Entry deleted');
    }
}
