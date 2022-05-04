<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;

class DataController extends Controller
{
    public function open(){

        $data = "this data is open for all users";

        return response()->json(compact('data'),200);
    }

    public function closed(){

        $data = "this data is for authenticated users only";

        return response()->json(compact('data'),200);
    }

    public function invalidateToken(){
        auth()->invalidate();
    }
}
