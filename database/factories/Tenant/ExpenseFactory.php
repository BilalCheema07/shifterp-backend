<?php

namespace Database\Factories\Tenant;

use App\Models\Tenant\Expense;
use App\Models\Tenant\ExpenseType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $expense_type = ExpenseType::where('parent_id', 0)->InRandomOrder()->first();
        $expense = new Expense();
        $expense->date = $this->faker->date();
        $expense->expense_type_id = $expense_type->id;
        
        $type_children = ExpenseType::where('parent_id', $expense_type->id)->InRandomOrder()->get()->toArray();
        foreach( $type_children as $expenses){
            $arr[] = ['type_id' => $expenses['uuid'], 'name' =>  $expenses['name'], 'amount' => $this->faker->randomNumber(3, true) ?? ""];
        }
        $expense->data = json_encode($arr);

        return $expense->toArray();
    }
}
