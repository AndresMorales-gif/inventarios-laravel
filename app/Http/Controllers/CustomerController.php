<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(){
    	if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role == 'admin') {
                return redirect()->route('admin');   
            }
            if ($role == 'proveedor') {
                return redirect()->route('provider');   
            }                        
        }
        else{
        	return redirect()->route('home'); 
        }
    	return view('customer/customer');
    }
}
