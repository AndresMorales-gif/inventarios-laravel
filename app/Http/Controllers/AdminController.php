<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


    public function index()
    {
    	if (Auth::check()) {
            $role = Auth::user()->role;            
            if ($role == 'proveedor') {
                return redirect()->route('provider');   
            }  
            if ($role == 'cliente') {
                return redirect()->route('customer');   
            }             
        }
        else{
            return redirect()->route('home'); 
        }
        return view('admin/admin');
    }

    public function newProduct()
    {
    	if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role == 'proveedor') {
                return redirect()->route('provider');   
            }  
            if ($role == 'cliente') {
                return redirect()->route('customer');   
            }             
        }
        else{
            return redirect()->route('home'); 
        }
        return view('admin/newProduct');
    }
}
