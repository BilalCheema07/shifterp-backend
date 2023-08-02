<?php

namespace App\Http\Controllers\Tenant\Dependency;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Tenant\{Dependency, DependencyType};
use App\Http\Resources\Tenant\Dependency\{DependencyResource, DependencyCollection, DependencyTypeResource, DependencyTypeCollection};

class DependencyController extends Controller
{
	public function dependencyTypeList()
	{
		$types = DependencyType::all();

		$data = [
			"types" => new DependencyTypeCollection($types)
		];
		return json_response(
			200,
			"Dependencies fetched successfully",
			$data
		);
	}
	
	public function addDependency(Request $request)
	{
		$request->validate([
			"type_id" => "required|exists:dependency_types,uuid",
			"dependencies.*.name" => "required",
			"dependencies.*.module" => "nullable",
		]);

		DB::beginTransaction();
		try {
			$type = DependencyType::findByUUID($request->type_id);
			$my_dependency = array();
			foreach ($request->dependencies as $depend) {
				$dependency = (object) $depend;
				$my_dependency[] = Dependency::create([
					"type_id" => $type->id,
					"name" => $dependency->name,
					"module" => @$dependency->module ?? "",
				]);
			}
			DB::commit();

			return json_response(200, "Dependencies added successfully.");
		} catch (Exception $e) {
			DB::rollBack();
			return json_response(500, "Something went wrong. Dependencies could not be added.");
		}
	}

	public function specificDependency($slug = "", $module = "")
	{
		if ($slug != "") {
			if ($module == "") {
				$type = DependencyType::where("slug", $slug)->with("dependencies")->first();
			} else {
				$type = DependencyType::where("slug", $slug)
					->with("dependencies", function ($query) use ($module) {
						$query->where("module", $module)->orWhere("module", "");
					})
					->first();
			}
			if (!$type) {
				return json_response(500, "Please specify a valid dependency type.");
			}

			return json_response(
				200,
				"Dependencies fetched successfully.",
				[$slug => new DependencyTypeResource($type)]
			);
		}
		return json_response(500, "Please specify a valid dependency type.");
	}

	public function deleteDependency($uuid)
	{
		$dependency = Dependency::findByUUID($uuid);
		if(!$dependency) {
			return json_response(500, "Please select a valid dependency to delete.");
		} else {
			$dependency->delete();
			return json_response(200, "Dependency Deleted successfully.");
		}
	}
}
