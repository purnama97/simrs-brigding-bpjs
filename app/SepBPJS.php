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
    protected $table      = 'tSep';
    protected $primaryKey = 'noSep';
    protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'noSep',
        'noRawat',
        'tglsep',
        'tglrujukan',
        'noRujukan',
        'kdppkrujukan',
        'nmppkrujukan',
        'kdppkpelayanan',
        'nmppkpelayanan',
        'jnspelayanan',
        'catatan',
        'diagawal',
        'nmdiagnosaawal',
        'kdpolitujuan',
        'nmpolitujuan',
        'klsrawat',
        'lakalantas',
        'nomr',
        'namaRasien',
        'tanggalLahir',
        'peserta',
        'jkel',
        'no_kartu',
        'asalRujukan',
        'eksekutif',
        'cob',
        'penjamin',
        'notelep',
        'katarak',
        'tglkkl',
        'keterangankkl',
        'suplesi',
        'noSepSuplesi',
        'kdprop',
        'nmprop',
        'kdkab',
        'nmkab',
        'kdkec',
        'nmkec',
        'noskdp',
        'kddpjp',
        'nmdpjp',
        'user',
        'kodePPK',
        'tglpulang',
        'hakKelas',
        'pembiayaan',
        'tujuan',
        'penunjang',
        'assesment',
        'flagProcedure',
        'createdAt',
        'updatedAt',
    ];
}
