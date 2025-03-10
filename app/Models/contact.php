<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class contact extends Model
{
    protected $fillable = ['name', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
