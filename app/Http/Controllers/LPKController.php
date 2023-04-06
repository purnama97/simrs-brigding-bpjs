<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;

class LPKController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('APP_ENV') === 'DEVELOPMENT' ? env('CONS_ID_BPJS_DEV') : env('CONS_ID_BPJS'),
            'secret_key' => env('APP_ENV') === 'DEVELOPMENT' ? env('SECRET_KEY_BPJS_DEV') : env('SECRET_KEY_BPJS'),
            'base_url' => env('APP_ENV') === 'DEVELOPMENT' ? env('BASE_URL_BPJS_DEV') : env('BASE_URL_BPJS'),
            'user_key' => env('APP_ENV') === 'DEVELOPMENT' ? env('USER_KEY_BPJS_DEV') : env('USER_KEY_BPJS'),
            'service_name' => env('APP_ENV') === 'DEVELOPMENT' ? env('SERVICE_NAME_BPJS_DEV') : env('SERVICE_NAME_BPJS'),
        ];
        

        return $vclaim_conf;
    }

    public function getDokterLPK($namaDokter)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            $data = $referensi->getDokterLPK($namaDokter);
            if($data["metaData"]["code"] === "200") {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function insertLPK($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            $data = $referensi->insertLPK($data);
            if($data["metaData"]["code"] === "200") {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function updateLPK($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            $data =  $referensi->updateLPK($data);
            if($data["metaData"]["code"] === "200") {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function deleteLPK($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            $data = $referensi->deleteLPK($data);
            if($data["metaData"]["code"] === "200") {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function cariLPK($tglMasuk, $jnsPelayanan)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            $data = $referensi->cariLPK($tglMasuk, $jnsPelayanan);
            if($data["metaData"]["code"] === "200") {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
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
