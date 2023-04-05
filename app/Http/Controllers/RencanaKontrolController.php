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

class RencanaKontrolController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->sub = $this->request->auth['Credentials']->sub;
        $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('APP_ENV') === 'DEVELOPMENT' ? env('CONS_ID_BPJS_DEV') : env('CONS_ID_BPJS'),
            'secret_key' => env('APP_ENV') === 'DEVELOPMENT' ? env('SECRET_KEY_BPJS_DEV') : env('SECRET_KEY_BPJS'),
            'base_url' => env('APP_ENV') === 'DEVELOPMENT' ? env('BASE_URL_APLICARE_DEV') : env('BASE_URL_APLICARE'),
            'user_key' => env('APP_ENV') === 'DEVELOPMENT' ? env('USER_KEY_BPJS_DEV') : env('USER_KEY_BPJS'),
            'service_name' => env('APP_ENV') === 'DEVELOPMENT' ? env('SERVICE_NAME_BPJS_DEV') : env('SERVICE_NAME_BPJS'),
        ];

        return $vclaim_conf;
    }

    public function insertRencanaKontrol($request = [])
    {
        $input = $this->request->input($request);
        $dateTimeNow = Carbon::now()->toDateTimeString();
        $dateNow = Carbon::now()->toDateString();

        $dataKontrol = [
            "request" => [
                "noSEP" => $input["request"]["noSEP"],
                "kodeDokter" => $input["request"]["kodeDokter"],
                "namaDokter" => $input["request"]["namaDokter"],
                "poliKontrol" => $input["request"]["poliKontrol"],
                "poliKontrolNama" => $input["request"]["poliKontrolNama"],
                "tglRencanaKontrol" => $input["request"]["tglRencanaKontrol"],
                "user" => $this->name,
            ],
        ];

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data = $referensi->insertRencanaKontrol($input);
            if($data["metaData"]["code"] === "200") { 
                RencanaKontrolBPJS::insert([
                    "noSuratKontrol" => $data["response"]["noSuratKontrol"],
                    "tglSurat" => $dateNow,
                    "noSep" => $input["request"]["noSEP"],
                    "tglRencanaKontrol" => $data["response"]["tglRencanaKontrol"],
                    "kdDokter" => $input["request"]["kodeDokter"],
                    "nmDokter" => $input["request"]["namaDokter"],
                    "kdPoli" => $input["request"]["poliKontrol"],
                    "nmPoli" => $input["request"]["poliKontrolNama"],
                    "namaPasien" => $input["request"]["namaPasien"],
                    "tglLahir" => $input["request"]["tglLahir"],
                    "noKartu" => $input["request"]["noKartu"],
                    "jnsKontrol" => 2,
                    "user" => $this->sub,
                    "statusAktif" => 1,
                    "createdAt" =>  $dateTimeNow,
                    "updatedAt" => $dateTimeNow    
                ]);

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
        $input = $this->request->input($request);
        $dateTimeNow = Carbon::now()->toDateTimeString();
        $dateNow = Carbon::now()->toDateTimeString();

        $dataKontrol = [
            "request" => [
                "noSuratKontrol" => $input["request"]["noSuratKontrol"],
                "noSEP" => $input["request"]["noSEP"],
                "kodeDokter" => $input["request"]["kodeDokter"],
                "poliKontrol" => $input["request"]["poliKontrol"],
                "tglRencanaKontrol" => $input["request"]["tglRencanaKontrol"],
                "user" => $this->name
            ]
        ];

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->updateRencanaKontrol($dataKontrol);
            if($data["metaData"]["code"] === "200") {   
                RencanaKontrolBPJS::where("noSuratKontrol", $data["response"]["noSuratKontrol"])
                    ->update([
                        "tglRencanaKontrol" => $data["response"]["tglRencanaKontrol"],
                        "kdDokter" => $input["request"]["kodeDokter"],
                        "nmDokter" => $input["request"]["namaDokter"],
                        "kdPoli" => $input["request"]["poliKontrol"],
                        "nmPoli" => $input["request"]["poliKontrolNama"],
                        // "kdDiagnosa" => $data["response"][""],
                        // "nmDiagnosa" => $data["response"][""],
                        "jnsKontrol" => 1,
                        "user" => $this->sub,
                        "statusAktif" => 1,
                        "updatedAt" => $dateTimeNow    
                    ]);

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
                    "noSuratKontrol" => $noSurat,
                    "user" =>  $this->name
                ]
            ]
        ];

        $dateTimeNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data = $referensi->deleteRencanaKontrol($data);
            if($data["metaData"]["message"] === "Sukses") {   
                try {
                    RencanaKontrolBPJS::where("noSuratKontrol", $noSurat)
                        ->update([
                            "user" => $this->sub,
                            "statusAktif" => 0,
                            "updatedAt" => $dateTimeNow    
                        ]);

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
        $input = $this->request->input($request);
        $dateTimeNow = Carbon::now()->toDateTimeString();
        $dateNow = Carbon::now()->toDateString();

        $dataKontrol = [
            "request" => [
                "noKartu" => $input["request"]["noKartu"],
                "kodeDokter" => $input["request"]["kodeDokter"],
                "namaDokter" => $input["request"]["namaDokter"],
                "poliKontrol" => $input["request"]["poliKontrol"],
                "tglRencanaKontrol" => $input["request"]["tglRencanaKontrol"],
                "user" => $this->name,
            ],
        ];

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data = $referensi->insertSPRI($dataKontrol);
            if($data["metaData"]["code"] === "200") { 
                try {
                    RencanaKontrolBPJS::insert([
                        "noSuratKontrol" => $data["response"]["noSPRI"],
                        "tglSurat" => $dateNow,
                        "noSep" => $input["request"]["noSEP"],
                        "tglRencanaKontrol" => $data["response"]["tglRencanaKontrol"],
                        "kdDokter" => $input["request"]["kodeDokter"],
                        "nmDokter" => $input["request"]["namaDokter"],
                        "kdPoli" => $input["request"]["poliKontrol"],
                        "nmPoli" => $input["request"]["poliKontrolNama"],
                        "namaPasien" => $input["request"]["namaPasien"],
                        "tglLahir" => $input["request"]["tglLahir"],
                        "noKartu" => $input["request"]["noKartu"],
                        "jnsKontrol" => 1,
                        "user" => $this->sub,
                        "statusAktif" => 1,
                        "createdAt" =>  $dateTimeNow,
                        "updatedAt" => $dateTimeNow    
                    ]);
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

    public function updateSPRI($request = [])
    {
        $input = $this->request->input($request);
        $dateTimeNow = Carbon::now()->toDateTimeString();

        $dataKontrol = [
            "request" => [
                "noSPRI" => $input["request"]["noSPRI"],
                "kodeDokter" => $input["request"]["kodeDokter"],
                "poliKontrol" => $input["request"]["poliKontrol"],
                "tglRencanaKontrol" => $input["request"]["tglRencanaKontrol"],
                "user" => $this->name
            ]
        ];

        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->updateSPRI($dataKontrol);
            if($data["metaData"]["code"] === "200") {   
                try {
                   
                    RencanaKontrolBPJS::where("noSuratKontrol", $data["response"]["noSPRI"])
                        ->update([
                            "tglRencanaKontrol" => $data["response"]["tglRencanaKontrol"],
                            "kdDokter" => $input["request"]["kodeDokter"],
                            "nmDokter" => $input["request"]["namaDokter"],
                            "kdPoli" => $input["request"]["poliKontrol"],
                            "nmPoli" => $input["request"]["poliKontrolNama"],
                            // "kdDiagnosa" => $data["response"][""],
                            // "nmDiagnosa" => $data["response"][""],
                            "jnsKontrol" => 1,
                            "user" => $this->sub,
                            "statusAktif" => 1,
                            "updatedAt" => $dateTimeNow    
                        ]);

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

    public function dokterKontrol($jnsKontrol, $kdPoli, $tglRencanaKontrol)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->dokterKontrol($jnsKontrol, $kdPoli, $tglRencanaKontrol);
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

    public function poliSpesialistik($jnsKontrol, $nomor, $tglKontrol)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->poliSpesialistik($jnsKontrol, $nomor, $tglKontrol);
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

    public function cariSEP($noSEP)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->cariSEP($noSEP);
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

    public function cariNoSuratKontrol($noSurat)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\RencanaKontrol($vclaim_conf);
            $data =  $referensi->cariNoSuratKontrol($noSurat);
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

    public function detailKontrol($noSurat)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $cek = RencanaKontrolBPJS::where("noSuratKontrol", $noSurat)->exists();
            $data = RencanaKontrolBPJS::from('rs_bridging_surat_kontrol as a')
            ->select(
                'a.noSuratKontrol',
                'a.noKartu',
                'a.namaPasien',
                'b.nmDpjp',
                'a.tglLahir',
                'a.tglRencanaKontrol',
                'b.diagAwal',
                'b.nmDiagnosa',
                'a.nmDokter',
                'a.nmPoli'
            )
            ->leftJoin('rs_bridging_sep as b', 'a.noSep', '=', 'b.noSep')
            ->where("noSuratKontrol", $noSurat)
            ->first();

            if($cek) {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => [
                                        "code"=> 200,
                                        "message" => "Sukses"
                                    ],
                    'data'        => $data,
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => [
                                        "code"=> 201,
                                        "message" => "Data tidak ditemukan!"
                                    ],
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
