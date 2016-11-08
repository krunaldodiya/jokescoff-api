<?php namespace App\Repositories;

use App\Account;
use App\Company;
use App\Location;
use App\ReferralCode;
use App\User;
use App\Interfaces\AccountInterface as AccountContract;
use Carbon\Carbon;
use Request;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class AccountRepository implements AccountContract
{
    private $posts;

    public function __construct(PostsRepository $posts)
    {
        $this->posts = $posts;
    }

    public function addUserDetails($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'is_admin' => false,
            'status' => 1
        ]);

        return ($user) ? $user : false;
    }

    public function processLogin($request)
    {
        $credentials = [
            get_fields_type($request->email) => $request->email,
            'password' => $request->password
        ];

        try {
            if ( ! $token = JWTAuth::attempt($credentials)) return response()->json(['error' => 'Wrong email or password.'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unable to create token.'], 500);
        }

        return auth()->user();
    }
}