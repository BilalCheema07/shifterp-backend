<?php

namespace App\Http\Controllers\Tenant\User;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Role;

class RoleManagementController extends Controller
{
    public function list()
    {
        $roles = Role::all();

        $data = ['roles'=> $roles];
        $msg = 'Roles were successfully fetched from DB.';

        return json_response(200, $msg, $data);
    }
}
