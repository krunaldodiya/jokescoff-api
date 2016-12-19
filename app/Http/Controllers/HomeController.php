<?php namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Http\Requests\SigninRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        auth()->logout();

        return redirect()->to('/login');
    }

    /**
     * @Post("/login", as="post-login")
     * @param SigninRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function loginProcess(SigninRequest $request)
    {
        $auth = auth()->attempt([
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
     * @Post("/sync/categories", as="sync-categories")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function syncCategories(Request $request)
    {
        $newest = $request->get("newest") ? $request->get("newest") : Carbon::now()->format("Y-m-d h:i:s");
        $oldest = $request->get("oldest") ? $request->get("oldest") : Carbon::now()->format("Y-m-d h:i:s");

        $data = Category::where('updated_at', '>', $newest)
            ->orWhere('updated_at', '<', $oldest)
            ->orderBy("updated_at", "DESC")
            ->get();

        return $data;
    }

    /**
     * @Post("/sync/posts", as="sync-posts")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function syncPosts(Request $request)
    {
        $newest = $request->get("newest") ? $request->get("newest") : Carbon::now()->format("Y-m-d h:i:s");
        $oldest = $request->get("oldest") ? $request->get("oldest") : Carbon::now()->format("Y-m-d h:i:s");

        $data = Post::where('updated_at', '>', $newest)
            ->orWhere('updated_at', '<', $oldest)
            ->orderBy("updated_at", "DESC")
            ->whereHas("category", function ($query) use ($request) {
                if ($request->get("category_id")) {
                    $query->where("category_id", $request->get("category_id"));
                }

                return $query;
            })
            ->limit($request->get("limit"))
            ->get();

        return $data;
    }
}