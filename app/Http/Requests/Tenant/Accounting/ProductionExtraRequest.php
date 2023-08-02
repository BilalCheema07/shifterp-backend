<?php

namespace App\Http\Requests\Tenant\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class ProductionExtraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
	{
		switch (last(request()->segments())) {
			case "add-new-production-extra":
				return $this->addNewProductionExtra();
			case "get-single-production-extra":
				return $this->getSingleProductionExtra();
            case "list-production-extra":
                return $this->listingProductionExtra();
            case "update-production-extra":
                return $this->updateProductionExtra();
			default:
				return $this->deleteProductionExtra();
		}
    }

    protected function addNewProductionExtra()
    {
        return [
            "name" => "required|string|unique:production_extras,name",
            "unit_id" => "required|exists:units,uuid",
            "amount" => "required|string",
            "direct_material" => "nullable|in:0,1",
			"status" => "nullable|in:0,1",
        ];
    }

    protected function getSingleProductionExtra()
    {
        return [
            "production_extra_uuid" => "required|exists:production_extras,uuid"
        ];
    }

    protected function deleteProductionExtra()
    {
        return [
            "production_extra_uuid" => "required|array",
            "production_extra_uuid.*" => "exists:production_extras,uuid"
        ];
    }

    protected function listingProductionExtra()
    {
        return [
            "direct_material" => "nullable|in:0,1",
            "status" => "nullable|in:0,1",
            "search" => "nullable|string"
        ];
    }

    protected function updateProductionExtra()
    {
        return [
            "uuid" => "required|exists:production_extras,uuid",
            "name" => "required|string|unique:production_extras,name,{$this->uuid},uuid",
            "unit_id" => "required|exists:units,uuid",
            "amount" => "required|string",
            "direct_material" => "nullable|in:0,1",
			"status" => "nullable|in:0,1",
        ];
    }
}
