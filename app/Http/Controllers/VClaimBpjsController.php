<?php


namespace App\Http\Controllers;

use Purnama97;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Helpers;
use Carbon\Carbon;
use App\SepBPJS;

class VClaimBpjsController extends Controller
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

    //===================================PESERTA=======================================================

    public function getByNoKartu($noKartu, $tglPelayananSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Peserta($vclaim_conf);
            $data = $referensi->getByNoKartu($noKartu, $tglPelayananSEP);
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

    public function getByNIK($noKartu, $tglPelayananSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Peserta($vclaim_conf);
            $data = $referensi->getByNIK($noKartu, $tglPelayananSEP);
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

    //===================================END PESERTA=======================================================

    //===================================SEP=======================================================
    public function cariSEP($keyword)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->cariSEP($keyword),
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

    public function insertSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
          

            // $response = $r->input("sep");
        $inputUser = $this->request->input("t_sep");
        $dateNow = Carbon::now()->toDateTimeString();
        $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
        $data = $referensi->insertSEP($data);

        // var_dump($inputUser);
        // $dataSEP = [];
        // array_push($dataSEP, [
        //     "no_sep" => $data["noSep"],
        //     // 'no_rawat' => null, // $response[""],
        //     'tglsep' => $data["tglSep"],
        //     'tglrujukan' => $inputUser["rujukan"]["tglRujukan"],
        //     'no_rujukan' => $inputUser["rujukan"]["noRujukan"],
        //     'kdppkrujukan' => $inputUser["rujukan"]["ppkRujukan"],
        //     // 'nmppkrujukan' => $inputUser["rujukan"][""],
        //     'kdppkpelayanan' => $inputUser["ppkPelayanan"],
        //     // 'nmppkpelayanan' => $data[""], 
        //     'jnspelayanan' => $data["jnsPelayanan"],
        //     'catatan' => $data["catatan"],
        //     'diagawal' => $inputUser["diagAwal"],
        //     'nmdiagnosaawal' => $data["diagnosa"],
        //     'kdpolitujuan' => $inputUser["poli"]["tujuan"],
        //     'nmpolitujuan' => $data["poli"],
        //     'klsrawat' => $data["kelasRawat"],
        //     'lakalantas' => $inputUser["jaminan"]["lakaLantas"],
        //     'nomr' => $data["peserta"]["noMr"],
        //     'nama_pasien' => $data["peserta"]["nama"],
        //     'tanggal_lahir' => $data["peserta"]["tglLahir"],
        //     'peserta' => $data["peserta"]["jnsPeserta"],
        //     'jkel' => $data["peserta"]["kelamin"],
        //     'no_kartu' => $data["peserta"]["noKartu"],
        //     'tglpulang' => "",
        //     'asal_rujukan' => $inputUser["rujukan"]["asalRujukan"],
        //     'eksekutif' => $data["poliEksekutif"],
        //     'cob' => $inputUser["cob"]["cob"],
        //     'penjamin' => $data["penjamin"],
        //     'notelep' => $inputUser["noTelp"],
        //     'katarak' => $inputUser["katarak"]["katarak"],
        //     // 'tglkkl' => $data[""],
        //     // 'keterangankkl' => "",
        //     'suplesi' => $inputUser["jaminan"]["penjamin"]["suplesi"]["suplesi"],
        //     'no_sep_suplesi' => $inputUser["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
        //     'kdprop' => $inputUser["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
        //     'nmprop' => $inputUser["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
        //     'kdkab' => $inputUser["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
        //     'nmkab' => $inputUser["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
        //     'kdkec' => $inputUser["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
        //     'nmkec' => $inputUser["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
        //     'noskdp' => $inputUser["skdp"]["noSurat"],
        //     'kddpjp' => $inputUser["skdp"]["kodeDPJP"],
        //     // 'nmdpjp' => $inputUser["dpjpLayanan"],
        //     'created_at' => $dateNow,
        //     'updated_at' => $dateNow,
        //     "user" => $this->name
        // ]);

        // SepBPJS::insert($dataSEP);

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

    public function updateSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->updateSEP($data),
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

    public function deleteSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->deleteSEP($data),
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

    public function pengajuanSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->pengajuanPenjaminanSep($data),
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

    public function approvalPenjaminanSep($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->approvalPenjaminanSep($data),
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

    //pas dicoba masih error
    public function updateTglPlg($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->updateTglPlg($data),
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

    public function suplesiJasaRaharja($noKartu, $tglPelayananSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->suplesiJasaRaharja($noKartu, $tglPelayananSEP),
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

    public function inacbgSEP($noSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->inacbgSEP($noSEP),
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
    //===================================END SEP=======================================================

    //===================================RUJUKAN=======================================================
    public function insertRujukan($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->insertRujukan($data),
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

    public function updateRujukan($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->updateRujukan($data),
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

    public function deleteRujukan($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\Rujukan($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->deleteRujukan($data),
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

    public function cariByNoRujukan($searchBy = null, $keyword)
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
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->cariByNoRujukan($searchBy, $keyword),
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

    public function cariByNoKartu($searchBy = null, $keyword)
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
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->cariByNoKartu($searchBy, $keyword),
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
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->cariByTglRujukan($searchBy, $keyword),
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
    //===================================END RUJUKAN=======================================================

    //===================================LPK=======================================================
    public function insertLPK($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->insertLPK($data),
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

    public function updateLPK($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->updateLPK($data),
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

    public function deleteLPK($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->deleteLPK($data),
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

    public function cariLPK($tglMasuk, $jnsPelayanan)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\LembarPengajuanKlaim($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->cariLPK($tglMasuk, $jnsPelayanan),
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
    //===================================END LPK=======================================================

    //===================================MONITORING=======================================================
    public function dataKunjungan($tglSep, $jnsPelayanan)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->dataKunjungan($tglSep, $jnsPelayanan),
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

    public function dataKlaim($tglPulang, $jnsPelayanan, $statusKlaim)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->dataKlaim($tglPulang, $jnsPelayanan, $statusKlaim),
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

    public function historyPelayanan($noKartu, $tglAwal, $tglAkhir)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->historyPelayanan($noKartu, $tglAwal, $tglAkhir),
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

    public function dataKlaimJasaRaharja($tglMulai, $tglAkhir)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\Monitoring($vclaim_conf);
            return response()->json([
                'acknowledge' => 1,
                'data'        => $referensi->dataKlaimJasaRaharja($tglMulai, $tglAkhir),
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
    //===================================END MONITORING=======================================================

    //============================================ PRB =======================================================
    
    //========================================== END PRB =====================================================
}
