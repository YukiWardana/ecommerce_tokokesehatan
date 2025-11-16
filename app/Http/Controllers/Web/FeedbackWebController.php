<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackWebController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Feedback::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}
