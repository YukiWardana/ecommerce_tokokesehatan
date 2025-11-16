<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')->get();
        return response()->json($customers);
    }

    public function show(User $user)
    {
        return response()->json($user->load(['orders', 'feedbacks']));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Cannot delete admin users'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Customer deleted']);
    }
}
