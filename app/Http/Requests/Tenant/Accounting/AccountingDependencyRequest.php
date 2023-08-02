<?php

namespace App\Http\Requests\Tenant\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class AccountingDependencyRequest extends FormRequest
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
        return [
            "name" => "nullable|string|in:production_extra,pricing,revenue,expense,expense_revenue",
            "category_id" => "nullable|string|exists:categories,uuid",
            "customer_id" => "nullable|string|exists:customers,uuid",
            "facility_id" => "nullable|string|exists:facilities,uuid",
        ];
    }
}
