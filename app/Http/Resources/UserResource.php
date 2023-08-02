<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProvisionAccountResource;


class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->provision_account_id > 0 ){
            return [
                'uuid' => $this->uuid,
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'tenant_user_id' => $this->tenant_user_id,
                'tenant_id' => $this->tenant_id,
                'provision_account_id' => $this->provision_account_id,
                'role' => $this->role,
                // 'provision_account' => new ProvisionAccountResource($this->provisionAccount)
            ];
        }
        return [
            'uuid' => $this->uuid,
			'username' => $this->username,
			'email' => $this->email,
			'phone' => $this->phone,
			'tenant_user_id' => $this->tenant_user_id,
			'tenant_id' => $this->tenant_id,
			'role' => $this->role,
        ];
    }
}
