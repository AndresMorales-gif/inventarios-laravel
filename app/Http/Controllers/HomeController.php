<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role == 'admin') {
                return redirect()->route('admin');   
            }
            if ($role == 'proveedor') {
                return redirect()->route('provider');   
            }  
            if ($role == 'cliente') {
                return redirect()->route('customer');   
            }             
        }
       
        
        return view('home');
    }
}
