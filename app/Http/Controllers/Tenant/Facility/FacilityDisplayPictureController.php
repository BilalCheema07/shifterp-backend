<?php

namespace App\Http\Controllers\Tenant\Facility;

use App\Http\Controllers\Controller;
use App\Models\Tenant\{Facility, File};
use App\Http\Requests\Tenant\FacilityRequest;
use App\Http\Resources\Tenant\Profile\ProfilePicCollection;

class FacilityDisplayPictureController extends Controller
{
	public function uploadDisplayPic(FacilityRequest $request)
	{
		$file = new File;
		$facility = Facility::findByUUID($request->facility_id);
		$old_dps = $facility->displayPic;
		$file->removeImage($facility, $old_dps);
		
		if ($request->hasFile('image')) {
			$type = 'fac_dp';
			$file = $file->createImage($request, $type);
			if ($file) {
				$facility->files()->attach([$file->id]);
			}
			
			$get_facility = Facility::find($facility->id);
			$display_pic = $get_facility->displayPic;
			
			return json_response(200, __('Profile.updated'), new ProfilePicCollection($display_pic));
		}
		return json_response(200, __('Profile.removed'));
	}
}
