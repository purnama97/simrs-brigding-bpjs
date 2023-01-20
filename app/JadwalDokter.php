<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class JadwalDokter extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;

  protected $table = 'rs_jadwal_dokter';
  protected $primaryKey = 'jadwal_id';
  protected $keyType = 'string';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    "jadwal_id",
    "dokter_id",
    "poli_id",
    "hari_id",
    "buka",
    "tutup",
    "status_aktif",
    "kuota",
    "kuotaJkn",
    "kuotaNonJkn",
    "createdAt",
    "updatedAt"
  ];


  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ["Cpassword"];


  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

  public function subModule()
  {
    return $this->hasMany('App\AppUserRight', "user_id");
  }

  public function outlet()
  {
    return $this->belongsTo('App\AppUserOutlet');
  }

  public function company()
  {
    return $this->hasOne('App\AppuserCompany');
  }
}
