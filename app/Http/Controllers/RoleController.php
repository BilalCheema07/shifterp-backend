<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Tenant\Role;

class RoleController extends Controller
{
	public function index($companyId)
	{
		$tenant = Tenant::find($companyId);
		
		tenancy()->initialize($tenant);
		$roles = Role::all();
		
		return view('admin.roles.index', compact('roles','tenant'));
	}
	
	public function create($tenant)
	{
		$tenants = Tenant::find($tenant);
		tenancy()->initialize($tenants);
		
		return view('admin.roles.create', compact('tenants'));
	}
	
	public function store(Request $request)
	{
		$tenant = Tenant::where('id', $request->domain)->first();
		tenancy()->initialize($tenant);
		
		$role = Role::where('name', $request->name)->first();
		if($role) {
			return[
				'success' => false,
				'msg' => 'Role name already exist'
			];
		}
		$req_name = str_replace(' ', '-', $request->name);
		
		Role::create([
			'name' => $request->name,
			'slug' => $req_name
		]);
		return response()->json([
			'success' => true,
			'msg' => 'Role added successfully'
		]);
	}

	public function edit($id, $tenant)
	{
		$tenants = Tenant::find($tenant);
		tenancy()->initialize($tenants);
		
		$roles = Role::find($id);
		return view('admin.roles.edit', compact('tenants', 'roles'));
	}

	public function update(Request $request, $id)
	{
		$tenant = Tenant::where('id', $request->domain)->first();
		tenancy()->initialize($tenant);
		
		$role = Role::where('name', $request->name)->first();
	
		if($role) {
			return[
				'success' => false,
				'msg' => 'Role name already exist'
			];
		}

		$req_name = str_replace('', '-', $request->name);

		Role::where('id', $id)->update([
			'name' => $request->name,
			'slug' =>$req_name
		]);

		return response()->json([
			'success' => true,
			'msg' => 'Role Updated successfully'
		]);
	}
}
