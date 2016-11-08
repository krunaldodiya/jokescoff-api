<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Seo;
use Illuminate\Http\Request;

/**
 * @Controller(prefix="admin")
 * @Middleware("web")
 * @Middleware("admin")
 */
class AdminDashboardController extends Controller
{
    /**
     * @Get("/", as="get-admin")
     */
    public function admin()
    {
        return view('admin.dashboard.dashboard');
    }

    /**
     * @Get("/dashboard", as="admin-dashboard")
     */
    public function dashboard()
    {
        return view('admin.dashboard.dashboard');
    }
}
