<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $guarded = [];

    protected function Threads()
    {
        return $this->hasMany(Thread::class);
    }
}
