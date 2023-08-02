<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class CUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
                'id' => $this->id,
                'fname' => $this->fname,
                'lname' => $this->lname,
                'username' => $this->username,
                'remember_token' => $this->remember_token,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
                'state' => $this->state,
                'birth_date' => $this->birth_date,
                'hire_date' => $this->hire_date,
                'release_date' => $this->release_date,
                'status' => $this->status,
                'job_title' => $this->job_title,
                'department' => $this->department,
                'supervisor_name' => $this->supervisor_name,
                'shift' => $this->shift,
        ];
    }
}
