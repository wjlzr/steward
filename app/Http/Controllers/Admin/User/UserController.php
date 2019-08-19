<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;

class UserController extends Controller
{

    //用户列表页
    public function index()
    {

        return view('admin/user/index');

    }

}
