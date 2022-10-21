<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;
use App\ConfigBpjs;
use App\RencanaKontrolBPJS;
use App\SpriBPJS;

class RencanaKontrolController extends Controller
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

    public function insertRencanaKontrol($request = [])
    {
        $data = $this->request->input($request);
        $dataInput = $this->request->input($request);
        $dateNow = Carbon::now()->toDateTimeString();

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data = $referensi->insertRencanaKontrol($data);
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

    public function updateRencanaKontrol($request = [])
    {
        $data = $this->request->input($request);
        $dataInput = $this->request->input($request);
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->updateRencanaKontrol($data);
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

    public function deleteRencanaKontrol($noSurat = "")
    {
        $data =  [
            "request" => [
                "t_suratkontrol" => [
                    "noSuratKontrol" => "0301R0010320K000004",
                    "user" =>  $this->name
                ]
            ]
        ];

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data = $referensi->deleteRencanaKontrol($data);
            if($data["metaData"]["message"] === "Sukses") {   
                try {
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $data["metaData"],
                        'data'        => $data["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'acknowledge' => 0,
                        'error_message' => $e->getMessage(),
                        'error_Line' => $e->getLine(),
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

    public function insertSPRI($request = [])
    {
        $data = $this->request->input($request);
        $dataInput = $this->request->input($request);
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data = $referensi->insertSPRI($data);
            if($data["response"] !== NULL) {   
                $dataSPRI = [];
                array_push($dataSPRI, [
                    'noSPRI' => $data["response"]["noSPRI"],
                    'noSEP' => $dataInput["request"]["noSEP"],
                    'kdDokter' => $dataInput["request"]["kodeDokter"],
                    'nmDokter' => $data["response"]["namaDokter"],
                    'kdDiagnosa' => $data["response"]["namaDiagnosa"] !== NULL ? explode(" ",$data["response"]["namaDiagnosa"])[0]:NULL,
                    'nmDiagnosa' => $data["response"]["namaDiagnosa"],
                    'kdPoli' =>  $dataInput["request"]["poliKontrol"],
                    'nmPoli' => $dataInput["request"]["poliKontrolNama"],
                    "tglRencanaKontrol" => $data["response"]["tglRencanaKontrol"],
                    'statusAktif' => 1,
                    'user' => $this->name,
                    'createdAt' =>  $dateNow,
                    'updatedAt' =>  $dateNow,   
                ]);

                try {
                    DB::transaction(function () use ($dataSPRI) {
                        SpriBPJS::insert($dataSPRI);
                    });
        
                    DB::commit();
         
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $data["metaData"],
                        'data'        => $data["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    DB::rollback();
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

    public function updateSPRI($request = [])
    {
        $data = $this->request->input($request);
        $dataInput = $this->request->input($request);
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->updateSPRI($data);
            if($data["response"] !== NULL) {   
                try {
                    DB::transaction(function () use ($dataInput, $data, $dateNow) {
                        SpriBPJS::where('noSPRI', $data["response"]["noSPRI"])->update([
                            'noSPRI' => $data["response"]["noSPRI"],
                            'noSEP' => $dataInput["request"]["noSEP"],
                            'kdDokter' => $dataInput["request"]["kodeDokter"],
                            'nmDokter' => $data["response"]["namaDokter"],
                            'kdDiagnosa' => $data["response"]["namaDiagnosa"] !== NULL ? explode(" ",$data["response"]["namaDiagnosa"])[0]:NULL,
                            'nmDiagnosa' => $data["response"]["namaDiagnosa"],
                            'kdPoli' =>  $dataInput["request"]["poliKontrol"],
                            'nmPoli' => $dataInput["request"]["poliKontrolNama"],
                            "tglRencanaKontrol" => $data["response"]["tglRencanaKontrol"],
                            'statusAktif' => 1,
                            'user' => $this->name,
                            'createdAt' =>  $dateNow,
                            'updatedAt' =>  $dateNow,   
                        ]);
                    });
        
                    DB::commit();
         
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $data["metaData"],
                        'data'        => $data["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    DB::rollback();
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

    public function dokterKontrol($jnsKontrol, $kdPoli, $tglRencanaKontrol)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->dokterKontrol($jnsKontrol, $kdPoli, $tglRencanaKontrol);
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

    public function poliSpesialistik($jnsKontrol, $nomor, $tglKontrol)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->poliSpesialistik($jnsKontrol, $nomor, $tglKontrol);
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

    public function cariSEP($noSEP)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->cariSEP($noSEP);
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

    public function cariNoSuratKontrol($noSurat)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->cariNoSuratKontrol($noSurat);
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

    public function dataNoSuratKontrol($tglAwal, $tglAkhir, $filter)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->dataNoSuratKontrol($tglAwal, $tglAkhir, $filter);
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
