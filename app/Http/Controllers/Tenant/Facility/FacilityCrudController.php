<?php

namespace App\Http\Controllers\Tenant\Facility;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\FacilityRequest;
use App\Models\Tenant\{Facility, User as CUser};
use App\Http\Resources\Tenant\Facility\{FacilityCollection, FacilityResource};

class FacilityCrudController extends Controller
{
	public function list(FacilityRequest $request)
	{
		$facilities = new Facility;
		$facilities = $request->search ? $facilities->searchString($request->search) : $facilities;
		$facilities = isset($request->status) ? $facilities->where('status', $request->status) : $facilities;
		$facilities = $facilities->orderBy('id', $request->order ?? 'asc');
		$facilities = $facilities->with('users')->get();

		return json_response(200, __('Facility.get_data'), ['facilities' => new FacilityCollection($facilities)]);
	}

	public function getAllFacilityAdmins()
	{
		$f_admins = CUser::facilityAdmins()->select('id', 'uuid', 'fname', 'lname', 'email', 'phone')->get();
		
		$data = ['facility_admins' => $f_admins];
		return json_response(200, __('Facility.facility_admins'), $data);
	}
	
	public function get(FacilityRequest $request)
	{
		$facility = Facility::findByUUID($request->uuid);
		
		$data = ['facility'=> new FacilityResource($facility)];
		return json_response(200, __('Facility.get_single'), $data);
	}
	
	public function save(FacilityRequest $request)
	{
		$user = CUser::findByUUID($request->admin_id);
		$facility = Facility::create(array_merge($request->all(), ['admin_id' => $user->id]));
		$user->facilities()->sync($facility);

		$data = ['facility'=> new FacilityResource($facility)];
		return json_response(200, __('Facility.added'), $data);
	}
	
	public function update(FacilityRequest $request)
	{
		$user = CUser::findByUUID($request->admin_id);
		$facility = Facility::findByUUID($request->uuid);

		// Detach User facility
		$prev_user = CUser::find($facility->admin_id);

		$prev_user->facilities()->detach($facility->id);

		// update facility
		$facility->update([
			'name'			=>	$request->name,
			'admin_id'		=>	$user->id,
			'office_phone'	=>	$request->office_phone,
			'address'		=>	$request->address,
			'city'			=>	$request->city,
			'state'			=>	$request->state,
			'zip_code'		=>	$request->zip_code,
			'status'		=>	$request->status,
		]);

		$user->facilities()->sync($facility->id);

		$data = ['facility'=> new FacilityResource($facility)];
		return json_response(200, __('Facility.updated'), $data);
	}
	
	public function deleteAndReassign(FacilityRequest $request)
	{
		Facility::findByUUID($request->uuid)->delete();
		return json_response(200, __('Facility.removed'));
	}
}
