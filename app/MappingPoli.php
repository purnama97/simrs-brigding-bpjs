<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class MappingPoli extends Model implements AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;
  protected $table      = 'rs_mapping_poli_asuransi';
  protected $primaryKey = null;
  // protected $keyType    = 'string';
  public $timestamps    = false;
  protected $fillable = [
    "kodeAsuransi",
    "kodePoli",
    "namaPoli",
    "kodePoliAsuransi",
    "namaPoliAsuransi",
    "createdAt",
    "updatedAt"    
  ];
}