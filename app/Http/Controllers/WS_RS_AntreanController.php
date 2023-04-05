<?php


namespace App\Http\Controllers;

use Purnama97;
use App\Libraries\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\JadwalDokter;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use App\Antrian;
use App\MappingDokter;
use App\MappingPoli;
use App\Pasien;

class WS_RS_AntreanController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        // $this->name = $this->request->auth['Credentials']->name;
    }

    public function convert_mil_to_date($mil){
        $seconds = $mil / 1000;
        return date("d-m-Y H:i:s", $seconds);
    }

    public function convert_date_to_mil($date){
        $date = strtotime($date) * 1000;
        return $date;
    }

    public function count_time($date, $time, $jml){
        if($jml > 1) {
            $newDate = $date.' '.$time.':00';
            $result = $this->convert_date_to_mil($newDate) + (300000 * ($jml-1));
            return $result;
        }else{
            $newDate = $date.' '.$time.':00';
            $result = $this->convert_date_to_mil($newDate);
            return $result;
        }
    }

    public function get_struk_no($tanggalperiksa)
    {
        $datenow = Carbon::create($tanggalperiksa);
        $date = Carbon::now()->toDateString();
        $num =  DB::table('rs_counter_antrian')->whereDate('createdAt', $datenow)->orderBy('created_at', 'Desc')->max('kodeBooking');

        if (!empty($num)) {
           return 'BOK' . $datenow->format('Y') . $datenow->format('m') . $datenow->format('d') . sprintf("%05s", (substr($num, 11) + 1));
        } elseif (empty($num)) {
            return $explode = 'BOK' . $datenow->format('Y') . $datenow->format('m') . $datenow->format('d') . sprintf("%05s", 1);
        }
    }

    // public function get_struk_no($tanggalperiksa){
	// 		$tanggal    = explode('-', $tanggalperiksa);
	// 		$monthNow  = $tanggal[2];
    //         $yearNow            = $tanggal[0];
    //         $dayNow             = $tanggal[1];
	// 		$datenow    = Carbon::now()->toDateString();

	// 		$num        = DB::table('rs_counter_antrian')->whereDate('createdAt', $datenow)->max('kodeBooking');
			
	// 		if ($num == 0 || $num == null) {
	// 				$result = intval($yearNow . $monthNow . $dayNow  . sprintf('%05s', 1));
	// 		} else {
	// 				$result = intval($num + 1);
	// 		}

	// 		return response()->json([
    //             "strukNo" => $result,
    //             "acknowledge" => 1,
    //             "message"    => 'True!.'
	// 		], 200);
    // }

	public function counting($polyCode, $docCode, $regDate)
    {
        $datenow    = Carbon::now()->toDateString();

        $polyId = $polyCode === "" ? "POL001" : $polyCode; 

        $polyInitial = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoli", $polyId)
                        ->value("b.inisial_poli");

        $urutanPoly = MappingDokter::from("rs_mapping_dokter_asuransi as a")
                        ->leftJoin("rs_dokter as b", "a.kodeDokter", '=', 'b.dokter_id')
                        ->where("a.kodeDokter", $docCode)
                        ->value("b.urutan_poli");

        $getAntrian = Antrian::where('bookingDate', $regDate)->where('kodePoli', $polyCode)->where('kodeDokter', $docCode)->count();
        
        $data = (int)$getAntrian + 1;

        if ($getAntrian < 1 || $getAntrian === "") {
            $result = intval(1);
            return response()->json([
                "acknowledge" => 1,
                "noAntri" => $polyInitial . $urutanPoly . '-' .  sprintf('%03s', $result),
                "count" => $result,
                "message"    => 'Sukses!'
            ], 200);
        } else {
            $result = intval($data);
            return response()->json([
                "acknowledge" => 1,
                "noAntri" => $polyInitial . $urutanPoly . '-' .  sprintf('%03s', $result),
                "count" => $result,
                "message"    => 'Sukses!'
            ], 200);
        }
    }

    public function getStatus()
    {
			try {
				$kodepoli = $this->request->input('kodepoli');
				$kodedokter = $this->request->input('kodedokter');
				$tanggalperiksa = $this->request->input('tanggalperiksa');
				$jampraktek = $this->request->input('jampraktek');

				$jampraktek = explode('-', $jampraktek);

				$data = JadwalDokter::where('poli_id', $kodepoli)
							->where('dokter_id', $kodedokter)
							->where('buka', $jampraktek[0])
							->where('tutup', $jampraktek[1])
							// ->where('jampraktek', $jampraktek)
							->first();

				if($data) {   
					return response()->json([
						"metadata" => [
							"code" => 200,
							"message" => "Ok"
						],
						'response'  => [
							"namapoli" => "Anak",
							"namadokter" =>  "Dr. Hendra",
							"totalantrean" =>  25,
							"sisaantrean" =>  4,
							"antreanpanggil" =>  "A-21",
							"sisakuotajkn" =>  5,
							"kuotajkn" =>  30,
							"sisakuotanonjkn" =>  5,
							"kuotanonjkn" =>  30,
							"keterangan" =>  $this->get_struk_no()->getData()->strukNo
						]
					], 200);
				}else{
                    return response()->json([
                        'metadata' => [
                            "code"      => 201,
                            "message"   => "Gagal"
                        ],
                        'response' => "Gagal mengecek status!."
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

    public function getAntrian()
    {
			try {
				$dateNow = Carbon::now();
				$nomorkartu = $this->request->input('nomorkartu');
				$nik = $this->request->input('nik');
				$nohp = $this->request->input('nohp');
				$kodepoli = $this->request->input('kodepoli');
				$norm = $this->request->input('norm');
				$tanggalperiksa = $this->request->input('tanggalperiksa');
				$kodedokter = $this->request->input('kodedokter');
				$jampraktek = $this->request->input('jampraktek');
				$jeniskunjungan = $this->request->input('jeniskunjungan');
				$nomorreferensi = $this->request->input('nomorreferensi');
				$kodeBooking = $this->get_struk_no($tanggalperiksa)->getData()->strukNo;
                $jam = explode('-', $jampraktek);
                
                $poliId = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoliAsuransi", $kodepoli)
                        ->value("a.kodePoli");

                $dokterId = MappingDokter::from("rs_mapping_dokter_asuransi as a")
                        ->leftJoin("rs_dokter as b", "a.kodeDokter", '=', 'b.dokter_id')
                        ->where("a.kodeDokterAsuransi", $kodedokter)
                        ->value("a.kodeDokter");

                $noAntrian = $this->counting($poliId, $dokterId, $tanggalperiksa)->getData();

				$request = [
					"kodeBooking" => $kodeBooking,
					"kodePoli" => $poliId,
					"kodeDokter" => $dokterId,
					"noKartu" => $nomorkartu,
					"nik" => $nik,
					"noRm" => $norm,
					"noHp" => $nohp,
					"nomorAntrian" => $noAntrian->noAntri,
					"angkaAntrean" => $noAntrian->count,
					"isJkn" => 1,
					"bookingDate" => $tanggalperiksa,
					"jamPraktek" => $jampraktek,
					"noReferensi" => $nomorreferensi,
					"jenisKunjungan" => $jeniskunjungan,
					"isCall" => 0,
					"isCancel" => 0,
					"isCallOn" => 0,
					"isCheckIn" => 0,
					"isOnsite" => 0,
                    "estimasiDilayani" => $this->count_time($tanggalperiksa, $jam[0], $noAntrian->count),
					"createdAt" => $dateNow->toDateTimeString(),
					"updatedAt" => $dateNow->toDateTimeString()
				];
				
				$jadwal = JadwalDokter::where('poli_id', $poliId)
								->where('dokter_id', $dokterId)
								->where('buka', $jam[0])
								->where('tutup', $jam[1])
								->first();
			

                Antrian::insert($request);

                $response = Antrian::from("rs_counter_antrian as a")
                                    ->select(
                                        "a.kodeBooking",
                                        "a.kodePoli",
                                        "b.namaPoliAsuransi as namaPoli",
                                        "a.kodeDokter",
                                        "c.namaDokterAsuransi as namaDokter",
                                        "a.noKartu",
                                        "a.nik",
                                        "a.noRm",
                                        "a.noHp",
                                        "a.nomorAntrian",
                                        "a.angkaAntrean",
                                        "a.isJkn",
                                        "a.bookingDate",
                                        "a.jamPraktek",
                                        "a.noReferensi",
                                        "a.jenisKunjungan",
                                        "a.isCall",
                                        "a.estimasiDilayani",
                                        "a.createdAt",
                                        "a.updatedAt"
                                    )
                                    ->leftJoin("rs_mapping_poli_asuransi as b", "a.kodePoli", '=', 'b.kodePoli')
                                    ->leftJoin("rs_mapping_dokter_asuransi as c", "a.kodeDokter", '=', 'c.kodeDokter')
                                    ->where("a.bookingDate", $tanggalperiksa)
                                    ->where("a.jamPraktek", $jampraktek)
                                    ->where("a.kodeBooking", $kodeBooking)
                                    ->where("a.kodePoli", $poliId)
                                    ->where("a.kodeDokter", $dokterId);


                $JmlJkn = Antrian::from("rs_counter_antrian as a")
                                        ->where("a.bookingDate", $tanggalperiksa)
                                        ->where("a.jamPraktek", $jampraktek)
                                        ->where("a.kodePoli", $poliId)
                                        ->where("a.kodeDokter", $dokterId)
                                        ->where("a.isJkn", 1);

                $JmlNonJkn = Antrian::from("rs_counter_antrian as a")
                                        ->where("a.bookingDate", $tanggalperiksa)
                                        ->where("a.jamPraktek", $jampraktek)
                                        ->where("a.kodePoli", $poliId)
                                        ->where("a.kodeDokter", $dokterId)
                                        ->where("a.isJkn", 0);

				if(sizeof($response->get()) > 0) {   

						$data = [
                                    "metadata" => [
                                        "code" => 200,
                                        "message" => "Ok"
                                    ],
                                    "response" => [
                                        "nomorantrean" => $response->first()->nomorAntrian,
                                        "angkaantrean" => $response->first()->angkaAntrean,
                                        "kodebooking" => $kodeBooking,
                                        "norm" => $norm,
                                        "namapoli" => $response->first()->namaPoli,
                                        "namadokter" => $response->first()->namaDokter,
                                        "estimasiDilayani" => $response->first()->estimasiDilayani,
                                        "sisakuotajkn" => $jadwal->kuotaJkn - $JmlJkn->count(),
                                        "kuotajkn" => $jadwal->kuotaJkn,
                                        "sisakuotanonjkn" => $jadwal->kuotaNonJkn - $JmlNonJkn->count(),
                                        "kuotanonjkn" => $jadwal->kuotaNonJkn,
                                        "keterangan" => "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
                                    ],
								];

						return response()->json([
                            'metaData'    => $data["metadata"],
                            'response'        => $data["response"]
						], 200);
				}else{
						return response()->json([
                            'metaData'    => [
                                "code" => 201,
                                "message" => "Gagal"
                            ],
                            'response' => "Gagal mengambil antrean!.",
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

    public function getSisaAntrian()
    {
        try {

            $kodeBooking = $this->request->input('kodebooking');
            $response = Antrian::from("rs_counter_antrian as a")
                        ->select(
                                "a.kodeBooking",
                                "a.kodePoli",
                                "b.namaPoliAsuransi as namaPoli",
                                "a.kodeDokter",
                                "c.namaDokterAsuransi as namaDokter",
                                "a.noKartu",
                                "a.nik",
                                "a.noRm",
                                "a.noHp",
                                "a.nomorAntrian",
                                "a.angkaAntrean",
                                "a.isJkn",
                                "a.bookingDate",
                                "a.jamPraktek",
                                "a.noReferensi",
                                "a.jenisKunjungan",
                                "a.isCall",
                                "a.createdAt",
                                "a.updatedAt"
                        )
                        ->leftJoin("rs_mapping_poli_asuransi as b", "a.kodePoli", '=', 'b.kodePoliAsuransi')
                        ->leftJoin("rs_mapping_dokter_asuransi as c", "a.kodeDokter", '=', 'c.kodeDokterAsuransi')
                        ->where("a.kodeBooking", $kodeBooking);

            if(sizeof($response->get()) > 0) { 
                $sisa = Antrian::from("rs_counter_antrian as a")
                    ->where("a.bookingDate",  $response->first()->bookingDate)
                    ->where("a.kodePoli",  $response->first()->kodePoli)
                    ->where("a.kodeDokter",  $response->first()->kodeDokter)
                    ->where("a.isCall", 0)
                    ->where("a.isCancel", 0)
                    ->where("a.angkaAntrean", "<", $response->first()->angkaAntrean)
                    ->count();

                $call = Antrian::from("rs_counter_antrian as a")
                        ->select("a.nomorAntrian")
                        ->where("a.bookingDate",  $response->first()->bookingDate)
                        ->where("a.kodePoli",  $response->first()->kodePoli)
                        ->where("a.kodeDokter",  $response->first()->kodeDokter)
                        ->where("a.isCall", 1)
                        ->orderBy('angkaAntrean', 'DESC')
                        ->first();

                $data = [
                    "metadata" => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                    "response" => [
                        "nomorantrean" => $response->first()->nomorAntrian,
                        "namapoli" => $response->first()->namaPoli,
                        "namadokter" => $response->first()->namaDokter,
                        "sisaantrean" => $sisa,
                        "antreanpanggil" => $call === Null ? "-" : $call->nomorAntrian,
                        "waktutunggu" => 9000,
                        "keterangan" => ""
                    ]
                ];

                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'        => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal",
                        "data" => $response->get()
                    ],
                    'response'        => "Gagal cek sisa antrean!.",
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

    public function batalAntrian()
    {
        $kodeBooking = $this->request->input('kodebooking');
        $keterangan = $this->request->input('keterangan');
        $dateNow = Carbon::now();
        try {
            Antrian::where("kodeBooking", $kodeBooking)
                    ->update([
                        "isCancel" => 1,
                        "waktuCancel" =>  $this->convert_date_to_mil($dateNow->toDateTimeString()),
                        "note" => $keterangan
                    ]);

            $data  = Antrian::where("kodeBooking", $kodeBooking)->where("isCancel", 1)->get();


            if(sizeof($data) > 0) {   
                return response()->json([
                    'metaData'    => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'    => "Gagal membatalkan antrean!.",
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

    public function checkinAntrian()
    {
        $kodeBooking = $this->request->input('kodebooking');
        $waktu = $this->request->input('waktu');
        $dateNow = Carbon::now();
        try {
            Antrian::where("kodeBooking", $kodeBooking)
                    ->update([
                        "isCheckIn" => 1,
                        "waktuCheckIn" => $waktu
                    ]);

            $data  = Antrian::where("kodeBooking", $kodeBooking)->where("isCheckIn", 1)->get();


            if(sizeof($data) > 0) {   
                return response()->json([
                    'metaData'    => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal Checkin antrean!.",
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

    public function getInfoPasien()
    {
        try {

            Pasien::insert([
                'nama_pasien' ,
                'no_ktp',
                'salut',
                'alamat',
                'provinsi_id',
                'kota_kab_id',
                'kecamatan_id',
                'kelurahan_id',
                'rt',
                'rw',
                'kode_pos',
                'tmp_lahir',
                'tgl_lahir',
                'agama',
                'pendidikan',
                'gol_darah',
                'telp',
                'hp',
                'pekerjaan_id',
                'ibu',
                'pasangan',
                'jk',
                'status_pasien',
                'created_at',
                'updated_at',
                'status_aktif',
                'asuransi',
                'no_kartu',
                'pasien_id',
                'email',
                'ayah',
                'exp_kartu',
                'pj_pasien',
                'berkas_rm'
            ]);

            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response"=> [
                    "norm" => "123456"
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                            "code" => 201,
                            "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat info pasien!.",
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

    public function jadwalOkRS()
    {
        try {
            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response" =>  [
                    "list" => [
                        [
                            "kodebooking" => "123456ZXC",
                            "tanggaloperasi" => "2019-12-11",
                            "jenistindakan" => "operasi gigi",
                            "kodepoli" => "001",
                            "namapoli" => "Poli Bedah Mulut",
                            "terlaksana" => 1,
                            "nopeserta" => "0000000924782",
                            "lastupdate" => 1577417743000 
                        ],
                        [
                            "kodebooking" => "67890QWE",
                            "tanggaloperasi" => "2019-12-11",
                            "jenistindakan" => "operasi mulut",
                            "kodepoli" => "001",
                            "namapoli" => "Poli Bedah Mulut",
                            "terlaksana" => 0,
                            "nopeserta" => "",
                            "lastupdate" => 1577417743000
                        ]
                    ]
                ]
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat info pasien!.",
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

    public function jadwalOkPasien()
    {
        try {
            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response"=> [
                    "norm" => "123456"
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat info pasien!.",
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

    public function getAntrianFarmasi()
    {
        try {
						
            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response"=> [
                    "jenisresep" => "Racikan/Non Racikan",
                    "nomorantrean" => 1,
                    "keterangan" => ""
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat info pasien!.",
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

    public function getStatusAntrianFarmasi()
    {
        try {
            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response"=> [
                    "jenisresep" => "Racikan/Non Racikan",
                    "totalantrean"  => 10,
                    "sisaantrean"  => 8,
                    "antreanpanggil" => 2,
                    "keterangan" => ""
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metaData'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metaData'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat info pasien!.",
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
