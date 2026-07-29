<?php

namespace App\Http\Controllers\AdminUser;

use App\Http\Controllers\Controller;

class PlanoController extends Controller
{
    public function index()
    {
        return view('view_admin_user.planos');
    }
}