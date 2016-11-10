<?php namespace App\Http\Controllers;

use App\Interfaces\AccountInterface;

use App\Http\Requests\SigninRequest;

use App\Http\Requests\SignupRequest;

/**
 * @Controller(prefix="account")
 * @Middleware("web")
 */
class AccountController extends Controller
{
    private $account;

    public function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }

    /**
     * @Post("/login", as="signin-process")
     * @param SigninRequest $request
     * @return
     */

    public function login(SigninRequest $request)
    {
        return $this->account->processLogin($request);
    }

    /**
     * @Post("/register", as="create-account-process")
     * @param SignupRequest $request
     * @return bool
     */

    public function register(SignupRequest $request)
    {
        $user = $this->account->addUserDetails($request);

        return $user ? $this->account->processLogin($request) : false;
    }
}
