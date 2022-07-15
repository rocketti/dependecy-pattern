<?php

namespace App\Rocketti\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "tests";

    protected $fillable = [
        'test'
    ];
}