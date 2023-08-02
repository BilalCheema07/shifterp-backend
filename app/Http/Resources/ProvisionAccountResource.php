<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BillingResource;
use App\Http\Resources\SubscriptionResource;


class ProvisionAccountResource extends JsonResource
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
            'uuid' => $this->uuid,
            'company_name' => $this->company_name,
            'dba_name' => $this->dba_name,
            'address' => $this->address,
            'city' => $this->city,
            'phone' => $this->phone,
            'state' => $this->state,
            'zip' => $this->zip,
            'status' => $this->status,
            'company_owner' => new UserResource(@$this->user),
            'billing_details' => new BillingResource(@$this->billingContact),
            'Subscription_details' => SubscriptionResource::collection($this->subDetails),
            'Subscription_histories' => SubscriptionHistoryResource::collection($this->subHistory),
            'Sow' => SowResource::collection($this->sowUploads)
        ];
    }
}
