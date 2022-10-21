<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use App\Libraries\Helpers;
use App\Kamar;

class AplicareController extends Controller
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->name = $this->request->auth['Credentials']->name;
    }

    protected function kapasitas($kode){
		$count = Kamar::where('kode_kamar_bpjs', $kode)->whereNotNull('kode_kamar_bpjs')->count();
		return $count;
	}
	
	protected function stock($kode, $status){
		$count = Kamar::where('kode_kamar_bpjs', $kode)->where('status_kamar', $status)->whereNotNull('kode_kamar_bpjs')->count();
		return $count;
	}

    public function connection()
    {
        $vclaim_conf = [
            'cons_id' => env('CONS_ID_BPJS'),
            'secret_key' => env('SECRET_KEY_BPJS'),
            'base_url' => env('BASE_URL_APLICARE'),
            'user_key' => env('USER_KEY_BPJS'),
            'service_name' => env('SERVICE_NAME_APLICARE'),
        ];

        return $vclaim_conf;
    }

    public function refKelas()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            $data = $referensi->refKelas();
            if($data["response"] !== NULL) {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metadata"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
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

    public function bedGet($kodePpk, $start, $limit)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();
        $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
        $data = $referensi->bedGet($kodePpk, $start, $limit);
        try {
             if($data["response"] !== NULL) {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metadata"],
                    'data'        => $data["response"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
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

    public function bedCreate($kodePpk, $request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
        $data = $referensi->bedCreate($kodePpk, $data);
        
        try {
            if($data["metadata"] !== NULL) {   
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metadata"],
                    'message'     => "BPJS CONNECTED!"
                ], 200);
            }else{
                return response()->json([
                    'acknowledge' => 0,
                    'metaData'    => $data["metadata"]
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

    public function bedUpdate($kodePpk, $request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            $data = $referensi->bedUpdate($kodePpk, $data);
            if($data["metadata"] !== NULL) {   
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

    public function syncServer($kodePpk, $data)
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            $data = $referensi->bedUpdate($kodePpk, $data);
            if($data["metadata"] !== NULL) {   
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

    public function bedDelete($kodePpk, $kodeKelas, $kodeRuangan, $request = [])
    {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();

        $data = array(
            "kodekelas" => $kodeKelas, 
            "koderuang" => $kodeRuangan
        );

        try {
            $referensi = new Purnama97\Bpjs\Aplicare\KetersediaanKamar($vclaim_conf);
            $data = $referensi->bedDelete($kodePpk, $data);
            if($data["metadata"] !== NULL) {   
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

    public function synckamar(){

        try{
            $data = Kamar::select(
                    "kelas_bpjs",
                    "kode_kamar_bpjs",
                    "nama_kamar"
                )
                ->whereNotNull('kelas_bpjs')
                ->where('kelas_bpjs', '!=', "")
                ->distinct()
                ->orderBy('nama_kamar', 'ASC')
                ->get();

            foreach($data as $row) {
                $this->syncServer(env('KODE_PPK_BPJS'), [
                    "kodekelas" => $row['kelas_bpjs'], 
                    "koderuang" => $row['kode_kamar_bpjs'],
                    "namaruang" => $row['nama_kamar'], 
                    "kapasitas" => $this->kapasitas($row['kode_kamar_bpjs']), 
                    "tersedia" => $this->stock($row['kode_kamar_bpjs'], "TERSEDIA"),
                    "tersediapria" => "0", 
                    "tersediawanita" => "0", 
                    "tersediapriawanita" => "0"
                ]);
            }

            return response()->json([
                'acknowledge' => 1,
                'message' => "Sinkronisasi data berhasil!"
            ], 200);
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
