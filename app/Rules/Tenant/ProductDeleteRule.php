<?php

namespace App\Rules\Tenant;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Tenant\KitProduct;
use App\Models\Tenant\Product;

class ProductDeleteRule implements Rule
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
        $products = Product::WhereIn('uuid',$value)->get();
        if($products){
            $kit_record = 0;
            foreach ($products as $product ) {
                $kit = KitProduct::where('product_id',$product->id)->get();
                
                if(count($kit) > 0){
                    $kit_record =1;
                }
            }

            if($kit_record == 0 )
            {
                return true;
            }
        }

       
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'One of selected product is used in Kit. Kindly remove product from kit before deleting';
    }
}
