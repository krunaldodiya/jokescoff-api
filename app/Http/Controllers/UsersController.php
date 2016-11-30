<?php namespace App\Http\Controllers;

use App\Interfaces\AccountInterface;
use App\Interfaces\PostsInterface;
use App\User;
use App\Wallet;
use App\WalletHistory;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @Controller(prefix="users")
 * @Middleware("web")
 * @Middleware("jwt.auth")
 */
class UsersController extends Controller
{
    protected $posts;
    protected $account;
    protected $user;

    public function __construct(PostsInterface $posts, AccountInterface $account)
    {
        $this->posts = $posts;
        $this->account = $account;
        $this->user = JWTAuth::parseToken()->toUser();
    }

    /**
     * @Post("/me", as="users-profile")
     */
    public function getUsersProfile()
    {
        return response(['users_profile' => User::with('wallet')->whereId($this->user->id)->first()], 200);
    }

    /**
     * @Post("/posts", as="users-posts")
     */
    public function usersPosts()
    {
        return response(['users_posts' => $this->user->posts()->orderBy('created_at', 'desc')->paginate(10)], 200);
    }

    /**
     * @Post("profile/edit", as="update-profile")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUsersProfile(Request $request)
    {
        User::where(['id' => auth()->user()->id])
            ->update([
                'name' => $request->name,
                'city' => $request->city,
                'state' => $request->state,
                'area' => $request->area,
                'mobile' => $request->mobile,
                'about_me' => $request->about_me,
                'updated_profile' => 1
            ]);

        if (auth()->user()->updated_profile == 0) {
            $this->posts->updateWallet('updated_profile', auth()->user()->username, 'updated profile.');
        }

        return back()->with('message', 'Profile has been updated successfully.');
    }
}