<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user()->id,
            ...$validated
        ]);

        return response()->json($feedback, 201);
    }
}
