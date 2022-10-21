<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class ConfigBpjs extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table      = 'tConfig';
    protected $primaryKey = null;
    // protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'kodePPK',
        'namaPPK',
        'jenisConsId',
        'consId',
        'secretKey',
        'userKey',
        'statusAktif',
        'createdAt',
        'updatedAt',

    ];
}
