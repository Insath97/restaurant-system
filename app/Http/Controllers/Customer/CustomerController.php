<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $categories = Category::with(['menus' => function ($query) {
            $query->limit(3);
        }])->get();

        return view('client.home', compact('categories'));
    }

    public function about()
    {
        return view('client.about');
    }
}
