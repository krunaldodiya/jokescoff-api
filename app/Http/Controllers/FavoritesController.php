<?php namespace App\Http\Controllers;

use App\Favorites;
use App\User;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="favorites")
 * @Middleware("web")
 */
class FavoritesController extends Controller
{
    /**
     * @Post("/show", as="show-favorites")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFavorites(Request $request)
    {
        $favorites = Favorites::whereUserId($request->get('user_id'))->get();

        return response(['favorites' => $favorites], 200);
    }

    /**
     * @Post("toggle", as="toggle-favorites")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toggleFavorites(Request $request)
    {
        $favorite = User::whereId($request->get('user_id'))->first()->favorites()->toggle($request->get('post_id'));
        $favorited = count($favorite['attached']) ? true : false;

        return response(['favorited' => $favorited], 200);
    }

    /**
     * @Post("status", as="get-favorite-status")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getFavoriteStatus(Request $request)
    {
        $favorited = Favorites::wherePostIdAndUserId($request->get('post_id'), $request->get('user_id'))->count() ? true : false;

        return response(['favorited' => $favorited], 200);
    }
}