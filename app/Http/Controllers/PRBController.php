<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;

class PRBController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('CONS_ID_BPJS'),
            'secret_key' => env('SECRET_KEY_BPJS'),
            'base_url' => env('BASE_URL_BPJS'),
            'user_key' => env('USER_KEY_BPJS'),
            'service_name' => env('SERVICE_NAME_BPJS'),
        ];
        

        return $vclaim_conf;
    }

    public function insertPRB($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\PRB($vclaim_conf);
            $data = $referensi->insertPRB($data);
            if($data["response"] !== NULL) {   
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

    public function updatePRB($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\PRB($vclaim_conf);
            $data =  $referensi->updatePRB($data);
            if($data["response"] !== NULL) {   
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

    public function deletePRB($noSrb, $noSep)
    {
        $data =  $data = [
            "request" => [
                "t_prb" => [  
                    "noSrb" => $noSrb,
                    "noSep" => $noSep,
                    "user" => $this->name
                ]
            ]
        ];

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\PRB($vclaim_conf);
            $data = $referensi->deletePRB($data);
            if($data["response"] !== NULL) {   
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

    public function cariNomorSRB($noSRB, $noSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\PRB($vclaim_conf);
            $data = $referensi->cariNomorSRB($noSRB, $noSEP);
            if($data["response"] !== NULL) {   
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

    public function cariTanggalSRB($tglAwal, $tglAkhir)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\PRB($vclaim_conf);
            $data = $referensi->cariTanggalSRB($tglAwal, $tglAkhir);
            if($data["response"] !== NULL) {   
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
