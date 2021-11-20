<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\AppUser;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class JwtMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $token      = trim($request->header('Authorization')) ?: 'A B';
        $key        = trim('JhbGciOiJIUzI1N0eXAiOiJKV1QiLC');
        $CompCode   = trim($request->header('CompCode'));

        if (env("DB_DATABASE_RSUD") != "RSUD_DABO") {
            Config::set("database.connections.sqlsrv", [
                "host" => "103.6.55.34",
                "database" => 'RSUD_DABO',
                "username" => 'user_rs',
                "driver" => 'sqlsrv',
                'port' => '1433',
                "password" => 'ZUa5TkQX'
            ]);
            DB::reconnect('sqlsrv');
        }

        if ($CompCode == "" || $token == "") {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'Header not provided.'
            ], 401);
        }

        try {

            $remove_bearer = explode(' ', $token);
            $credentials = JWT::decode(trim($remove_bearer[1]), $key, array('HS256'));
        } catch (ExpiredException $e) {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'Provided token is expired.',
                'isLogOut' => 1
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'An error while decoding token.',
                'data' => $e->getMessage(),
                // "Key" => env('JWT_SECRET')

            ], 400);
        }

        $user = AppUser::findOrFail(trim($credentials->sub));

        if (trim($credentials->compCode) != trim($CompCode)) {
            return response()->json([
                'acknowledge' => 0,
                'message' => 'CompCode Not Match.'
            ], 400);
        }

        // Now let's put the user in the request class so that you can grab it from there

        $request->auth = $this->getUserCredential($user, $CompCode, $credentials);

        return $next($request);
    }

    public function getUserCredential($user, $CompCode, $credentials)
    {
        $UserAuth = collect([
            'User' => $user,
            'CompCode' => $CompCode,
            'Credentials' => $credentials
        ]);
        return $UserAuth;
    }
}
