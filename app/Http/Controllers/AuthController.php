<?php

namespace App\Http\Controllers;

use App\BpjsUserAntrol;
use App\Company;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
// use Spatie\ArrayToXml\ArrayToXml;
use Laravel\Lumen\Routing\Controller as BaseController;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class AuthController extends BaseController
{
  /**
   * The request instance.
   *
   * @var \Illuminate\Http\Request
   */
  private $request;
  private $CompCode;
  private $OutletCode;
  /**
   * Create a new controller instance.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function __construct(Request $request)
  {
    $this->request    = $request;
    $this->CompCode   = $request->header('CompCode');
  }
  /**
   * Create a new token.
   *
   * @param  \App\BpjsUserAntrol   $user
   * @return string
   */
  protected function jwt(BpjsUserAntrol $user)
  {
    $payload = [
      'iss' => "lumen-jwt", // Issuer of the token
      'sub' =>  trim($user->username), // Subject of the token
      'iat' => time(), // Time when JWT was issued.
      'exp' => time() + (24 * 60 * 60) // Expiration time
    ];

    // As you can see we are passing `JWT_SECRET` as the second parameter that will
    // be used to decode the token in the future.
    return JWT::encode($payload, env('JWT_SECRET'));
  }
  /**
   * Authenticate a user and return the token if the provided credentials are correct.
   *
   * @param  \App\BpjsUserAntrol   $user
   * @return mixed
   */

  function getMAcAddress()
  {
      return substr(exec('getmac'), 0, 17); 
  }

  function getIpAddress()
  {
      return $this->request->ip(); 
  }

  public function authenticate(BpjsUserAntrol $user)
  {

    $username = $this->request->header('x-username');
    $password = $this->request->header('x-password');

    // $this->validate($this->request, [
    //     'Username'     => 'required',
    //     'Password'    => 'required'
    // ]);
    
    // Find the user by username
    $user = BpjsUserAntrol::where('username', $this->request->header('x-username'))->first();
   
    if (!$user) {
      return response()->json([
        "metaData"  => [
          "code" => 201,
          "message" => "Username atau Password Tidak Sesuai"
        ]
      ], 201);
    }
    // // Verify the password and generate the token
    if ($this->request->header('x-password') === $user->password) {
      return response()->json([
        "metaData"  => [
            "code" => 200,
            "message" => "Ok"
        ],
        "response"  => [
            'token' => $this->jwt($user)
        ]
      ], 200);
    }else{
        return response()->json([
            "metaData"  => [
              "code" => 201,
              "message" => "Username atau Password Tidak Sesuai"
            ]
          ], 201);
    }
  }
}
