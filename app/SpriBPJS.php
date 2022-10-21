<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class SpriBPJS extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table      = 'tSPRI';
    protected $primaryKey = null;
    // protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'noSPRI',
        'noSEP',
        'kdDokter',
        'nmDokter',
        'kdDiagnosa',
        'nmDiagnosa',
        'user',
        'statusAktif',
        'createdAt',
        'updatedAt'        
    ];
}
