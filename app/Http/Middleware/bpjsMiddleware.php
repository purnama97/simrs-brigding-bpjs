<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\BpjsUserAntrol;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class BpjsMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token      = trim($request->header('x-token'));
        $username      = trim($request->header('x-username')) ? : 'A B';
        $key        = trim('JhbGciOiJIUzI1N0eXAiOiJKV1QiLC');


        if ($username == "" || $token == "") {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'Header not provided.',
                "data" => $token
            ], 401);
        }

        try {
            $credentials = JWT::decode($token, $key, array('HS256'));
        } catch (ExpiredException $e) {
            return response()->json([
                "metaData"  => [
                    "code" => 201,
                    "message" => "Token Expired"
                  ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'An error while decoding tokens.',
                'data' => $e->getMessage(),
                // "Key" => env('JWT_SECRET')

            ], 400);
        }

        $user = BpjsUserAntrol::findOrFail(trim($credentials->sub));

        if (trim($credentials->sub) != trim($username)) {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'User Not Match.'
            ], 400);
        }

        // Now let's put the user in the request class so that you can grab it from there

        $request->auth = $this->getUserCredential($user, $username, $credentials);

        return $next($request);
    }

    public function getUserCredential($user, $username, $credentials)
    {
        $UserAuth = collect([
            'User' => $user,
            'UserName' => $username,
            'Credentials' => $credentials
        ]);
        return $UserAuth;
    }
}
