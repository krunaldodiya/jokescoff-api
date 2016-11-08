<?php namespace App\Http\Controllers\Admin;

use App\Post;
use App\Category;
use App\Http\Controllers\Controller;
use App\PostCategory;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="admin")
 * @Middleware("web")
 * @Middleware("admin")
 */
class AdminPostController extends Controller
{
    public $root_categories;

    public function __construct()
    {
        $this->root_categories = Category::with('child')->whereParentId(0)->get();
    }

    /**
     * @Get("posts/show", as="admin-show-posts")
     */
    public function show()
    {
        $posts = Post::with(['category'])->paginate(20);
        return view("admin.posts.show", compact("posts"));
    }

    /**
     * @Get("posts/create", as="admin-create-posts")
     */

    public function create()
    {
        return view("admin.posts.create")->with(['root_categories' => $this->root_categories]);
    }

    /**
     * @Post("posts/create", as="admin-store-posts")
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $filename = $request->post_image_old;
        if ($request->file('post_image')) {
            $filename = fileUpload($request->file('post_image'), public_path("uploads/posts"), [90, 30]);
        }

        $post = Post::create([
            "title" => $request->title,
            "description" => $request->description,
            "keywords" => $request->keywords,
            "cover" => isset($filename) ? $filename : "default.png"
        ]);

        $post_category = Category::with("parent")->find($request->category_id);
        PostCategory::create([
            "post_id" => $post->id,
            "parent_category_id" => $post_category->parent->id,
            "parent_category_name" => $post_category->parent->name,
            "category_id" => $post_category->id,
            "category_name" => $post_category->name
        ]);

        if ($post) return redirect()->route('admin-show-posts')
            ->with(["message.success" => "Post has been added successfully."]);
    }

    /**
     * @Get("posts/{post_id?}/edit", as="admin-edit-posts")
     * @param Request $request
     * @return $this
     */
    public function edit(Request $request)
    {
        $post = Post::whereId($request->post_id)->first();
        return view("admin.posts.edit")->with(['post' => $post, 'root_categories' => $this->root_categories]);;
    }

    /**
     * @Post("posts/{post_id?}/edit", as="admin-update-posts")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $filename = $request->post_image_old;
        if ($request->file('post_image')) {
            $filename = fileUpload($request->file('post_image'), public_path("uploads/posts"), [90, 30]);
        }

        $post = Post::whereId($request->post_id)->update([
            "title" => $request->title,
            "description" => $request->description,
            "keywords" => $request->keywords,
            "cover" => isset($filename) ? $filename : "default.png"
        ]);

        $post_category = Category::with("parent")->find($request->category_id);
        PostCategory::wherePostId($request->post_id)->update([
            "post_id" => $request->post_id,
            "parent_category_id" => $post_category->parent->id,
            "parent_category_name" => $post_category->parent->name,
            "category_id" => $post_category->id,
            "category_name" => $post_category->name
        ]);

        if ($post) return redirect()->route('admin-show-posts')
            ->with(["message.success" => "Post has been updated successfully."]);
    }

    /**
     * @Get("posts/{post_id?}/destroy", as="admin-destroy-posts")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $delete = Post::find($request->post_id)->delete();

        if ($delete) return redirect()->route('admin-show-posts')
            ->with(["message.success" => "Post has been deleted successfully."]);
    }
}

