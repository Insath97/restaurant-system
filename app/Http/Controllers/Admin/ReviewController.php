<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Review Index,admin'])->only(['index']);
    }
    
    public function index()
    {
        $reviews = Review::with(['user', 'reviewable'])->get();
        return view('admin.review.index', compact('reviews'));
    }
}
