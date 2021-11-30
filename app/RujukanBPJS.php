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
    protected $table      = 'rs_bpjs_rujukan';
    protected $primaryKey = 'no_sep';
    protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'no_rujukan',
        'no_mr',
        'no_kartu',
        'kode_icd',
        'nama_penyakit',
        'tgl_berlaku_kunjungan',
        'tgl_rencana_kunjungan',
        'tgl_rujukan',
        'kode_ppkrujukan',
        'nama_ppkrujukan',
        'created_by',
        'created_at',
        'updated_at'
    ];
}
