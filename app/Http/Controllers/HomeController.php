<?php namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\SigninRequest;
use App\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @Post("/sync", as="sync")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function sync(Request $request)
    {
        $type = $request->get("type");
        $last_sync = $request->get("last_sync");

        return response(['data' => $this->getSyncData($type, $last_sync)]);
    }

    public function getSyncData($table, $last_sync)
    {
        $query = DB::table($table);

        if ($last_sync) {

            $query->where('updated_at', '>', $last_sync);
        }

        return $query->orderBy("updated_at", "ASC")->limit(40)->get();
    }
}