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
    public function cariSEP($noPeserta)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->cariSEP($noPeserta);

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
          

            // $response = $r->input("sep");
        $inputUser = $this->request->input("request");
        $dateNow = Carbon::now()->toDateTimeString();
        $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
        $data = $referensi->insertSEP($data);
        // $datas = json_encode($data);

        // echo $datas;


        

        // var_dump($inputUser);
        $dataSEP = [];
        array_push($dataSEP, [
            "no_sep" => $data["response"]["sep"]["noSep"],
            'no_rawat' => $inputUser["t_sep"]["noRawat"],
            'tglsep' => $data["response"]["sep"]["tglSep"],
            'tglrujukan' => $inputUser["t_sep"]["rujukan"]["tglRujukan"],
            'no_rujukan' => $inputUser["t_sep"]["rujukan"]["noRujukan"],
            'kdppkrujukan' => $inputUser["t_sep"]["rujukan"]["ppkRujukan"],
            'nmppkrujukan' => $inputUser["t_sep"]["rujukan"]["nmPpkRujukan"],
            'kdppkpelayanan' => '0438R002',
            'nmppkpelayanan' => 'RSUD DABO', 
            'jnspelayanan' => $data["response"]["sep"]["jnsPelayanan"],
            'catatan' => $data["response"]["sep"]["catatan"],
            'diagawal' => $inputUser["t_sep"]["diagAwal"],
            'nmdiagnosaawal' => $data["response"]["sep"]["diagnosa"],
            'kdpolitujuan' => $inputUser["t_sep"]["poli"]["tujuan"],
            'nmpolitujuan' => $data["response"]["sep"]["poli"],
            'klsrawat' => $data["response"]["sep"]["kelasRawat"],
            'lakalantas' => $inputUser["t_sep"]["jaminan"]["lakaLantas"],
            'nomr' => $data["response"]["sep"]["peserta"]["noMr"],
            'nama_pasien' => $data["response"]["sep"]["peserta"]["nama"],
            'tanggal_lahir' => $data["response"]["sep"]["peserta"]["tglLahir"],
            'peserta' => $data["response"]["sep"]["peserta"]["jnsPeserta"],
            'jkel' => $data["response"]["sep"]["peserta"]["kelamin"],
            'no_kartu' => $data["response"]["sep"]["peserta"]["noKartu"],
            'tglpulang' => NULL,
            'asal_rujukan' => $inputUser["t_sep"]["rujukan"]["asalRujukan"],
            'eksekutif' => $data["response"]["sep"]["poliEksekutif"],
            'cob' => $inputUser["t_sep"]["cob"]["cob"],
            'penjamin' => $data["response"]["sep"]["penjamin"],
            'notelep' => $inputUser["t_sep"]["noTelp"],
            'katarak' => $inputUser["t_sep"]["katarak"]["katarak"],
            'tglkkl' => $inputUser["t_sep"]["jaminan"]["penjamin"]["tglKejadian"],
            'keterangankkl' => $inputUser["t_sep"]["jaminan"]["penjamin"]["keterangan"],
            'suplesi' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["suplesi"],
            'no_sep_suplesi' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
            'kdprop' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
            'nmprop' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmPropinsi"],
            'kdkab' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
            'nmkab' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKabupaten"],
            'kdkec' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
            'nmkec' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKecamatan"],
            'noskdp' => $inputUser["t_sep"]["skdp"]["noSurat"],
            'kddpjp' => $inputUser["t_sep"]["skdp"]["kodeDPJP"],
            'nmdpjp' => $inputUser["t_sep"]["dpjpLayan"],
            'created_at' => $dateNow,
            'updated_at' => $dateNow,
            "user" => $this->name
        ]);


        if($data["response"] !== NULL) {
            try {
                DB::transaction(function () use ($dataSEP) {
                    SepBPJS::insert($dataSEP);
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

    public function updateSEP($request = [])
    {
        $data = $this->request->input($request);
        $inputUser = $this->request->input("request");
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
     
        $vclaim_conf = $this->connection();
        $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
        $datas = $referensi->updateSEP($data);

        $dataSEP = [];
        array_push($dataSEP, [
            // "no_sep" => $inputUser["t_sep"]["noSep"],
            'catatan' =>  $inputUser["t_sep"]["catatan"],
            'diagawal' => $inputUser["t_sep"]["diagAwal"],
            'nmdiagnosaawal' => $inputUser["t_sep"]["diagnosa"],
            'kdpolitujuan' => $inputUser["t_sep"]["poli"]["tujuan"],
            'nmpolitujuan' => $inputUser["t_sep"]["poli"]["nmtujuan"],
            'lakalantas' => $inputUser["t_sep"]["jaminan"]["lakaLantas"],
            'nomr' => $inputUser["t_sep"]["noMR"],
            'tglpulang' => NULL,
            'eksekutif' => $inputUser["t_sep"]["poli"]["eksekutif"],
            'cob' => $inputUser["t_sep"]["cob"]["cob"],
            'notelep' => $inputUser["t_sep"]["noTelp"],
            'katarak' => $inputUser["t_sep"]["katarak"]["katarak"],
            'tglkkl' => $inputUser["t_sep"]["jaminan"]["penjamin"]["tglKejadian"],
            'keterangankkl' => $inputUser["t_sep"]["jaminan"]["penjamin"]["keterangan"],
            'suplesi' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["suplesi"],
            'no_sep_suplesi' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
            'kdprop' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
            'nmprop' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmPropinsi"],
            'kdkab' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
            'nmkab' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKabupaten"],
            'kdkec' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
            'nmkec' => $inputUser["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKecamatan"],
            'noskdp' => $inputUser["t_sep"]["skdp"]["noSurat"],
            'kddpjp' => $inputUser["t_sep"]["skdp"]["kodeDPJP"],
            'nmdpjp' => $inputUser["t_sep"]["dpjpLayan"],
            'created_at' => $dateNow,
            'updated_at' => $dateNow,
            "user" => $this->name
        ]);

        // var_dump($dataSEP);
        
        try {
            if($datas["response"] !== NULL) {
                try {
                    DB::transaction(function () use ($datas, $dataSEP) {
                        SepBPJS::where('no_sep', $datas["response"])->update($dataSEP[0]);
                    });
        
                    DB::commit();
        
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $datas["metaData"],
                        'data'        => $datas["response"],
                        'message'     => "BPJS CONNECTED!"
                    ], 200);
                } catch (\Exception $e) {
                    DB::rollback();
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

    public function deleteSEP($request = [])
    {
        $data = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $datas = $referensi->deleteSEP($data);
            if($datas["metaData"]["code"] === "200") {

                try {
                    DB::transaction(function () use ($data) {
                        $ticket = SepBPJS::where("no_Sep", $data["request"]["t_sep"]["noSep"]);
                        $ticket->delete();
                    });

        
                    DB::commit();
        
                    return response()->json([
                        'acknowledge' => 1,
                        'metaData'    => $datas["metaData"],
                        'data'        => $datas["response"],
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
                    DB::transaction(function () use ($inputUser, $dataSEP) {
                        SepBPJS::where('no_sep', $inputUser["t_sep"]["noSep"])->update($dataSEP[0]);
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
