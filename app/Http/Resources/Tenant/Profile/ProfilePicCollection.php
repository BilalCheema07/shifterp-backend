<?php

namespace App\Http\Resources\Tenant\Profile;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProfilePicCollection extends ResourceCollection
{
	public function toArray($request)
	{
		return [
			'profile_pic' => $this->getData()
		];
	}

	private function getData()
	{   
		$data = [] ;
		foreach ($this->collection as $pic) {
			$data = [
				'uuid' => $pic->uuid,
				'url' => $pic->url
			];
		}

		return $data;
	}
}
