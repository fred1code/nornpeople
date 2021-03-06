<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enable', 'user_id', 'del', 'col', 'numIn', 'numEx', 'street',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
