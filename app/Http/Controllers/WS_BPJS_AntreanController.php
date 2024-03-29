<?php


namespace App\Http\Controllers;

use Purnama97;
use App\Libraries\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Antrian;

class WS_BPJS_AntreanController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {
        $vclaim_conf = [
            'cons_id' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('CONS_ID_ANTROL_BPJS_DEV') : env('CONS_ID_BPJS_PROD'),
            'secret_key' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('SECRET_KEY_ANTROL_BPJS_DEV') : env('SECRET_KEY_BPJS_PROD'),
            'base_url' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('BASE_URL_ANTROL_BPJS_DEV') : env('BASE_URL_ANTROL_BPJS_PROD'),
            'user_key' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('USER_KEY_ANTROL_BPJS_DEV') : env('USER_KEY_ANTROL_BPJS_PROD'),
            'service_name' => env('APP_ENV_SERVICE') == 'DEVELOPMENT' ? env('SERVICE_NAME_ANTROL_DEV') : env('SERVICE_NAME_ANTROL_PROD'),
        ];

        return $vclaim_conf;
    }

    public function convert_date_to_mil($date){
        $date = strtotime($date) * 1000;
        return $date;
    }

    public function getPoli()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getPoli();
            if($data["metaData"]["code"] == 1) {   
                return response()->json([
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => $data["metaData"],
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

    public function getPoliFinger()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getPoliFinger();
            if($data["metadata"]["code"] == 1) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => $data["metadata"],
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

    public function getPasienFinger($identitas, $noIdentitas)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getPasienFinger($identitas, $noIdentitas);
            if($data["metaData"]["code"] == 1) {   
                return response()->json([
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => $data["metaData"],
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

    public function getDokter()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getDokter();
            if($data["response"] !== NULL) {   
                return response()->json([
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function getJadwalDokter($kodePoli, $tglPelayanan)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();
        $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
        $data = $referensi->getJadwalDokter($kodePoli, $tglPelayanan);
        try {
             if($data["response"] !== NULL) {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metaData"],
                    'data'        => [],
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function updateJadwalDokter($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->updateJadwalDokter($data);
            if($data["metadata"]["code"] == 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
                'error_Line' => $e->getLine(),
                'message'     => "Gagal!."
            ], 500);
        }
    }

    public function addAntrian($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->addAntrian($data);
    
            if($data["metadata"]["code"] == 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    // 'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'data'        => []
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

    public function addAntrianFarmasi($request = [])
    {
        $dateNow = Carbon::now();
        $dateTimeNow = $dateNow->toDateTimeString();
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->addAntrianFarmasi($data);
            if($data["metadata"]["code"] == 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => $data["metadata"],
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

    public function updateWaktuAntrian($request = [])
    {
        $dateNow = Carbon::now();
        $dateTimeNow = $dateNow->toDateTimeString();
        $kodebooking = $this->request->input("kodeBooking");
        $taskid = $this->request->input("taskid");
        $jenisresep = $this->request->input("jenisresep");
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        
     
        $dataAntrol1 = [
            "kodebooking" => $kodebooking,
            "taskid" => $taskid,
            "jenisresep" => $jenisresep,
            "waktu" => $this->convert_date_to_mil($dateTimeNow)
        ];

        $dataAntrol2 = [
            "kodebooking" => $kodebooking,
            "taskid" => $taskid,
            "waktu" => $this->convert_date_to_mil($dateTimeNow)
        ];

        $payload = $taskid == 5 ?  $dataAntrol1 : $dataAntrol2;

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->updateWaktuAntrian($payload);
            if($data["metadata"]["code"] == 200) { 
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metadata"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metadata"],
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

    public function batalAntrian($request = [])
    {
        $datas = $this->request->input($request);
        $dateNow = Carbon::now();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->batalAntrian($datas);
            if($data["metadata"]["code"] == 200) {  
                Antrian::where("kodeBooking", $datas["kodebooking"])
                    ->update([
                        "isCancel" => 1,
                        "waktuCancel" =>  $this->convert_date_to_mil($dateNow->toDateTimeString()),
                        "note" => $datas['keterangan']
                    ]);

                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data['metadata'],
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metadata"],
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

    public function waktuTasks($kodeBooking)
    {
        $datas = [
            "kodebooking" => $kodeBooking
        ];
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->waktuTasks($datas);
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
                    'data'        => $datas,
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
   
    public function getDashboardTgl($date, $time)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getDashboardTgl($date, $time);
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

    public function getDashboardBln($month, $year, $time)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();
        $data = $referensi->bedGet($month, $month, $month);
        try {
            $referensi = new Purnama97\Bpjs\Aplicare\Antrean($vclaim_conf);
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

    public function getAntreanTgl($tanggal)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getAntreanTgl($tanggal);
            if($data["metadata"]["code"] == 1) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => $data["metadata"],
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

    public function getAntreanKdBooking($kodebooking)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getAntreanKdBooking($kodebooking);
            if($data["metaData"]["code"] == 200) {   
                return response()->json([
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
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

    public function getAntreanBlmDilayani()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getAntreanBlmDilayani();
            if($data["metadata"]["code"] == 200) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => $data["metadata"],
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

    public function getAntreanBlmDilayaniDokter($kodepoli, $kodedokter, $hari, $jampraktek)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Antrol\Antrean($vclaim_conf);
            $data = $referensi->getAntreanBlmDilayaniDokter($kodepoli, $kodedokter, $hari, $jampraktek);
            if($data["metadata"]["code"] == 1) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => $data["metadata"],
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
