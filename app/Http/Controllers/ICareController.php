<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;

class ICareController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->docCode = $this->request->auth['Credentials']->docCode;

    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('CONS_ID_BPJS_DEV') : env('CONS_ID_BPJS'),
            'secret_key' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('SECRET_KEY_BPJS_DEV') : env('SECRET_KEY_BPJS'),
            'base_url' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('BASE_URL_BPJS_DEV') : env('BASE_URL_BPJS'),
            'user_key' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('USER_KEY_BPJS_DEV') : env('USER_KEY_BPJS'),
            'service_name' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('SERVICE_NAME_IHS_DEV') : env('SERVICE_NAME_IHS_'),
        ];
        

        return $vclaim_conf;
    }

    public function IcareFKTL($request =[])
    {
        $data = $this->request->input($request);
        $docCode = DB::table('rs_mapping_dokter_asuransi')->where('kodeDokter',  $data["kodedokter"])->value('kodeDokterAsuransi');
        //use your own bpjs config

        $payload = [
            "param" => $data["param"],
            "kodedokter" =>  (int)$docCode
        ];

        $vclaim_conf = $this->connection();
        
        try {
            $referensi = new Purnama97\Bpjs\ICare\Icare($vclaim_conf);
            $res = $referensi->IcareFKTL($payload);
            if($res["metaData"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $res["metaData"],
                    'data'        => $res["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    =>  $res["metaData"],
                    'data'        =>  [],
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
