<?php namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * @Controller(prefix="admin")
 * @Middleware("web")
 * @Middleware("admin")
 */
class AdminCategoryController extends Controller
{
    public $root_categories;

    public function __construct()
    {
        $this->root_categories = Category::with('child')->whereParentId(0)->get();
    }

    /**
     * @Get("category/show/{category_id?}", as="admin-show-category")
     * @param Request $request
     * @return $this
     */

    public function show(Request $request)
    {
        return view("admin.categories.show")->with(['root_categories' => $this->root_categories]);
    }

    /**
     * @Get("category/create", as="admin-create-category")
     */

    public function create()
    {
        return view("admin.categories.create")->with(['root_categories' => $this->root_categories]);
    }

    /**
     * @Post("category/create", as="admin-store-category")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        $filename = $request->category_image_old;
        if ($request->file('category_image')) {
            $filename = fileUpload($request->file('category_image'), "uploads/categories", [90, 30]);
        }

        Category::create([
            "parent_id" => request()->get("parent_id"),
            "name" => request()->get("name"),
            "cover" => isset($filename) ? $filename : "default.png"
        ]);

        return redirect()->route('admin-show-category')
            ->with(["message.success" => "Category has been added successfully."]);
    }

    /**
     * @Get("category/{category_id?}/edit", as="admin-edit-category")
     * @param Request $request
     * @return $this
     */

    public function edit(Request $request)
    {
        return view("admin.categories.edit")->with(['root_categories' => $this->root_categories, 'category' => Category::find($request->category_id)]);
    }

    /**
     * @Post("category/{category_id?}/edit", as="admin-update-category")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request)
    {
        $filename = $request->category_image_old;
        if ($request->file('category_image')) {
            $filename = fileUpload($request->file('category_image'), "uploads/categories", [90, 30]);
        }

        Category::whereId($request->category_id)->update([
            "parent_id" => request()->get("parent_id"),
            "name" => request()->get("name"),
            "cover" => isset($filename) ? $filename : "default.png"
        ]);

        return redirect()->route('admin-show-category')
            ->with(["message.success" => "Category has been updated successfully."]);
    }

    /**
     * @Get("category/{category_id?}/delete", as="admin-delete-category")
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function delete(Request $request)
    {
        $category = Category::whereId($request->category_id)->first();
        $category->child()->delete();
        $category->delete();

        return redirect()->route('admin-show-category')
            ->with(["message.success" => "Category has been deleted successfully."]);
    }
}

