<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;

class EKlaimController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {

        $vclaim_conf = [
            'key' => '736b0335d7a7dd53e514ef869b6e16953119fc28d0329fc136df8e2698f1a3ae',
            'base_url' => 'http://localhost/E-Klaim/ws.php',
            'user_key' => env('USER_KEY_BPJS'),
            'service_name' => env('SERVICE_NAME_BPJS'),
        ];
        

        return $vclaim_conf;
    }

    public function testEnkrip(Request $request)
    {
        //use your own bpjs config
        $data = $request->input('request');
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($vclaim_conf);
            $data = $referensi->testEnkrip($data);
            // if($data["metaData"]["code"] === "200") {   
            //     return response()->json([
            //         'acknowledge' => 1,
            //         'metaData'    => $data["metaData"],
            //         'data'        => $data["response"],
            //         'message'     => "BPJS CONNECTED!"
            //     ], 200);
            // }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metadata"],
                    'data'        => $data["data"],
                ], 200);
            // }
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function testDekrip(Request $request)
    {
        //use your own bpjs config
        $data = $request->input('data');
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($vclaim_conf);
            $data = $referensi->testDekrip($data);
            // if($data["metaData"]["code"] === "200") {   
            //     return response()->json([
            //         'acknowledge' => 1,
            //         'metaData'    => $data["metaData"],
            //         'data'        => $data["response"],
            //         'message'     => "BPJS CONNECTED!"
            //     ], 200);
            // }else{
            //     return response()->json([
            //         'acknowledge' => 0,
            //         'metaData'    => $data["metaData"],
            //         'data'        => $data,
            //     ], 200);
            // }
        } catch (\Throwable $e) {
            return response()->json([
                'acknowledge' => 0,
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function dataKlaim($tglPulang, $jnsPelayanan, $statusKlaim)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            $data = $referensi->dataKlaim($tglPulang, $jnsPelayanan, $statusKlaim);
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

    public function historyPelayanan($noKartu, $tglAwal, $tglAkhir)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            $data = $referensi->historyPelayanan($noKartu, $tglAwal, $tglAkhir);
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

    public function dataKlaimJasaRaharja($jnsPelayanan, $tglMulai, $tglAkhir)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            $data = $referensi->dataKlaimJasaRaharja($jnsPelayanan, $tglMulai, $tglAkhir);
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
