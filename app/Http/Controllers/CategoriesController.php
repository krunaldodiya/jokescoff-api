<?php

namespace App\Http\Controllers;

use App\Category;

/**
 * @Controller(prefix="categories")
 * @Middleware("web")
 */
class CategoriesController extends Controller
{
    /**
     * @Post("/tree", as="get-parent-with-child-categories")
     */
    public function getCategoriesTree()
    {
        return response([
            'categories' => Category::with('child')->whereParentId(0)->get(),
        ], 200);
    }
}