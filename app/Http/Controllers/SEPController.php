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
        $this->sub = $this->request->auth['Credentials']->sub;
        $this->name = $this->request->auth['Credentials']->name;
    }

    public function connection()
    {

        $vclaim_conf = [
            'cons_id' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('CONS_ID_BPJS_DEV') : env('CONS_ID_BPJS'),
            'secret_key' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('SECRET_KEY_BPJS_DEV') : env('SECRET_KEY_BPJS'),
            'base_url' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('BASE_URL_BPJS_DEV') : env('BASE_URL_BPJS'),
            'user_key' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('USER_KEY_BPJS_DEV') : env('USER_KEY_BPJS'),
            'service_name' => env('APP_ENV_SERVICE') === 'DEVELOPMENT' ? env('SERVICE_NAME_BPJS_DEV') : env('SERVICE_NAME_BPJS'),
        ];

        return $vclaim_conf;
    }

    //===================================SEP=======================================================
    public function listSEP(Request $request)
    {
        //use your own bpjs config
        $first_period = $request->input('firstPeriod');
        $last_period = $request->input('lastPeriod'); 
        $keyword = $request->input('keyword'); 

        try {
            $data = SepBPJS::from("rs_bridging_sep as a")
                        ->select('*')
                        ->orderBy('a.createdAt', 'DESC')
						->where('a.statusAktif', 1)
                        ->where(function ($query) use ($first_period, $last_period, $keyword) {
                            $query->whereBetween("a.tglsep", [$first_period, $last_period]);
                            if (!empty($keyword)) {
                                $query->where("a.noMr", 'like', "%{$keyword}%")
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

		public function detailSEP($noSEP)
    {
        try {
            $data = SepBPJS::from("rs_bridging_sep as a")
                        ->select('*')
                        ->where("a.noSep", $noSEP)
                        ->first();

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

            if($data["metaData"]["code"] === "200") {
                return response()->json([
                    'acknowledge' => 1,
                    'metaData'    => $data["metaData"],
                    'data'        => $data["response"],
                    'peserta'       => $data["response"]
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
        $input = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
          
        $dataSep =  [
            "request" => [
            "t_sep" => [
              "noKartu"  => $input["request"]["t_sep"]["noKartu"],
              "tglSep"  => $input["request"]["t_sep"]["tglSep"],
              "ppkPelayanan" => $input["request"]["t_sep"]["kdPpkPelayanan"],
              "jnsPelayanan" => $input["request"]["t_sep"]["jnsPelayanan"],
              "klsRawat" => [
                "klsRawatHak" => $input["request"]["t_sep"]["klsRawat"]["klsRawatHak"],
                "klsRawatNaik" =>  $input["request"]["t_sep"]["klsRawat"]["klsRawatNaik"],
                "pembiayaan" =>  $input["request"]["t_sep"]["klsRawat"]["pembiayaan"],
                "penanggungJawab" =>  $input["request"]["t_sep"]["klsRawat"]["penanggungJawab"],
              ],
              "noMR" => $input["request"]["t_sep"]["noMR"],
              "rujukan" => [
                "asalRujukan" => $input["request"]["t_sep"]["rujukan"]["asalRujukan"],
                "tglRujukan" => $input["request"]["t_sep"]["rujukan"]["tglRujukan"],
                "noRujukan" => $input["request"]["t_sep"]["rujukan"]["noRujukan"],
                "ppkRujukan" => $input["request"]["t_sep"]["rujukan"]["kdPpkRujukan"],
              ],
              "catatan" => $input["request"]["t_sep"]["catatan"],
              "diagAwal" => $input["request"]["t_sep"]["kdDiagAwal"],
              "poli" => [
                "tujuan" => $input["request"]["t_sep"]["poli"]["kdPoliTujuan"],
                "eksekutif" =>  $input["request"]["t_sep"]["poli"]["eksekutif"],
              ],
              "cob" => [
                "cob" => $input["request"]["t_sep"]["cob"]["cob"],
              ],
              "katarak" => [
                "katarak" => $input["request"]["t_sep"]["katarak"]["katarak"],
              ],
              "jaminan" => [
                "lakaLantas" => $input["request"]["t_sep"]["jaminan"]["lakaLantas"],
                "noLp" => $input["request"]["t_sep"]["jaminan"]["noLp"],
                "penjamin" => [
                  "tglKejadian" =>  $input["request"]["t_sep"]["jaminan"]["penjamin"]["tglKejadian"],
                  "keterangan" =>  $input["request"]["t_sep"]["jaminan"]["penjamin"]["keterangan"],
                  "suplesi" => [
                    "suplesi" =>  $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["suplesi"],
                    "noSepSuplesi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
                    "lokasiLaka" => [
                      "kdPropinsi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
                      "kdKabupaten" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
                      "kdKecamatan" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
                    ],
                  ],
                ],
              ],
              "tujuanKunj" => $input["request"]["t_sep"]["tujuanKunj"],
              "flagProcedure" => $input["request"]["t_sep"]["flagProcedure"],
              "kdPenunjang" => $input["request"]["t_sep"]["kdPenunjang"],
              "assesmentPel" => $input["request"]["t_sep"]["assesmentPel"],
              "skdp" => [
                "noSurat" =>  $input["request"]["t_sep"]["skdp"]["noSurat"],
                "kodeDPJP" =>  $input["request"]["t_sep"]["skdp"]["kodeDPJP"], // dokterDpjp.kode,
              ],
              "dpjpLayan" => $input["request"]["t_sep"]["kdDpjpLayan"],
              "noTelp" => $input["request"]["t_sep"]["noTelp"],
              "user" => $input["request"]["t_sep"]["user"],
						]
					]
				];

        $dateNow = Carbon::now()->toDateTimeString();
        $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
        $data = $referensi->insertSEP($dataSep);
        
        if($data["metaData"]["code"] === "200") {
            try {    
                SepBPJS::insert([
                    "noSep" => $data["response"]["sep"]["noSep"],
                    "noRawat" => "",
                    "tglsep" => $input["request"]["t_sep"]["tglSep"],
                    "tglRujukan" => $input["request"]["t_sep"]["rujukan"]["tglRujukan"],
                    "noRujukan" => $input["request"]["t_sep"]["rujukan"]["noRujukan"],
                    "kdPpkRujukan" => $input["request"]["t_sep"]["rujukan"]["kdPpkRujukan"],
                    "nmPpkRujukan" => $input["request"]["t_sep"]["rujukan"]["nmPpkRujukan"],
                    "kdPpkPelayanan" => $input["request"]["t_sep"]["kdPpkPelayanan"],
                    "nmPpkPelayanan" => $input["request"]["t_sep"]["nmPpkPelayanan"],
                    "jnsPelayanan" => $input["request"]["t_sep"]["jnsPelayanan"],
                    "catatan" => $input["request"]["t_sep"]["catatan"],
                    "diagAwal" => $input["request"]["t_sep"]["kdDiagAwal"],
                    "nmDiagnosa" => $input["request"]["t_sep"]["nmDiagAwal"],
                    "kdPoliTujuan" => $input["request"]["t_sep"]["poli"]["kdPoliTujuan"],
                    "nmPoliTujuan" => $input["request"]["t_sep"]["poli"]["nmPoliTujuan"],
                    "klsRawat" => $input["request"]["t_sep"]["klsRawat"]["klsRawatNaik"],
                    "lakaLantas" => $input["request"]["t_sep"]["jaminan"]["lakaLantas"],
                    "user" => $this->sub,
                    "noMr" => $input["request"]["t_sep"]["noMR"],
                    "namaPasien" => $data["response"]["sep"]["peserta"]["nama"],
                    "tglLahir" => $data["response"]["sep"]["peserta"]["tglLahir"],
                    "peserta" => $data["response"]["sep"]["peserta"]["jnsPeserta"],
                    "jKel" => $data["response"]["sep"]["peserta"]["kelamin"],
                    "noKartu" => $input["request"]["t_sep"]["noKartu"],
                    "tglPulang" => "",
                    "asalRujukan" => $input["request"]["t_sep"]["rujukan"]["asalRujukan"],
                    "eksekutif" => $input["request"]["t_sep"]["poli"]["eksekutif"],
                    "cob" => $input["request"]["t_sep"]["cob"]["cob"],
                    "penjamin" => $input["request"]["t_sep"]["klsRawat"]["penanggungJawab"],
                    "noTelp" => $input["request"]["t_sep"]["noTelp"],
                    "katarak" => $input["request"]["t_sep"]["katarak"]["katarak"],
                    "tglKkl" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["tglKejadian"],
                    "keteranganKkl" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["keterangan"],
                    "suplesi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["suplesi"],
                    "noSepSuplesi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
                    "kdProv" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
                    "nmProp" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmPropinsi"],
                    "kdKab" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
                    "nmKab" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKabupaten"],
                    "kdKec" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
                    "nmKec" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKecamatan"],
                    "noSkdp" => $input["request"]["t_sep"]["skdp"]["noSurat"],
                    "kdDpjp" => $input["request"]["t_sep"]["jnsPelayanan"] === "2" ? $input["request"]["t_sep"]["kdDpjpLayan"] : $input["request"]["t_sep"]["skdp"]["kodeDPJP"],
                    "nmDpjp" => $input["request"]["t_sep"]["jnsPelayanan"] === "2" ? $input["request"]["t_sep"]["nmDpjpLayan"] : $input["request"]["t_sep"]["skdp"]["namaDPJP"],
                    "hakKelas" => $input["request"]["t_sep"]["klsRawat"]["klsRawatHak"],
                    "tujuan" => $input["request"]["t_sep"]["tujuanKunj"],
                    "penunjang" => $input["request"]["t_sep"]["kdPenunjang"],
                    "assesment" => $input["request"]["t_sep"]["assesmentPel"],
                    "flagProcedure" => $input["request"]["t_sep"]["flagProcedure"],
                    "statusAktif" => 1,
                    "createdAt" => $dateNow,
                    "updatedAt" => $dateNow
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
                'data'        => $dataSep,
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
        $input = $this->request->input($request);
        $dateNow = Carbon::now()->toDateTimeString();
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
             
				$dataSep =  [
					"request" => [
						"t_sep" => [
							"noSep" => $input["request"]["t_sep"]["noSep"],
							"klsRawat" => [
                "klsRawatHak" => $input["request"]["t_sep"]["klsRawat"]["klsRawatHak"],
                "klsRawatNaik" =>  $input["request"]["t_sep"]["klsRawat"]["klsRawatNaik"],
                "pembiayaan" =>  $input["request"]["t_sep"]["klsRawat"]["pembiayaan"],
                "penanggungJawab" =>  $input["request"]["t_sep"]["klsRawat"]["penanggungJawab"],
              ],
							"noMR" => $input["request"]["t_sep"]["noMR"],
							"catatan" => $input["request"]["t_sep"]["catatan"],
							"diagAwal" => $input["request"]["t_sep"]["kdDiagAwal"],
							"poli" => [
                "tujuan" => $input["request"]["t_sep"]["poli"]["kdPoliTujuan"],
                "eksekutif" =>  $input["request"]["t_sep"]["poli"]["eksekutif"],
              ],
							"cob" => [
                "cob" => $input["request"]["t_sep"]["cob"]["cob"],
              ],
              "katarak" => [
                "katarak" => $input["request"]["t_sep"]["katarak"]["katarak"],
              ],
							"jaminan" => [
                "lakaLantas" => $input["request"]["t_sep"]["jaminan"]["lakaLantas"],
                "noLp" => $input["request"]["t_sep"]["jaminan"]["noLp"],
                "penjamin" => [
                  "tglKejadian" =>  $input["request"]["t_sep"]["jaminan"]["penjamin"]["tglKejadian"],
                  "keterangan" =>  $input["request"]["t_sep"]["jaminan"]["penjamin"]["keterangan"],
                  "suplesi" => [
                    "suplesi" =>  $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["suplesi"],
                    "noSepSuplesi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
                    "lokasiLaka" => [
                      "kdPropinsi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
                      "kdKabupaten" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
                      "kdKecamatan" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
                    ],
                  ],
                ],
              ],
							"dpjpLayan" => $input["request"]["t_sep"]["kdDpjpLayan"],
              "noTelp" => $input["request"]["t_sep"]["noTelp"],
              "user" => $input["request"]["t_sep"]["user"],
						],
					],
				];

        try {
            $vclaim_conf = $this->connection();
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $datas = $referensi->updateSEP($dataSep);

            if($datas["response"] !== NULL) {
                try {
								
									SepBPJS::where("noSep", $input["request"]["t_sep"]["noSep"])
										->update([
											"klsRawat" => $input["request"]["t_sep"]["klsRawat"]["klsRawatNaik"],
											"hakKelas" => $input["request"]["t_sep"]["klsRawat"]["klsRawatHak"],
											"noMr" => $input["request"]["t_sep"]["noMR"],
											"catatan" => $input["request"]["t_sep"]["catatan"],
											"diagAwal" => $input["request"]["t_sep"]["kdDiagAwal"],
											"nmDiagnosa" => $input["request"]["t_sep"]["nmDiagAwal"],
											"kdPoliTujuan" => $input["request"]["t_sep"]["poli"]["kdPoliTujuan"],
											"nmPoliTujuan" => $input["request"]["t_sep"]["poli"]["nmPoliTujuan"],
											"eksekutif" => $input["request"]["t_sep"]["poli"]["eksekutif"],
											"cob" => $input["request"]["t_sep"]["cob"]["cob"],
											"katarak" => $input["request"]["t_sep"]["katarak"]["katarak"],
											"lakaLantas" => $input["request"]["t_sep"]["jaminan"]["lakaLantas"],
											"keteranganKkl" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["keterangan"],
											"tglKkl" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["tglKejadian"],
											"suplesi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["suplesi"],
											"noSepSuplesi" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["noSepSuplesi"],
											"kdProv" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdPropinsi"],
											"nmProp" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmPropinsi"],
											"kdKab" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKabupaten"],
											"nmKab" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKabupaten"],
											"kdKec" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["kdKecamatan"],
											"nmKec" => $input["request"]["t_sep"]["jaminan"]["penjamin"]["suplesi"]["lokasiLaka"]["nmKecamatan"],
											"user" => $this->sub,
											"noTelp" => $input["request"]["t_sep"]["noTelp"],
											"updatedAt" => $dateNow
										]);

										if($input["request"]["t_sep"]["jnsPelayanan"] === "2") {
											SepBPJS::where("noSep", $input["request"]["t_sep"]["noSep"])
											->update([
												"kdDpjp" => $input["request"]["t_sep"]["jnsPelayanan"] === "2" ? $input["request"]["t_sep"]["kdDpjpLayan"] : "",
												"nmDpjp" => $input["request"]["t_sep"]["jnsPelayanan"] === "2" ? $input["request"]["t_sep"]["nmDpjpLayan"] : "",
											]);
										}
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
                    SepBPJS::where("noSep", $noSEP)
                        ->update([
                            "statusAktif" => 0
                        ]);

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
            if($data["metaData"]["code"] === "200") {
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
            if($data["metaData"]["code"] === "200") {
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

    public function persetujuanSEP($bulan, $tahun) {
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->persetujuanSEP($bulan, $tahun);
            if($data["metaData"]["code"] === "200") {
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
        $input = $this->request->input($request);
        $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $vclaim_conf = $this->connection();
        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->updateTglPlg($input);

            if($data["metaData"]["code"] === "200") {   
                try {
                    SepBPJS::where("noSep", $input["request"]["t_sep"]["noSep"])
                        ->update([
                            "tglPulang" => $input["request"]["t_sep"]["tglPulang"]
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
            if($data["metaData"]["code"] === "200") {
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
            if($data["metaData"]["code"] === "200") {
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

    public function cariSEPInternal($noSEP)
    {
        //use your own bpjs config
        $vclaim_conf = $this->connection();

        try {
            $referensi = new Purnama97\Bpjs\VClaim\SEP($vclaim_conf);
            $data = $referensi->getSEPInternal($noSEP);

            if($data["metaData"]["code"] === "200") {
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

        $req = [
            "request" => [
                "t_sep" => [
                    "noSep" => $data["noSep"],
                    "noSurat"=> $data["noSurat"],
                    "tglRujukanInternal"=> $data["tglRujukanInternal"],
                    "kdPoliTuj"=> $data["kdPoliTuj"],
                    "user" => $this->name
                ]
            ]
        ];

        $data = $referensi->deleteSepInternal($req);

        try {
            if($data["metaData"]["code"] === "200") {
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
