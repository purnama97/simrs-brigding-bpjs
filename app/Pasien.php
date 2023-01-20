<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table      = 'rs_pasien';
    protected $primaryKey = 'pasien_id';
    protected $keyType    = 'string';
    public $timestamps    = false;
    protected $fillable = [
        'nama_pasien',
        'no_ktp',
        'salut',
        'alamat',
        'provinsi_id',
        'kota_kab_id',
        'kecamatan_id',
        'kelurahan_id',
        'rt',
        'rw',
        'kode_pos',
        'tmp_lahir',
        'tgl_lahir',
        'agama',
        'pendidikan',
        'gol_darah',
        'telp',
        'hp',
        'pekerjaan_id',
        'ibu',
        'pasangan',
        'jk',
        'status_pasien',
        'created_at',
        'updated_at',
        'status_aktif',
        'asuransi',
        'no_kartu',
        'pasien_id',
        'email',
        'ayah',
        'exp_kartu',
        'pj_pasien',
        'berkas_rm'
    ];
}
