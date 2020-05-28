<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Str;
use App\Http\Requests\PasswordReset\CreateRequest;
use App\Http\Requests\PasswordReset\FindRequest;
use App\Http\Requests\PasswordReset\ResetRequest;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create(CreateRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user){
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        }

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],['email' => $user->email, 'token' => Str::random(60)]
        );

        if ($user && $passwordReset){
            $user->notify(
                new PasswordResetRequest($passwordReset->token, $request->reset_url)
            );
        }

        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ], 200);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find(FindRequest $request, $token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset){
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
            
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(60)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token has expired'
            ], 404);
        }
        return response()->json($passwordReset);
    }
    
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(ResetRequest $request)
    {

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset){
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user){
            return response()->json([
                'message' => 'We cant find a user with that e-mail address.'
            ], 404);
        }
        
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return response()->json(["user" => ["name" => $user->name]], 200);
    }
}