<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Antrian extends Model implements AuthenticatableContract, AuthorizableContract
{
  use Authenticatable, Authorizable;
  protected $table      = 'rs_counter_antrian';
  protected $primaryKey = null;
  // protected $keyType    = 'string';
  public $timestamps    = false;
  protected $fillable = [
    "kodeBooking",
    "kodePoli",
    "kodeDokter",
    "noKartu",
    "nik",
    "noRm",
    "noHp",
    "nomorAntrian",
    "angkaAntrean",
    "isJkn",
    "bookingDate",
    "jamPraktek",
    "noReferensi",
    "jenisKunjungan",
    "isCall",
    "isCancel",
    "isCheckIn",
    "isCallOn",
    "note",
    "createdAt",
    "updatedAt"     
  ];
}