<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class SepBPJS extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table      = 'rs_bridging_sep';
    protected $primaryKey = 'noSep';
    protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        "noSep",
        "noRawat",
        "tglsep",
        "tglRujukan",
        "noRujukan",
        "kdPpkRujukan",
        "nmPpkRujukan",
        "kdPpkPelayanan",
        "nmPpkPelayanan",
        "jnsPelayanan",
        "catatan",
        "diagAwal",
        "nmDiagnosa",
        "kdPoliTujuan",
        "nmPoliTujuan",
        "klsRawat",
        "lakaLantas",
        "user",
        "noMr",
        "namaPasien",
        "tglLahir",
        "peserta",
        "jKel",
        "noKartu",
        "tglPulang",
        "asalRujukan",
        "eksekutif",
        "cob",
        "penjamin",
        "noTelp",
        "katarak",
        "tglKkl",
        "keteranganKkl",
        "suplesi",
        "noSepSuplesi",
        "kdProv",
        "nmProp",
        "kdKab",
        "nmKab",
        "kdKec",
        "nmKec",
        "noSkdp",
        "kdDpjp",
        "nmDpjp",
        "hakKelas",
        "tujuan",
        "penunjang",
        "assesment",
        "flagProcedure",
        "createdAt",
        "updatedAt"
    ];
}
