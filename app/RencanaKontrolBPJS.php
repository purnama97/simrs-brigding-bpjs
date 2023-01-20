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
    protected $table      = 'rs_bridging_surat_kontrol';
    protected $primaryKey = null;
    // protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        "noSuratKontrol",
        "tglSurat",
        "noSep",
        "tglRencanaKontrol",
        "kdDokter",
        "nmDokter",
        "kdPoli",
        "nmPoli",
        "namaPasien",
        "tglLahir",
        "jnsKontrol",
        "noKartu",
        "statusAktif",
        "user",
        "createdAt",
        "updatedAt"   
    ];
}
