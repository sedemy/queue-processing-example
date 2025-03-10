<?php

namespace App\Models;

use App\Models\User;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'status'];

    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
