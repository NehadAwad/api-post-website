<?php

namespace App\Traits;

use phpDocumentor\Reflection\Types\True_;

trait ApiTrait{

    public function isAdmin()
    {
        try{
            if (auth()->user()->role == 'admin') {
                return true;
            }
            return false;
        }catch (\Exception $e){
            Log::warning($e->getMessage()."\n".$e->getFile()."\n".$e->getLine());
        }
    }
}
