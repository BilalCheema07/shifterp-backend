<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'uuid'                          => $this->uuid,
            'recurring_billing_start_date'  => $this->recurring_billing_start_date,
            'setup_fee'                     => $this->setup_fee,
            'setup_fee_start_date'          => $this->setup_fee_start_date,
            'sub_expire_date'               => $this->sub_expire_date,
            'total'                         => $this->total,
            'status'                        => $this->status,
            'pause_start_date'              => $this->pause_start_date,
            'pause_subscription_months'     => $this->pause_subscription_months / 7,
            'Subscription'                  => new SubscriptionsResource(@$this->subscription)
        ];
    }
}
