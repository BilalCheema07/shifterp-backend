<?php

namespace App\Rules\Tenant;

use App\Models\Tenant\ExpenseRevenue;
use App\Models\Tenant\RevenueItem;
use Illuminate\Contracts\Validation\Rule;

class RevenueItemRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $revenue_item = RevenueItem::whereUUID($value)->first();
        if($revenue_item) {
            $expense_revenue = ExpenseRevenue::where('revenue_item_id', $revenue_item->id)->first();
            if(!$expense_revenue){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This revenue item is link with the Revenue/Expense. Kindly remove revenue item from the Expense/Revenue before delete.';
    }
}
