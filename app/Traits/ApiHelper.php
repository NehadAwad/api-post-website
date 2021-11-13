<?php


namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function isAdmin()
    {
        if (auth()->user()->role == 'admin') {
            return true;
        }
        return false;
    }

    protected function isWriter($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('writer');
        }

        return false;
    }


}
