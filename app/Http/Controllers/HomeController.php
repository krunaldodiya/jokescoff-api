<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @Middleware("web")
 */
class HomeController extends Controller
{
    /**
     * @Get("/login", as="get-login")
     */
    public function login()
    {
        return view('admin.account.login');
    }

    /**
     * @Get("/logout", as="get-logout")
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->to('/login');
    }

    /**
     * @Post("/login", as="post-login")
     * @param SigninRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginProcess(SigninRequest $request)
    {
        $auth = Auth::attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);

        return $auth ? redirect('admin/dashboard') : back()->with(['error' => "Login Failed."]);
    }

    /**
     * @Get("/401", as="401")
     */
    public function unAuthorizedAccess()
    {
        return 'unauthorized access';
    }

    /**
     * @Post("/sync", as="sync")
     */
    public function sync()
    {
        return response(['sync' => true]);
    }
}