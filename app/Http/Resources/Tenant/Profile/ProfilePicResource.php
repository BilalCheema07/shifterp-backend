<?php

namespace App\Http\Resources\Tenant\Profile;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfilePicResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'uuid' => $this->uuid,
			'url' => $this->url
		];
	}
}
