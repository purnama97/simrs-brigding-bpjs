<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Kamar extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table      = 'rs_kamar';
    protected $primaryKey = null;
    // protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        "kamar_id",
        "nama_kamar",
        "kelas_id",
        "tarif",
        "status_aktif",
        "status_kamar",
        "no_bed",
        "created_at",
        "updated_at",
        "kelas_bpjs",
        "kode_kamar_bpjs",      
    ];
}
