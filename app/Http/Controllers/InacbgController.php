<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;

class InacbgController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {

        $inacbg_conf = [
            'key' => env('INACBGS_KEY'),
            'base_url' => env('INACBGS_URL'),
            'user_key' => env('USER_KEY_BPJS'),
            'service_name' => env('SERVICE_NAME_BPJS'),
        ];
        

        return $inacbg_conf;
    }

    public function createKlaim(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->createKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function updatePasien(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->updatePasien($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function deletePasien($noRM)
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf);
            $payload = [
                    "metadata" => [ 
                        "method" => "delete_patient" 
                    ], 
                    "data" => [
                        "nomor_rm" => $noRM,
                        "coder_nik" => "37234567890121"
                    ]
                ];
            
            $data = $referensi->deletePasien($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function updateKlaim(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->updateKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function grouper(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->grouper($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => [
                                        'response' => $data["response"],
                                        'response_inagrouper' => $data["response_inagrouper"],
                                        // 'special_cmg_option' => $data["special_cmg_option"],
                                        'tarif_alt' => $data["tarif_alt"]
                                    ],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function finalKlaim(Request $request)
    {
        //use your own bpjs config
        $req = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            
            $payload = [
                "metadata" => [ 
                    "method" => $req["metadata"]["method"]
                ], 
                "data" => [
                    "nomor_sep" => $req["data"]["nomor_sep"],
                    "coder_nik" => "37234567890121"
                ]
            ];

            $data = $referensi->finalKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function reupdateKlaim(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->reupdateKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function sendKlaim(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->sendKlaimAll($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function sendKlaimAll(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->sendKlaimAll($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function listKlaim($tglAwal = "", $tglAkhir = "", $jnsRawat = "")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "pull_claim" 
                ], 
                "data" => [ 
                    "start_dt" => $tglAwal, 
                    "stop_dt" => $tglAkhir, 
                    "jenis_rawat"  => $jnsRawat 
                ] 
            ];

            $data = $referensi->listKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function detailKlaim($noSEP = "")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "get_claim_data" 
                ], 
                "data" => [ 
                    "nomor_sep" => $noSEP, 
                ] 
            ];

            $data = $referensi->detailKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function statusKlaim($noSEP = "")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "get_claim_status" 
                ], 
                "data" => [ 
                    "nomor_sep" => $noSEP, 
                ] 
            ];

            $data = $referensi->statusKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function deleteKlaim($noSEP)
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf);
            $payload = [
                    "metadata" => [ 
                        "method" => "delete_claim" 
                    ], 
                    "data" => [
                        "nomor_sep" => $noSEP,
                        "coder_nik" => "37234567890121"
                    ]
                ];
            
            $data = $referensi->deleteKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function printKlaim($noSEP)
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf);
            $payload = [
                    "metadata" => [ 
                        "method" => "claim_print" 
                    ], 
                    "data" => [
                        "nomor_sep" => $noSEP
                    ]
                ];
            
            $data = $referensi->printKlaim($payload);

            if($data["metadata"]["code"] === 200) {   
                $pdf = base64_decode($data["data"]);
                header("Content-type:application/pdf"); 
                header("Content-Disposition:attachment;filename='klaim.pdf'"); 
                echo $pdf;
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'data'        => $data["data"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function searchDiagnosa($keyword = "")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "search_diagnosis" 
                ], 
                "data" => [ 
                    "keyword" => $keyword, 
                ] 
            ];

            $data = $referensi->searchDiagnosa($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function searchProsedur($keyword = "")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "search_procedures" 
                ], 
                "data" => [ 
                    "keyword" => $keyword, 
                ] 
            ];

            $data = $referensi->searchProsedur($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function generateNomorPengajuan(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->generateNomorPengajuan($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function uploadFile(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->uploadFile($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function deleteFile($noSEP="", $fileId="")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "file_delete" 
                ], 
                "data" => [ 
                    "nomor_sep" => $noSEP, 
                    "file_id" => $fileId, 
                ] 
            ];

            $data = $referensi->uploadFile($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function listFile($noSEP="")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "file_get" 
                ], 
                "data" => [ 
                    "nomor_sep" => $noSEP, 
                ] 
            ];


            $data = $referensi->listFile($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function cekStatus($noSEP="", $noPengajuan="")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "file_get" 
                ], 
                "data" => [ 
                    "nomor_sep" => $noSEP, 
                    "nomor_pengajuan" => $noPengajuan, 
                ] 
            ];


            $data = $referensi->cekStatus($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function searchDiagnosaInaGrouper($keyword="")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "search_diagnosis_inagrouper" 
                ], 
                "data" => [ 
                    "keyword" => $keyword, 
                ] 
            ];


            $data = $referensi->searchDiagnosaInaGrouper($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function searchProsedurInaGrouper($keyword="")
    {
        //use your own bpjs config
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 

            $payload = [ 
                "metadata" => [ 
                    "method" => "search_procedures_inagrouper" 
                ], 
                "data" => [ 
                    "keyword" => $keyword, 
                ] 
            ];


            $data = $referensi->searchProsedurInaGrouper($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function validasiSitb(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->validasiSitb($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function batalSitb(Request $request)
    {
        //use your own bpjs config
        $payload = $request->input("request");
        $inacbg_conf = $this->connection();

        try {
            $referensi = new Purnama97\Inacbgs\InaCbgs\EKlaim($inacbg_conf); 
            $data = $referensi->batalSitb($payload);

            if($data["metadata"]["code"] === 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "INACBG CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'error_File' => $e->getFile(),
                'message'     => "Gagal!."
            ], 500);
        }
    }
}
