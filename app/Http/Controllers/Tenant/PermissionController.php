<?php

namespace App\Http\Controllers\Tenant;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Permission;
use App\Models\Tenant\Role;
use App\Models\Tenant\Facility;

class PermissionController extends Controller
{
	public function index(){
		
		$roles = Role::all();
		$permissions = Permission::where('parent_id', 0)->with('children')->orderBy('id')->get();
		$facilities = Facility::with('primaryContact')->get();

		$data = ['roles' => $roles, 'permissions' => $permissions, 'facilities' => $facilities];
		$msg = 'All facilities, roles and permission are successfully fetched.';
		
		return json_response(200, $msg, $data);
	}
}
