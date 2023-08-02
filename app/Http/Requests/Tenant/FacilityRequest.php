<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class FacilityRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		switch (last(request()->segments())) {
			case "update-dp":
				return $this->updateDp();
			case "list":
				return $this->filters();
			case "save":
				return $this->save();
			case "update":
				return $this->update();
			case "get-user-facilities":
				return $this->getUserFacilities();
			case "get-facility-users":
				return $this->getFacilityUsers();
			case "add-facility-in-user":
				return $this->addFacilitiesInUsers();
			case "add-users-in-facilities":
				return $this->addUsersInFacilities();
			case "make-active-facility":
				return $this->makeActiveFacility();
			case "remove-facilities-from-profile":
				return $this->removeUserFacilities();
			default:
				return $this->uuidCheck();
		}
	}



	protected function uuidCheck()
	{
		return [
			"uuid" => "required|exists:facilities,uuid"
		];
	}

	protected function filters()
	{
		return [
			"search" => "nullable|string",
			"status" => "nullable|in:0,1",
			"order" => "nullable|in:asc,desc"
		];
	}

	protected function save()
	{
		return [
			"name" => "required|unique:facilities,name",
			"admin_id" => "required|exists:users,uuid",
			"office_phone" => "required",
			"address" => "required",
			"city" => "required",
			"state" => "required",
			"zip_code" => "required",
			"status" => "nullable|in:0,1",
		];
	}

	protected function update()
	{
		return [
			"uuid" => "required|exists:facilities,uuid",
			"admin_id" => "required|exists:users,uuid",
			"name" => "required|unique:facilities,name,{$this->uuid},uuid",
			"office_phone" => "required",
			"address" => "required",
			"city" => "required",
			"state" => "required",
			"zip_code" => "required",
			"status" => "nullable|in:0,1",
		];
	}

	protected function getUserFacilities()
	{
		return [
			"user_id" => "required|exists:users,uuid"
		];
	}

	protected function getFacilityUsers()
	{
		return [
			"facility_id" => "required|exists:facilities,uuid"
		];
	}

	protected function addFacilitiesInUsers()
	{
		return [
			"user_ids" => "required|array",
			"user_ids.*" => "exists:users,uuid",
			"facility_ids" => "array",
			"facility_ids.*" => "exists:facilities,uuid",
			"type" => "required|in:single,multi"
		];
	}

	protected function addUsersInFacilities()
	{
		return [
			"facility_ids" => "required|array",
			"facility_ids.*" => "exists:facilities,uuid",
			"user_ids" => "nullable|array",
			"user_ids.*" => "exists:users,uuid",
			"type" => "required|in:single,multi"
		];
	}

	protected function makeActiveFacility()
	{
		return [
			"facility_id" => "required|exists:facilities,uuid"
		];
	}

	protected function removeUserFacilities()
	{
		return [
			"facility_ids" => "required|array",
			"facility_ids.*" => "exists:facilities,uuid"
		];
	}

	protected function updateDp()
	{
		return [
			"facility_id" => "required|exists:facilities,uuid",
			"image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048"
		];
	}
}
