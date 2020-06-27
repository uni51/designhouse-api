<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Providers\RouteServiceProvider;
//use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }


    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        // check if the url is a valid signed url
        if(! URL::hasValidSignature($request)){
            return response()->json(["errors" => [
                "messgae" => "Invalid verification link or signature"
            ]], 422); // Laravelの場合、バリデーションエラーは422
        }

        // check if the user has already verified account
        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "messgae" => "Email address already verified"
            ]], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email successfully verified'], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = User::where('email', $request->email)->first();

        if(! $user) {
            return response()->json(["errors" => [
                "email" => "No user could be found with this email address"
            ]], 422);
        }

        // check if the user has already verified account
        if($user->hasVerifiedEmail()){
            return response()->json(["errors" => [
                "messgae" => "Email address already verified"
            ]], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => "verification link resent"]);
    }
}
