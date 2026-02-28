<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'budget_amount',
    ];

    protected $casts = [
        'budget_amount' => 'decimal:2',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    public function getTotalSpentAttribute()
    {
        return $this->expenses()->sum('amount');
    }
}
