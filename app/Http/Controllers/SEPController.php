<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\ConfigBpjs;
use App\SepBPJS;

class SEPController extends Controller
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

    //===================================SEP=======================================================
    public function listSEP(Request $request)
    {
        //use your own bpjs config
        $first_period = $request->input('first_period');
        $last_period = $request->input('last_period'); 
        $keyword = $request->input('keyword'); 

        try {
            $data = SepBPJS::from("tSep as a")
                        ->select('*')
                        ->orderBy('a.createdAt', 'DESC')
                        ->where(function ($query) use ($first_period, $last_period, $keyword) {
                            $query->whereBetween("a.tglsep", [$first_period, $last_period]);
                            if (!empty($keyword)) {
                                $query->where("a.nomr", 'like', "%{$keyword}%")
                                    ->orWhere("a.namaPasien", 'like', "%{$keyword}%")
                                    ->orWhere("a.noSep", 'like', "%{$keyword}%")
                                    ->orWhere("a.noKartu", 'like', "%{$keyword}%");
                            }
                        })
                        ->paginate();

            if(!empty($data)) {
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => array("code" => 200, "message" => "Sukes"),
                    'data'        => $data
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => array("code" => 201, "message" => "Data tidak tersedia"),
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

    public function cariSEP($noSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->cariSEP($noSEP);

            if($data["response"] !== NULL) {
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

    public function insertSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
          
        $inputUser = $this->request->input("request");
        $dateNow = Carbon::now()->toDateTimeString();
        $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
        $data = $referensi->insertSEP($data);
        
        if($data["response"] !== NULL) {
            try {    
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'acknowledge' => 0,
                    'error_message' => $e->getMessage(),
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }
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

    public function updateSEP($request = [])
    {
        $data = $this->request->input($request);
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
             
        try {
            $vclaim_conf = $this->connection();
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $datas = $referensi->updateSEP($data);

            if($datas["response"] !== NULL) {
                try {
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $datas["metaData"],
                        'data'        => $datas["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'acknowledge' => 0,
                        'error_message' => $e->getMessage(),
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                }
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $datas["metaData"],
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

    public function deleteSEP($noSEP)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = [
                "request" => [
                    "t_sep" => [
                        "noSep" => $noSEP,
                        "user" => $this->name
                    ]
                ]
            ];

            $datas = $referensi->deleteSEP($data);
            if($datas["metaData"]["code"] === "200") {

                try {     
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $datas["metaData"],
                        'data'        => $datas["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'acknowledge' => 0,
                        'error_message' => $e->getMessage(),
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                }
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $datas["metaData"],
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

    public function pengajuanSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->pengajuanPenjaminanSep($data);
            if($data["response"] !== NULL) {
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

    public function approvalPenjaminanSep($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->approvalPenjaminanSep($data);
            if($data["response"] !== NULL) {
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

    //pas dicoba masih error
    public function updateTglPlg($request = [])
    {
        $data = $this->request->input($request);
        $inputUser = $this->request->input("request");
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->updateTglPlg($data);
            $dataSEP = [];
            array_push($dataSEP, [
                "tglpulang" => $inputUser["t_sep"]["tglPulang"]
            ]);

            if($data["response"] !== NULL) {   
                try {
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $data["metaData"],
                        'data'        => $data["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'acknowledge' => 0,
                        'data'        => $dataSEP,
                        'error_message' => $e->getMessage(),
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                }
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

    public function listUpdateTglPlg($tahun, $bulan, $filter)
    {
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
             
        try {
            $vclaim_conf = $this->connection();
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $datas = $referensi->listUpdateTglPlg($bulan, $tahun, $filter);

            if($datas["response"] !== NULL) {
                try {
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $datas["metaData"],
                        'data'        => $datas["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'acknowledge' => 0,
                        'error_message' => $e->getMessage(),
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                }
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $datas["metaData"],
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

    public function suplesiJasaRaharja($noKartu, $tglPelayananSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->suplesiJasaRaharja($noKartu, $tglPelayananSEP);
            if($data["response"] !== NULL) {
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

    public function dataIndukKLL($noKartu)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->dataIndukKll($noKartu);
            if($data["response"] !== NULL) {
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

    public function inacbgSEP($noSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->inacbgSEP($noSEP);
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

    public function cariSEPInternal($noSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->getSEPInternal($noSEP);

            if($data["response"] !== NULL) {
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

    public function deleteSepInternal($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
        $data = $referensi->deleteSepInternal($data);

        try {
            if($data["response"] !== NULL) {
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
    
    
    //===================================SEP =======================================================
}
