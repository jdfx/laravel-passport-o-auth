<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\User\UserDetailsRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

    /** 
     * get user details
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details(UserDetailsRequest $request)
    {
        
        try {

            $user = User::with('roles')->find(Auth::user()->id);

            return response()->json([
                "name" => $user->name,
                "email" => $user->email,
                "role" => $user->roles[0]->name
            ]);

        } catch (\Exception $e) {
            Log::info($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            return response()->json([
                'message' => "Unauthorized"
            ], 401);
        }
    }
}
