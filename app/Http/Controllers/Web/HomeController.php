<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->take(4)->get();
        return view('home', compact('categories'));
    }
}
