<?php namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Interfaces\PostsInterface;
use App\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Controller(prefix="posts")
 * @Middleware("web")
 */
class PostsController extends Controller
{
    protected $posts;

    public function __construct(PostsInterface $posts)
    {
        $this->posts = $posts;
    }

    /**
     * @Post("list", as="show-posts")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPostsList(Request $request)
    {
        return Post::whereStatus(1)->selectedCategory($request->category_id)->search($request->search)
            ->orderBy('created_at', 'desc')->paginate($request->category_id);
    }

    /**
     * @Post("/popular", as="get-featured-products")
     */
    public function getPopularPosts()
    {
        return Post::whereStatus(1)->orderBy('created_at', 'desc')->limit(10)->get();
    }

    /**
     * @Post("detail", as="posts-detail")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPostsDetail(Request $request)
    {
        $post = Post::find($request->get('id'));

        $similar_posts = Post::where('id', '!=', $request->get('id'))
            ->where('category_id', $request->get('id'))
            ->get();

        $previous = Post::where('id', '<', $request->get('id'))->count() ? Post::where('id', '<', $request->get('id'))->first()->id : null;

        $next = Post::where('id', '>', $request->get('id'))->count() ? Post::where('id', '>', $request->get('id'))->first()->id : null;

        return response(['post_detail' => $post, 'similar_posts' => $similar_posts, 'previous_post' => $previous, 'next_post' => $next], 200);
    }
}