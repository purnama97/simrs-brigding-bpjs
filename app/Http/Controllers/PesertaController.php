<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;

class PesertaController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('CONS_ID_BPJS_DEV') : env('CONS_ID_BPJS_PROD'),
            'secret_key' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('SECRET_KEY_BPJS_DEV') : env('SECRET_KEY_BPJS_PROD'),
            'base_url' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('BASE_URL_BPJS_DEV') : env('BASE_URL_BPJS_PROD'),
            'user_key' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('USER_KEY_BPJS_DEV') : env('USER_KEY_BPJS_PROD'),
            'service_name' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('SERVICE_NAME_BPJS_DEV') : env('SERVICE_NAME_BPJS_PROD'),
        ];
        

        return $vclaim_conf;
    }

    //===================================PESERTA=======================================================

    public function getByNoKartu($noKartu, $tglPelayananSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Peserta($vclaim_conf);
            $data = $referensi->getByNoKartu($noKartu, $tglPelayananSEP);
            if($data["metaData"]["code"] === "200") {
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"]
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

    public function getByNIK($nik, $tglPelayananSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Peserta($vclaim_conf);
            $data = $referensi->getByNIK($nik, $tglPelayananSEP);
            if($data["metaData"]["code"] === "200") {
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"]
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