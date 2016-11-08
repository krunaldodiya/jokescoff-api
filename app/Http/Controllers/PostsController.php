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
        return Post::with(['category'])
            ->whereStatus(1)
            ->selectedCategory($request->category_id)
            ->search($request->search)
            ->limit(20)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @Post("/popular", as="get-featured-products")
     */
    public function getPopularPosts()
    {
        return Post::with('category')->where(['status' => 1])->orderBy('created_at', 'desc')->limit(10)->get();
    }

    /**
     * @Post("detail", as="posts-detail")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPostsDetail(Request $request)
    {
        $post = Post::with(['category'])->find($request->get('id'));

        $similar_posts = Post::with(['category'])
            ->where('id', '!=', $request->get('id'))
            ->whereHas('category', function ($query) use ($post) {
                return $query->where('category_id', $post->category->category_id);
            })
            ->get();

        $previous = Post::where('id', '<', $request->get('id'))->count() ? Post::where('id', '<', $request->get('id'))->first()->id : null;

        $next = Post::where('id', '>', $request->get('id'))->count() ? Post::where('id', '>', $request->get('id'))->first()->id : null;

        return response(['post_detail' => $post, 'similar_posts' => $similar_posts, 'previous_post' => $previous, 'next_post' => $next], 200);
    }

    /**
     * @Post("create", as="store-posts")
     * @param CreatePostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePostRequest $request)
    {
        $post = $this->posts->postManager($request, auth()->user());

        return redirect()->route('posts-detail', [$post->id, slug($post->title)])->with(['message.success' => 'Bingo! Your listing has been posted successfully.']);
    }

    /**
     * @Get("{post_id}/edit", as="edit-posts", middleware="auth")
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $this->authorize('managePost', $post = Post::with(['category', 'location', 'user', 'images'])->find($request->post_id));

        session(['selected_category' => getSelectedCategory($post->category->category_id)]);

        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * @Post("{post_id}/edit", as="update-posts", middleware="auth")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $post = $this->posts->postManager($request, Post::find($request->post_id)->user);

        return redirect()->route('posts-detail', [$post->id, slug($post->title)])->with(['message.success' => 'Bingo! Your listing has been updated successfully.']);
    }

    /**
     * @Post("{post_id}/destroy", as="destroy-posts", middleware="auth")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request)
    {
        return Post::find($request->post_id)->delete() ? response(['success' => true], 200) : response(['success' => 'false'], 401);
    }

    /**
     * @Post("download/photo", as="destroy-posts", middleware="auth")
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function downloadPhoto(Request $request)
    {
        $image_url = $request->input('path');
        $image_name = explode("/", $image_url);
        $image_path = public_path() . "/" . $image_url;

        return response()->download($image_path, end($image_name), []);
    }
}