<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class RencanaKontrolBPJS extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table      = 'tRencanaKontrol';
    protected $primaryKey = null;
    // protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'noSuratKontrol',
        'noSEP',
        'kdDokter',
        'nmDokter',
        'kdPoliKontrol',
        'nmPoliKontrol',
        'tglRencanaKontrol',
        'user',
        'createdAt',
        'updatedAt',
    ];
}
