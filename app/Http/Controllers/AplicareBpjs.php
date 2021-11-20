<?php


namespace App\Http\Controllers;

use Purnama97;
use App\Libraries\Helpers;

class BridgingBpjs extends Controller
{
    public function connection()
    {
        //use your own bpjs config
        // $data = DB::table("rs_bpjs_config")->get();
        $vclaim_conf = [
            'cons_id' => env('CONS_ID_BPJS'),
            'secret_key' => env('SECRET_KEY_BPJS'),
            'base_url' => env('BASE_URL_BPJS'),
            'user_key' => env('USER_KEY_BPJS'),
            'service_name' => env('SERVICE_NAME_BPJS'),
        ];

        // $vclaim_conf = [
        //     'cons_id' => $data[0]->cons_id,
        //     'secret_key' => $data[0]->secret_key,
        //     'base_url' => $data[0]->base_url,
        //     'service_name' => $data[0]->service_name
        // ];

        return $vclaim_conf;
    }

    public function refKelas()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->refKelas(),
                'message'     => "BPJS CONNECTED!"
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function bedGet($kodePpk, $start, $limit)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->bedGet($kodePpk, $start, $limit),
                'message'     => "BPJS CONNECTED!"
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function bedCreate($kodePpk, $request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->bedCreate($kodePpk, $data),
                'message'     => "BPJS CONNECTED!"
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function bedUpdate($kodePpk, $request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->bedUpdate($kodePpk, $data),
                'message'     => "BPJS CONNECTED!"
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function bedDelete($kodePpk, $request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->bedDelete($kodePpk, $data),
                'message'     => "BPJS CONNECTED!"
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }
}
