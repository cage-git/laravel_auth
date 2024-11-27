<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $store = myGlobalFunction('World');
        return view('test')->with('store',$store);
    }
}
