<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compte extends Model
{
    use HasFactory;

    protected $fillable = [
        'login', 'password', 'user_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
