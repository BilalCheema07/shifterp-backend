<?php

namespace App\Http\Controllers\Tenant\Facility;

use App\Http\Controllers\Controller;
use App\Models\Tenant\{Facility, User};
use App\Http\Requests\Tenant\FacilityRequest;
use App\Http\Resources\Tenant\Facility\{FacilityUserCollection,FacilityCollection};

class FacilityUserController extends Controller
{
	public function getUserFacilities(FacilityRequest $request)
	{
		$user = User::WhereUUID($request->user_id)->with('facilities.primaryContact')->first();
		
		return json_response(200, __('Facility.user_facilities'), ['facilities' => new FacilityCollection($user->facilities)] );
	}
	
	public function getFacilityUsers(FacilityRequest $request)
	{
		$facility = Facility::findByUUID($request->facility_id);
		$data = ['users' => new FacilityUserCollection($facility->users)];
		
		return json_response(200, __('Facility.user_facilities'), $data);
	}
	
	public function makeActiveFacility(FacilityRequest $request)
	{
		$facility = Facility::findByUUID($request->facility_id);
		$user = User::find(auth()->user()->tenant_user_id);
		
		foreach ($user->facilities as $user_facility) {
			if ($facility->id == $user_facility->id) {
				$user->facilities()->detach($user_facility->id);
				$user->facilities()->attach($user_facility->id, ['is_active' => 1]);
			} else {
				$user->facilities()->detach($user_facility->id);
				$user->facilities()->attach($user_facility->id, ['is_active' => 0]);
			}
		}
		
		return json_response(200, __('Facility.active_facility'));
	}
	
	public function addFacilitiesInUsers(FacilityRequest $request)
	{	
		$facilities = Facility::getByUUID($request->facility_ids)->pluck('id');
		if ($request->type == 'single') {
			$user = User::findByUUID($request->user_ids[0]);
			if(count($facilities) > 0){
				$user->facilities()->sync($facilities);
			} else {
				$user->facilities()->detach();
			}
		} elseif($request->type == 'multi') {
			$users = User::getByUUID($request->user_ids);
			foreach ($users as $user) {
				$user->facilities()->sync($facilities, false);
			}
		}
		return json_response(200, __('Facility.facility_user'));
	}
	
	public function removeUserFacilities(FacilityRequest $request)
	{	
		$facility_ids = Facility::getByUUID($request->facility_ids)->pluck('id');
		$user = User::find(auth()->user()->tenant_user_id);
		
		if (count($facility_ids) > 0) {
			$user->facilities()->detach($facility_ids);
		}
		return json_response(200, __('Facility.remove_user_from_facility'));
	}
	
	public function addUsersInFacilities(FacilityRequest $request)
	{
		$u_ids = User::getByUUID($request->user_ids)->pluck('id');
		
		if($request->type == 'single') {
			$facility = Facility::findByUUID($request->facility_ids[0]);
			if(count($u_ids) > 0){
				$facility->users()->sync($u_ids);
			} else {
				$facility->users()->detach();
			}
		} elseif($request->type == 'multi') {
			$facilities = Facility::getByUUID($request->facility_ids);
			foreach ($facilities as $facility) {
				$facility->users()->sync($u_ids, false);
			}
		}
		return json_response(200, __('Facility.user_facility'));
	}
}
