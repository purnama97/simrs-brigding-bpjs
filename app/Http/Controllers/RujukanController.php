<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;

class RujukanController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('CONS_ID_BPJS_DEV') : env('CONS_ID_BPJS'),
            'secret_key' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('SECRET_KEY_BPJS_DEV') : env('SECRET_KEY_BPJS'),
            'base_url' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('BASE_URL_BPJS_DEV') : env('BASE_URL_BPJS'),
            'user_key' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('USER_KEY_BPJS_DEV') : env('USER_KEY_BPJS'),
            'service_name' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('SERVICE_NAME_BPJS_DEV') : env('SERVICE_NAME_BPJS'),
        ];
        

        return $vclaim_conf;
    }

    public function insertRujukan($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->insertRujukan($data);
            if($data["metaData"]["code"] == "200") {   
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

    public function updateRujukan($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data =  $referensi->updateRujukan($data);
            if($data["metaData"]["code"] == "200") {   
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

    public function deleteRujukan($noRujukan)
    {
        
        $name = $this->request->auth['Credentials']->name;
        $data = [
            "request" => [
                "t_rujukan" => [
                    "noRujukan" => $noRujukan,
                    "user" =>  $name
                ]
            ]
        ];
        
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->deleteRujukan($data);
            if($data["metaData"]["code"] == "200") {  
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

    public function insertRujukanKhusus($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->insertRujukanKhusus($data);
            if($data["metaData"]["code"] == "200") {   
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

    public function updateRujukanKhusus($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data =  $referensi->updateRujukanKhusus($data);
            if($data["metaData"]["code"] == "200") {   
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

    public function deleteRujukanKhusus($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->deleteRujukanKhusus($data);
            if($data["metaData"]["code"] == "200") {   
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

    public function spesialistikRujukan($kodePPK, $tglRujuk)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->spesialistikRujukan($kodePPK, $tglRujuk);

            if($data["metaData"]["code"] == "200") {   
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

    public function saranaRujukan($kodePPK)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->saranaRujukan($kodePPK);

            if($data["metaData"]["code"] == "200") {   
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

    public function cariByNoRujukan($searchBy = null, $noRujukan)
    {
        if (empty($searchBy)) {
            $searchBy = ' ';
        } else {
            $searchBy = $searchBy;
        }
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->cariByNoRujukan($searchBy, $noRujukan);

            if($data["metaData"]["code"] == "200") {  
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

    public function cariByNoRujukanRS($searchBy = null, $noRujukan)
    {
        if (empty($searchBy)) {
            $searchBy = ' ';
        } else {
            $searchBy = $searchBy;
        }
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->cariByNoRujukanRS('RS', $noRujukan);
            
            if($data["metaData"]["code"] == "200") {  
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

    public function cariByNoKartu($searchBy = null, $noKartu)
    {
        if (empty($searchBy)) {
            $searchBy = ' ';
        } else {
            $searchBy = $searchBy;
        }
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->cariByNoKartu($searchBy, $noKartu, false);
            if($data["metaData"]["code"] == "200") {   
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

    public function cariByListNoKartu($searchBy = null, $noKartu)
    {
        if (empty($searchBy)) {
            $searchBy = ' ';
        } else {
            $searchBy = $searchBy;
        }
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->cariByNoKartuMulti($searchBy, $noKartu);
            if($data["metaData"]["code"] == "200") {   
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


    public function cariByTglRujukan($searchBy = null, $keyword)
    {
        if (empty($searchBy)) {
            $searchBy = ' ';
        } else {
            $searchBy = $searchBy;
        }
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            if($data["metaData"]["code"] == "200") {
                return response()->json([
                    'acknowledge' => 1,
                    'data'        => $referensi->cariByTglRujukan($searchBy, $keyword),
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

    public function cariRujukanKhusus($bulan, $tahun)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->cariRujukanKhusus($bulan, $tahun);

            if($data["metaData"]["code"] == "200") {  
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

    public function getRujukKeluar($tglMulai, $tglAkhir){
         //use your own bpjs config
         $vclaim_conf = $this->connection();

         try {
             $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
             $data = $referensi->getRujukKeluar($tglMulai, $tglAkhir);
 
             if($data["metaData"]["code"] == "200") {   
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

    public function cariRujukKeluar($noRujukan){
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->cariRujukKeluar($noRujukan);

            if($data["metaData"]["code"] == "200") {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => " sdmlsdfksd",
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

   public function jumlahSEP($jenis = "", $norujukan = ""){
    //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            $data = $referensi->jumlahSEP($jenis, $norujukan);

            if($data["metaData"]["code"] == "200") { 
                $kontrol=[];  
                if($data["response"]["jumlahSEP"] != 0){
                    $noKartu = DB::table('rs_bridging_sep')->where('noRujukan', $norujukan)->where('statusAktif', 1)->orderBy('noSep', 'DESC')->value("noKartu");
                    $kontrol = DB::table('rs_bridging_surat_kontrol')->where('noKartu', $noKartu)->where('statusAktif', 1)->orderBy('noSuratKontrol', 'DESC')->first();
                };

                if(is_null($kontrol)) {
                    $kontrol = [];
                }

                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'kontrol'     => $kontrol,
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
