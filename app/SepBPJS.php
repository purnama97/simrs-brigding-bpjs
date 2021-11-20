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
    protected $table      = 'bridging_sep_bpjs';
    protected $primaryKey = 'no_sep';
    protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'no_sep',
        'no_rawat',
        'tglsep',
        'tglrujukan',
        'no_rujukan',
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
        'user',
        'nomr',
        'nama_pasien',
        'tanggal_lahir',
        'peserta',
        'jkel',
        'no_kartu',
        'tglpulang',
        'asal_rujukan',
        'eksekutif',
        'cob',
        'penjamin',
        'notelep',
        'katarak',
        'tglkkl',
        'keterangankkl',
        'suplesi',
        'no_sep_suplesi',
        'kdprop',
        'nmprop',
        'kdkab',
        'nmkab',
        'kdkec',
        'nmkec',
        'noskdp',
        'kddpjp',
        'nmdpjp'
    ];
}
