<?php

namespace App\Exceptions;

use Exception;

use Illuminate\Support\Facades\Log;

class TestException extends Exception
{
    public function render($request){

        Log::info($this->getMessage());
        return response()->json(['foo'=>'bar']);
    }
}
