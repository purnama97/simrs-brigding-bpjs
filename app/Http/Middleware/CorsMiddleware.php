<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class CorsMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
      $headers = [
          'Access-Control-Allow-Origin'      => '*',
          'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
          'Access-Control-Allow-Credentials' => 'true',
          'Access-Control-Max-Age'           => '86400',
          'Access-Control-Allow-Headers'     => 'content-type,authorization,compcode,outletcode,appschema,token'
      ];

      if ($request->isMethod('OPTIONS'))
      {
          return response()->json('{"method":"OPTIONS"}', 200, $headers);
      }

      $response = $next($request);
      
      foreach($headers as $key => $value)
      {
          $response->header($key, $value);
      }


      return $response;
    // $response = $next($request);

    // $response->headers->set('Access-Control-Allow-Origin' , '*');
    // $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
    // $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');

    // return $response;
  }
}
