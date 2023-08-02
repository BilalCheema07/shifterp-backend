<?php

namespace App\Models\Tenant;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory, UUID;

    protected $guarded = ['id', 'uuid'];

    protected $cast = [ 
        'data' => 'array'
    ];

    //Filters Scopes
    public function scopeExpenseTypeString($query, $expense_type)
	{
		return $query->whereHas('expenseType', function ($query) use ($expense_type) {
			$query->whereIn('uuid', $expense_type);
		});
	}

	public function scopeDate($query, $date)
	{
		return $query->where(function ($inner_query) use ($date) {
			$inner_query->where('date', $date);
		});
	}

	public function scopeWhereSearch($query, $search)
	{
		return $query->whereHas('expenseType', function($query) use ($search) {
			$query->where('name', 'LIKE', '%'.$search.'%');
		});
	}

    //Relations
	public function expenseType() :BelongsTo
	{
		return $this->belongsTo(ExpenseType::class);
	}
}
