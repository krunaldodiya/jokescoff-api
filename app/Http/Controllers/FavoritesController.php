<?php namespace App\Http\Controllers;

use App\Favorites;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="users/favorites")
 * @Middleware("web")
 * @Middleware("auth")
 */
class FavoritesController extends Controller
{
    /**
     * @Get("/", as="show-favorites")
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFavorites()
    {
        $posts = auth()->user()->favorites()->get();

        return view('users.favorites', compact('posts'));
    }

    /**
     * @Get("{post_id}/toggle", as="toggle-favorites")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toggleFavorites(Request $request)
    {
        if (Favorites::wherePostId($request->post_id)->count()) {
            auth()->user()->favorites()->detach($request->post_id);
        } else {
            auth()->user()->favorites()->attach($request->post_id);
        }

        if ($request->ajax()) {
            return response(['success' => true], 200);
        }

        return back();
    }
}