<?php


namespace App\Http\Controllers;

use Purnama97;
use App\Libraries\Helpers;

class ReferensiBpjsController extends Controller
{
    public function connection()
    {
        //use your own bpjs config
        // $data = DB::table("rs_bpjs_config")->get();
        $vclaim_conf = [
            'cons_id' => env('CONS_ID_BPJS'),
            'secret_key' => env('SECRET_KEY_BPJS'),
            'base_url' => env('BASE_URL_BPJS'),
            'user_key' => env('USER_KEY_BPJS'),
            'service_name' => env('SERVICE_NAME_BPJS'),
        ];

        // $vclaim_conf = [
        //     'cons_id' => $data[0]->cons_id,
        //     'secret_key' => $data[0]->secret_key,
        //     'base_url' => $data[0]->base_url,
        //     'service_name' => $data[0]->service_name
        // ];

        return $vclaim_conf;
    }

    public function diagnosa($diagnosa)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->diagnosa($diagnosa),
                'message'     => "BPJS CONNECTED!"
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

    public function poli($poli)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->poli($poli),
                'message'     => "BPJS CONNECTED!"
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

    public function faskes($kode_faskes, $jenis_faskes)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->faskes($kode_faskes, $jenis_faskes),
                'message'     => "BPJS CONNECTED!"
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

    public function dokterDpjp($jenis_pelayanan, $tgl_pelayanan, $kode_spesialis)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->dokterDpjp($jenis_pelayanan, $tgl_pelayanan, $kode_spesialis),
                'message'     => "BPJS CONNECTED!"
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

    public function propinsi()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->propinsi(),
                'message'     => "BPJS CONNECTED!"
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

    public function kabupaten($kabupaten)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->kabupaten($kabupaten),
                'message'     => "BPJS CONNECTED!"
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

    public function kecamatan($kecamatan)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->kecamatan($kecamatan),
                'message'     => "BPJS CONNECTED!"
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

    public function diagnosaPRB()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->diagnosaPRB(),
                'message'     => "BPJS CONNECTED!"
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

    public function obatPRB($obat)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->obatPRB($obat),
                'message'     => "BPJS CONNECTED!"
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

    public function prosedur($prosedur)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->procedure($prosedur),
                'message'     => "BPJS CONNECTED!"
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

    public function kelasRawat()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->kelasRawat(),
                'message'     => "BPJS CONNECTED!"
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

    public function dokter($dokter)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->dokter($dokter),
                'message'     => "BPJS CONNECTED!"
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

    public function spesialistik()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->spesialistik(),
                'message'     => "BPJS CONNECTED!"
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

    public function ruangrawat()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->ruangrawat(),
                'message'     => "BPJS CONNECTED!"
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

    public function carakeluar()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->carakeluar(),
                'message'     => "BPJS CONNECTED!"
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

    public function pascapulang()
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Referensi($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->pascapulang(),
                'message'     => "BPJS CONNECTED!"
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

