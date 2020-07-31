<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Languagelist extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table="language_list";
    protected $fillable = [
        'language_code', 'language_name', 'created_at', 'updated_at'
    ];
}
