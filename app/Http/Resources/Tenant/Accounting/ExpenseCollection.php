<?php

namespace App\Http\Resources\Tenant\Accounting;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ExpenseCollection extends ResourceCollection
{
	/**
	 * Transform the resource collection into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
		return $this->getData();
	}
	
	private function getData()
	{
		$data = [];
		foreach ($this->collection as $item) {
			$data[] = [
				"uuid" => @$item->uuid,
				"date" => @$item->date,
				"data"  => json_decode(@$item->data, true),
				"expense_type" => [
					"uuid" => @$item->expenseType->uuid,
					"name" => @$item->expenseType->name,
				],
			];
		}
		return $data;
	}
}
