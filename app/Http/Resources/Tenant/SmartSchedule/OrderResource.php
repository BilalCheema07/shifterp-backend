<?php

namespace App\Http\Resources\Tenant\SmartSchedule;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            
            "date"      => $this->date,
            "time"      => $this->time,
            "status"    => $this->status,
            "po_notes"  => $this->po_notes,
            "notes"     => $this->notes,
            "customer"  => [ 
                "uuid"  => $this->customer->uuid,
                "name"  => $this->customer->name,
                "code"  => $this->customer->code,
            ],
            "driver1"   => [
                'uuid'      =>$this->drivers[0]->uuid,
                'name'      =>$this->drivers[0]->name,
            ],
            "driver2"   => [
                'uuid'      => $this->drivers[1]->uuid,
                'name'      => $this->drivers[1]->name,
            ],
        ];
    }
}
