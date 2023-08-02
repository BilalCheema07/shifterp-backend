<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BillingResource extends JsonResource
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
            'uuid'              => $this->uuid,
            'fname'             => $this->fname,
            'lname'             => $this->lname,
            'title'             => $this->title,
            'email'             => $this->email,
            'contact_number'    => $this->contact_number,
            'address'           => $this->address,
            'city'              => $this->city,
            'state'             => $this->state,
            'zip'               => $this->zip,
        ];
    }
}
