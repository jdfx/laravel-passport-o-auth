<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\DetailsRequest;

class APIAuthController extends Controller
{

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login(LoginRequest $request)
    {
        try{
            $user = User::with('roles')->where(['email' => $request->email])->firstOrFail();
            $token = $user->createToken(Config::get('app.name'))->accessToken;
            return response()->json([
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => ["name" => $user->name, "email" => $user->email, "role" => $user->roles[0]->name]
                ], 200);

        }catch(\Exception $e){
            Log::info($e->getMessage().' '.$e->getFile().' '.$e->getLine());
            return response()->json([
                'message' => "Internal Server Error - this has been logged"
            ], 500);
        }
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(RegisterRequest $request)
    {
        try{
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            $user_role = Role::findByName('user');
            $user->assignRole($user_role);
            $response['token'] =  $user->createToken(Config::get('app.name'))->accessToken;
            $response['name'] =  $user->name;
            return response()->json($response, 200);

        }catch(\Exception $e){
            Log::info($e->getMessage().' '.$e->getFile().' '.$e->getLine());
            return response()->json([
                'message' => "Error with registration."
            ], 422);
        } 
    }


    /** 
     * get authed user details
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details(DetailsRequest $request)
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
