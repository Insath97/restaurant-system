<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Customer Index,admin'])->only(['index']);
    }

    public function index()
    {
        $customers = User::all();
        return view('admin.customers.index', compact('customers'));
    }
}
