<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Plano;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalEmpresas = Empresa::count();
        $admins = User::where('role', 'admin')->count();

        return view(
            'view_superadmin.dashboard',
            compact(
                'totalEmpresas',
                'admins',
            )
        );
    }
}
