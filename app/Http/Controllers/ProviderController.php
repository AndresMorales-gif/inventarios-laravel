<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function index(){
    	if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role == 'admin') {
                return redirect()->route('admin');   
            }            
            if ($role == 'cliente') {
                return redirect()->route('customer');   
            }             
        }
        else{
        	return redirect()->route('home'); 
        }
    	return view('provider/provider');
    }
}
