<?php

namespace App\Rules\Tenant;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Product;
use PhpParser\Builder\Function_;

class CustomerDeleteRule implements Rule
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
        $customer = Customer::whereUUID($value)->first();
        if($customer){

            $products = Product::where('customer_id',$customer->id)->where('status',1)->first();
            if(!$products){
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
        return 'Customer has active products. Kindly remove customer products or assign to other customer before delete.';
    }
}
