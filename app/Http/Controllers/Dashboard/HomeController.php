<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $permissions = [
            'view new users' => 'dashboard.user.view.new',
            'create user' => 'dashboard.user.create',
            'view users' => 'dashboard.user.view',
            'create permission' => 'dashboard.permission.create',
            'view permissions' => 'dashboard.permission.view',
            'create role' => 'dashboard.role.create',
            'view roles' => 'dashboard.role.view',
            'upload excel' => 'dashboard.excel.create',
            'view excel files' => 'dashboard.excel.view',
        ];

        foreach ($permissions as $permission => $route) {
            if ($user->can($permission)) {
                return redirect()->route($route);
            }
        }

        return view('dashboard.home');
    }


}
