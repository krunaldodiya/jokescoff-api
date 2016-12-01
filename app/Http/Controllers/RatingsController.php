<?php namespace App\Http\Controllers;

use App\Ratings;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="ratings")
 * @Middleware("web")
 */
class RatingsController extends Controller
{
    /**
     * @Post("average-ratings", as="show-average-ratings")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAverageRatings(Request $request)
    {
        $average_ratings = $this->calculateAverageRatings($request->get('post_id'));
        $average_ratings = $average_ratings ? $average_ratings : 0;

        return response(['average_ratings' => number_format($average_ratings, 1)], 200);
    }

    /**
     * @Post("users-ratings", as="get-users-ratings")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUsersRatings(Request $request)
    {
        $users_ratings = Ratings::whereUserIdAndPostId($request->get('user_id'), $request->get('post_id'))->pluck('ratings')->first();
        $users_ratings = $users_ratings ? $users_ratings : 0;

        return response(['users_ratings' => number_format($users_ratings, 1)], 200);
    }

    /**
     * @Post("toggle", as="toggle-ratings")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function toggleRatings(Request $request)
    {
        Ratings::updateOrCreate(['post_id' => $request->get('post_id'), 'user_id' => $request->get('user_id')])->update([
            'ratings' => $request->get('ratings')
        ]);

        $average_ratings = $this->calculateAverageRatings($request->get('post_id'));

        return response(['average_ratings' => number_format($average_ratings, 1)], 200);
    }

    /**
     * @param $post_id
     * @return float|int
     */
    public function calculateAverageRatings($post_id)
    {
        $ratings = Ratings::wherePostId($post_id)->pluck('ratings');

        $average_ratings = 0;
        foreach ($ratings as $rating) {
            $average_ratings = $average_ratings + $rating;
        }

        if (count($ratings)) {
            $average_ratings = $average_ratings / count($ratings);
            return $average_ratings;
        }
        return $average_ratings;
    }
}