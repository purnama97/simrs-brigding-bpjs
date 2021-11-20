<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AppUser extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;

  protected $table = 'Tbl_AppUser';
  protected $primaryKey = 'USER_ID';
  protected $keyType = 'string';
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    "USER_ID", "LoginName", "UserName",  "PinCode", "Barcode",  "reg_date", "Wpassword", "fdevelop", "dokter_id", "poli_id"
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
