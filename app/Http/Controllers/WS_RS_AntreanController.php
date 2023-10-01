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

    public function count_wait_time($jml){
        $spm = 600;

        $result = $spm * $jml;
        return $result;
    }

    public function refHari($tanggal) {

        $day = date('D', strtotime($tanggal));
        $dayList = array(
            'Sun' => '7',
            'Mon' => '1',
            'Tue' => '2',
            'Wed' => '3',
            'Thu' => '4',
            'Fri' => '5',
            'Sat' => '6'
        );

        return $dayList[$day];

            
    }

    private function generate_batch2_id_pasien()
    {
        $data = DB::table('rs_pasien')
            ->select(
                'batch_id',
                'batch',
                'batch2_id'
            )
            ->orderBy('urutan_id', 'DESC')
            ->first();

        if (empty($data)) {
            $result = '00';
            return $result;
        } elseif (!empty($data)) {
            if ($data->batch_id == '99') {
                if ($data->batch != '99') {
                    $result = sprintf('%02s', $data->batch2_id);
                    // $result = $data->batch_id + 1;
                    return $result;
                } else {
                    $result = sprintf('%02s', $data->batch2_id + 1);
                    // $result = $data->batch_id + 1;
                    return $result;
                }
            }
            if ($data->batch_id != '99') {
                $result = sprintf('%02s', $data->batch2_id);
                // $result = $data->batch_id + 1;
                return $result;
            }
            if ($data->batch != '99' && $data->batch_id == '00' && $data->batch2_id == '00') {
                $result = '00';
                return $result;
            }
        }
    }

    private function generate_batch_id_pasien()
    {
        $data = DB::table('rs_pasien')
            ->select(
                'batch_id',
                'batch',
                'batch2_id'
            )
            ->orderBy('urutan_id', 'DESC')
            ->first();

        if (empty($data)) {
            $result = '00';
            return $result;
        } elseif (!empty($data)) {
            if ($data->batch == '99') {
                if ($data->batch_id == '99') {
                    $result = '00';
                    return $result;
                } else {
                    $result = sprintf('%02s', $data->batch_id + 1);
                    return $result;
                }
            }
            if ($data->batch != '99' && $data->batch_id == '99') {
                // $result = '00';
                // return $result;
                $result = sprintf('%02s', $data->batch_id);
                // $result = $data->batch_id + 1;
                return $result;
            }
            if ($data->batch_id != '99' && $data->batch != '99') {
                $result = sprintf('%02s', $data->batch_id);
                // $result = $data->batch_id + 1;
                return $result;
            }
        }
    }

    private function generate_urutan_id_pasien()
    {
        $data = DB::table('rs_pasien')
            ->select(
                'batch_id',
                'batch',
                'batch2_id'
            )
            ->orderBy('urutan_id', 'DESC')
            ->first();

        if (empty($data)) {
            $result = '00';
            return $result;
        } elseif (!empty($data)) {

            if ($data->batch == '99') {
                $result = '00';
                return $result;
            }
            if ($data->batch != '99') {
                if ($data->batch_id == '99') {
                    $result = sprintf('%02s', $data->batch + 1);
                    return $result;
                }
                if ($data->batch2_id == '99' && $data->batch_id == '99') {
                    $result = '00';
                    return $result;
                } else {
                    $result = sprintf('%02s', $data->batch + 1);
                    return $result;
                }
            }
        }
    }

    private function generate_urut_pasien()
    {
        $data = DB::table('rs_pasien')
            ->select(
                'urutan_id'
            )
            ->orderBy('urutan_id', 'DESC')
            ->first();

        if (empty($data)) {
            $result = 1;
            return $result;
        } elseif (!empty($data)) {

            $result = $data->urutan_id + 1;
            return $result;
        }
    }

    private function addSpiltAddress($string) {
        $result = '';
        $length = strlen($string);
    
        for ($i = 0; $i < $length; $i += 2) {
            $result .= substr($string, $i, 2) . '.';
        }
    
        return rtrim($result, '.'); // Menghapus titik ekstra di akhir
    }

    public function get_struk_no($tanggalperiksa)
    {
        $datenow = Carbon::create($tanggalperiksa);
        $date = Carbon::now()->toDateString();
        $num =  DB::table('rs_counter_antrian')->whereDate('bookingDate', $datenow)->orderBy('created_at', 'Desc')->max('kodeBooking');

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
                $dateNow = Carbon::now();
				$kodepoli = $this->request->input('kodepoli');
				$kodedokter = $this->request->input('kodedokter');
				$tanggalperiksa = $this->request->input('tanggalperiksa');
				$jampraktek = $this->request->input('jampraktek');

				$explodeJP = explode('-', $jampraktek);

                $cek = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoliAsuransi", $kodepoli)
                        ->exists();

                if(!$cek) {
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Poli Tidak Ditemukan",
						],
					], 201);
                }

                if (strtotime($tanggalperiksa) < strtotime($dateNow->toDateString())) {
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Tanggal Periksa Tidak Berlaku",
						],
					], 201);
                }

                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $tanggalperiksa)){
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd",
						],
					], 201);
                }

                $poliId = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoliAsuransi", $kodepoli)
                        ->value("a.kodePoli");

                $dokterId = MappingDokter::from("rs_mapping_dokter_asuransi as a")
                        ->leftJoin("rs_dokter as b", "a.kodeDokter", '=', 'b.dokter_id')
                        ->where("a.kodeDokterAsuransi", $kodedokter)
                        ->value("a.kodeDokter");

				$data = JadwalDokter::from('rs_jadwal_dokter as a')
                            ->where('a.poli_id', $poliId)
                            ->leftJoin('rs_dokter as b', 'a.dokter_id', '=', 'b.dokter_id')
                            ->leftJoin('rs_poli as c', 'c.poli_id', '=', 'a.poli_id')
							->where('a.dokter_id', $dokterId)
							->where('buka', $explodeJP[0])
							->where('tutup', $explodeJP[1])
							// ->where('jampraktek', $jampraktek)
							->first();

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

                $call = Antrian::from("rs_counter_antrian as a")
                            ->where("a.bookingDate", $tanggalperiksa)
                            ->where("a.jamPraktek", $jampraktek)
                            ->where("a.kodePoli", $poliId)
                            ->where("a.kodeDokter", $dokterId);

                $calls = Antrian::from("rs_counter_antrian as a")
                            ->where("a.bookingDate", $tanggalperiksa)
                            ->where("a.jamPraktek", $jampraktek)
                            ->where("a.kodePoli", $poliId)
                            ->where("a.kodeDokter", $dokterId);

				if($data) {   
					return response()->json([
						"metadata" => [
							"code" => 200,
							"message" => "Ok"
						],
						'response'  => [
							"namapoli" => $data->nama_poli,
							"namadokter" => $data->nama_dokter,
							"totalantrean" =>  $JmlJkn->count() + $JmlNonJkn->count(),
							"sisaantrean" =>  $call->where("a.isCall", 0)->count(),
							"antreanpanggil" => $calls->where("a.isCall", 1)->max("nomorAntrian"),
							"sisakuotajkn" =>  $data->kuotaJkn - $JmlJkn->count(),
							"kuotajkn" =>  $data->kuotaJkn,
							"sisakuotanonjkn" => $data->kuotaNonJkn - $JmlNonJkn->count(),
							"kuotanonjkn" =>  $data->kuotaNonJkn,
							"keterangan" =>  ""
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
				$kodeBooking = $this->get_struk_no($tanggalperiksa);
                $jam = explode('-', $jampraktek);

                if(!Pasien::from("rs_pasien")->where("pasien_id", $norm)->exists()){
                    return response()->json([
						"metadata" => [
							"code" => 202,
							"message" => "Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru",
						],
					], 202);
                }

                $poliId = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoliAsuransi", $kodepoli)
                        ->value("a.kodePoli");

                $dokterId = MappingDokter::from("rs_mapping_dokter_asuransi as a")
                        ->leftJoin("rs_dokter as b", "a.kodeDokter", '=', 'b.dokter_id')
                        ->where("a.kodeDokterAsuransi", $kodedokter)
                        ->value("a.kodeDokter");

                $cek = Antrian::from("rs_counter_antrian as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->leftJoin("rs_mapping_poli_asuransi as c", "a.kodePoli", '=', 'c.kodePoli')
                        ->where("c.kodePoliAsuransi", $kodepoli)
                        ->where("a.bookingDate", $tanggalperiksa)
                        ->where("a.noKartu", $nomorkartu)
                        ->where("a.isCancel", 0)
                        ->exists();

                if($cek) {
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Nomor Antrean Hanya Dapat Diambil 1 Kali Pada Tanggal Yang Sama",
						],
					], 201);
                }

                $sttPoli = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoliAsuransi", $kodepoli)
                        ->where("b.status_aktif", 0)
                        ->exists();

                if($sttPoli) {
                    return response()->json([
                        "metadata" => [
                            "code" => 201,
                            "message" => "Pendaftaran ke Poli Ini Sedang Tutup",
                        ],
                    ], 201);
                }

                $cekJadwal = JadwalDokter::where('hari_id', $this->refHari($tanggalperiksa))
								->where('dokter_id', $dokterId)
								->where('buka', $jam[0])
								->where('tutup', $jam[1])
								->exists();

                if(!$cekJadwal) {
                    return response()->json([
                        "metadata" => [
                            "code" => 201,
                            "message" => "Jadwal Dokter Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya",
                        ],
                    ], 201);
                }

                $cekPasien = DB::table('rs_pasien')
                            ->where('pasien_id', $norm)
                            ->exists();

                if(!$cekPasien) {
                    return response()->json([
                        "metadata" => [
                            "code" => 202,
                            "message" => "Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru",
                        ],
                    ], 202);
                }

                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $tanggalperiksa)){
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd",
						],
					], 201);
                }

                if (strtotime($tanggalperiksa) < strtotime($dateNow->toDateString())) {
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Tanggal Periksa Tidak Berlaku",
						],
					], 201);
                }

                $noAntrian = $this->counting($poliId, $dokterId, $tanggalperiksa)->getData();

                $sisa = Antrian::from("rs_counter_antrian as a")
                                        ->where("a.bookingDate", $tanggalperiksa)
                                        ->where("a.jamPraktek", $jampraktek)
                                        ->where("a.kodePoli", $poliId)
                                        ->where("a.kodeDokter", $dokterId)
                                        ->where("a.isCall", 0)
                                        ->count();

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
                    "estimasidilayani" => $this->count_time($tanggalperiksa, $jam[0], $noAntrian->count),
                    // "estimasiDilayani" => $this->count_wait_time($sisa),
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
                                        "estimasidilayani" => intval($response->first()->estimasiDilayani),
                                        "sisakuotajkn" =>  intval($jadwal->kuotaJkn - $JmlJkn->count()),
                                        "kuotajkn" =>  intval($jadwal->kuotaJkn),
                                        "sisakuotanonjkn" =>  intval($jadwal->kuotaNonJkn - $JmlNonJkn->count()),
                                        "kuotanonjkn" =>  intval($jadwal->kuotaNonJkn),
                                        "keterangan" => "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
                                    ],
								];

						return response()->json([
                            'metadata'    => $data["metadata"],
                            'response'        => $data["response"]
						], 200);
				}else{
						return response()->json([
                            'metadata'    => [
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

            $cek = Antrian::from("rs_counter_antrian as a")
                    ->where("a.kodeBooking", $kodeBooking)
                    ->exists();

            if(!$cek) {
                return response()->json([
                    "metadata" => [
                        "code" => 201,
                        "message" => "Antrean Tidak Ditemukan",
                    ],
                ], 201);
            }

            $response = Antrian::from("rs_counter_antrian as a")
                        ->select(
                                "a.kodeBooking",
                                "a.kodePoli",
                                "b.kodePoliAsuransi",
                                "b.namaPoliAsuransi",
                                "a.kodeDokter",
                                "c.kodeDokterAsuransi",
                                "c.namaDokterAsuransi",
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
                                "a.estimasiDilayani",
                                "a.isCall",
                                "a.createdAt",
                                "a.updatedAt"
                        )
                        ->leftJoin("rs_mapping_poli_asuransi as b", "a.kodePoli", '=', 'b.kodePoli')
                        ->leftJoin("rs_mapping_dokter_asuransi as c", "a.kodeDokter", '=', 'c.kodeDokter')
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
                        "namapoli" => $response->first()->namaPoliAsuransi,
                        "namadokter" => $response->first()->namaDokterAsuransi,
                        "sisaantrean" => $sisa,
                        "antreanpanggil" => $call === Null ? "-" : $call->nomorAntrian,
                        // "waktutunggu" => intval($response->first()->estimasiDilayani),
                        "waktutunggu" => intval($this->count_wait_time($sisa)),
                        "keterangan" => ""
                    ]
                ];

                return response()->json([
                    'metadata'    => $data["metadata"],
                    'response'        => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
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

            $cekValid = Antrian::where('kodeBooking', $kodeBooking)
                    ->whereDate('bookingDate','<', $dateNow->toDateString())
                    ->exists();

            if($cekValid) {
                return response()->json([
                    "metadata" => [
                        "code" => 201,
                        "message" => "Antrean Tidak Ditemukan ",
                    ],
                ], 201);
            }

            $cekCancel= Antrian::where('kodeBooking', $kodeBooking)
                        ->where('isCancel', 1)
                        ->exists();

            if($cekCancel) {
                return response()->json([
                    "metadata" => [
                        "code" => 201,
                        "message" => "Antrean Tidak Ditemukan atau Sudah Dibatalkan",
                    ],
                ], 201);
            }

            $cekDilayani = Antrian::where('kodeBooking', $kodeBooking)
                    ->where('isCheckIn', 1)
                    ->exists();

            if($cekDilayani) {
                return response()->json([
                    "metadata" => [
                        "code" => 201,
                        "message" => "Pasien Sudah Dilayani, Antrean Tidak Dapat Dibatalkan",
                    ],
                ], 201);
            }

            Antrian::where("kodeBooking", $kodeBooking)
                    ->update([
                        "isCancel" => 1,
                        "waktuCancel" =>  $this->convert_date_to_mil($dateNow->toDateTimeString()),
                        "note" => $keterangan
                    ]);

            $data  = Antrian::where("kodeBooking", $kodeBooking)->where("isCancel", 1)->get();


            if(sizeof($data) > 0) {   
                return response()->json([
                    'metadata'    => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
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
                    'metadata'    => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
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
        $date = Carbon::now()->toDateTimeString();
		$tanggalsekarang = Carbon::now()->toDateString();
        try {

            // {
            //     "nomorkartu": "00012345678",
            //     "nik": "3212345678987654",
            //     "nomorkk": "3212345678987654",
            //     "nama": "sumarsono",
            //     "jeniskelamin": "L",
            //     "tanggallahir": "1985-03-01",
            //     "nohp": "085635228888",
            //     "alamat": "alamat yang muncul merupakan alamat lengkap",
            //     "kodeprop": "11",
            //     "namaprop": "Jawa Barat",
            //     "kodedati2": "0120",
            //     "namadati2": "Kab. Bandung",
            //     "kodekec": "1319",
            //     "namakec": "Soreang",
            //     "kodekel": "D2105",
            //     "namakel": "Cingcin",
            //     "rw": "001",
            //     "rt": "013"
            //  }

            

            if(strlen($this->request->input("nomorkartu")) <> 13 && !empty($this->request->input("nomorkartu"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Format Kartu Tidak Sesuai",
                        "code" => 201
                    ]
                ], 201);
            }

            if(strlen($this->request->input("nik")) <> 16 && !empty($this->request->input("nik"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Format NIK Tidak Sesuai",
                        "code" => 201
                    ]
                ], 201);
            }

    

            if(empty($this->request->input("nomorkartu"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Nomor Kartu Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }

            if(empty($this->request->input("nik"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "NIK Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }

            if(empty($this->request->input("nama"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Nama Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }

            if(empty($this->request->input("nomorkk"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Nomor KK Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("jeniskelamin"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Jenis Kelamin Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("tanggallahir"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Tanggal Lahir Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("nohp"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "No HP Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("alamat"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Alamat Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("kodeprop"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Kode Provinsi Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("namaprop"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "Nama Provinsi Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("kodedati2"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "kodedati2 Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("namadati2"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "namadati2 Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("kodekec"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "kodekec Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
			 if(empty($this->request->input("namakec"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "namakec Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("kodekel"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "kodekel Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("namakel"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "namakel Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("rw"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "rw Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
            if(empty($this->request->input("rt"))) {
                return response()->json([
                    'metadata'    => [
                        "message" => "rt Belum Diisi",
                        "code" => 201
                    ]
                ], 201);
            }
			
			 if (strtotime($this->request->input("tanggallahir")) > strtotime($tanggalsekarang)) {
                return response()->json([
                    "metadata" => [
                        "code" => 201,
                        "message" => "Format Tanggal Lahir Tidak Sesuai",
                    ],
                ], 201);
            }

            if(Pasien::where("no_ktp", $this->request->input("nik"))->exists() || Pasien::where("no_kartu", $this->request->input("nomorkartu"))->exists()){
				return response()->json([
					'metadata'    => [
						"code" => 201,
						"message" => "Data Peserta Sudah Pernah Dientrikan"
					]
				], 201);
            }

            $urutan = $this->generate_urutan_id_pasien();
            $batch = $this->generate_batch_id_pasien();
            $batch2 = $this->generate_batch2_id_pasien();
            $urut = $this->generate_urut_pasien();
             
            Pasien::insert([
                "pasien_id" => $urutan . '.' . $batch . '.' . $batch2,
                'nama_pasien' => $this->request->input("nama"),
                'no_ktp' => $this->request->input("nik"),
                'salut' => "",
                'alamat' => $this->request->input("alamat"),
                'provinsi_id' => "",
                'kota_kab_id' => "", // $this->addSpiltAddress(),
                'kecamatan_id' => "", // $this->addSpiltAddress(),
                'kelurahan_id' => "", // $this->addSpiltAddress(),
                'rt' => "",
                'rw' => "",
                'kode_pos' => "",
                'tmp_lahir' => "",
                'tgl_lahir' => $this->request->input("tanggallahir"),
                'agama' => "",
                'pendidikan' => "",
                'gol_darah' => "",
                'telp' => "",
                'hp' => $this->request->input("nohp"),
                'pekerjaan_id' => "",
                'ibu' => "",
                'pasangan' => "",
                'jk'=> $this->request->input("jeniskelamin"),
                'status_pasien' => "PASIEN BARU",
                'created_at' => $date,
                'updated_at' => $date,
                'status_aktif' => 1,
                'asuransi' => "INS000001",
                'no_kartu' => $this->request->input("nomorkartu"),
                'email' => "",
                'ayah' => "",
                'exp_kartu' => "",
                'pj_pasien' => "",
                'berkas_rm' => "",
                "batch" => $urutan,
                "batch_id" => $batch,
                "batch2_id" => $batch2,
                "urutan_id" => $urut,
            ]);

            $noRm = Pasien::from("rs_pasien")->where("created_at", $date)->value("pasien_id");

            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Harap datang ke admisi untuk melengkapi data rekam medis"
                ],
                "response"=> [
                    "norm" => $noRm
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metadata'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
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

    public function jadwalOkRS(Request $request)
    {
        try {
            $tanggalawal = $request->input('tanggalawal');
            $tanggalakhir = $request->input('tanggalakhir');


            if (strtotime($tanggalakhir) < strtotime($tanggalawal)) {
                return response()->json([
                    "metadata" => [
                        "code" => 201,
                        "message" => "Tanggal Akhir Tidak Boleh Lebih Kecil dari Tanggal Awal",
                    ],
                ], 201);
            }

            $data = DB::table('rs_permintaan_operasi as a')
                    ->select(
                        "a.no_permintaan",
                        "a.tgl_mulai",
                        "c.nama_tindakan",
                        "d.kodePoliAsuransi",
                        "d.namaPoliAsuransi",
                        "a.status_operasi",
                        "e.no_kartu",
                        "a.updated_at"
                    )
                    ->leftJoin('rs_detail_permintaan_operasi as b', 'a.no_permintaan', '=', 'b.no_permintaan')
                    ->leftJoin('rs_tindakan_kamar as c', 'b.kode_tindakan', '=', 'c.tindakan_kamar_id')
                    ->leftJoin('rs_mapping_poli_asuransi as d', 'd.kodePoli', '=', 'c.kdPoli')
                    ->leftJoin('rs_pasien as e', 'e.pasien_id', '=', 'a.pasien_id')
                    ->whereBetween('a.tgl_mulai', [$tanggalawal, $tanggalakhir])
                    ->get();

            $jadwal = [];

            foreach($data As $row) {
                array_push($jadwal, [
                    "kodebooking" => $row->no_permintaan,
                    "tanggaloperasi" => $row->tgl_mulai,
                    "jenistindakan" => $row->nama_tindakan,
                    "kodepoli" => $row->kodePoliAsuransi,
                    "namapoli" => $row->namaPoliAsuransi,
                    "terlaksana" => $row->status_operasi === "Tunggu" ? 0 : 1,
                    "nopeserta" => $row->no_kartu,
                    "lastupdate" => $this->convert_date_to_mil($row->updated_at)
                ]);
            }

            $data = [
                    "metadata" => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                    "response" =>  [
                        "list" => $jadwal
                    ]
                ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metadata'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat jadwal ok!.",
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

    public function jadwalOkPasien(Request $request)
    {
        try {
            $nopeserta = $request->input('nopeserta');

            $data = DB::table('rs_permintaan_operasi as a')
                    ->select(
                        "a.no_permintaan",
                        "a.tgl_mulai",
                        "c.nama_tindakan",
                        "d.kodePoliAsuransi",
                        "d.namaPoliAsuransi",
                        "e.no_kartu",
                        "a.updated_at"
                    )
                    ->leftJoin('rs_detail_permintaan_operasi as b', 'a.no_permintaan', '=', 'b.no_permintaan')
                    ->leftJoin('rs_tindakan_kamar as c', 'b.kode_tindakan', '=', 'c.tindakan_kamar_id')
                    ->leftJoin('rs_mapping_poli_asuransi as d', 'd.kodePoli', '=', 'c.kdPoli')
                    ->leftJoin('rs_pasien as e', 'e.pasien_id', '=', 'a.pasien_id')
                    ->where('e.no_kartu', $nopeserta)
                    ->get();

            $jadwal = [];

            foreach($data As $row) {
                array_push($jadwal, [
                    "kodebooking" => $row->no_permintaan,
                    "tanggaloperasi" => $row->tgl_mulai,
                    "jenistindakan" => $row->nama_tindakan,
                    "kodepoli" => $row->kodePoliAsuransi,
                    "namapoli" => $row->namaPoliAsuransi,
                    "terlaksana" => 1
                ]);
            }

            $data = [
                    "metadata" => [
                        "code" => 200,
                        "message" => "Ok"
                    ],
                    "response" =>  [
                        "list" => $jadwal
                    ]
                ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metadata'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
                        "code" => 201,
                        "message" => "Gagal"
                    ],
                    'response'        => "Gagal memuat jadwal ok!.",
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

    public function getAntrianFarmasi(Request $request)
    {
        try {

            $kodeBooking = $request->input("kodebooking");

            $cekRacikan = DB::table('rs_antrian as a')
                    ->Join('rs_resep_online as b', 'a.strukCode', '=', 'b.strukCode')
                    ->Join('rs_resep_item as c', 'b.kode_transaksi', '=', 'c.kode_transaksi')
                    ->where('a.kodeBooking', $kodeBooking)
                    ->where('c.status_aktif', 1)
                    ->where('c.jml_R', '>', 0)
                    ->exists();

            $noAntrian = DB::table('rs_antrian as a')
                    ->where('a.kodeBooking', $kodeBooking)
                    ->value('a.noAntrian');
						
            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response"=> [
                    "jenisresep" =>  $cekRacikan ? "Racikan" : "Non Racikan",
                    "nomorantrean" => $noAntrian,
                    "keterangan" => ""
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metadata'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
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

    public function getStatusAntrianFarmasi(Request $request)
    {
        try {

            $kodeBooking = $request->input("kodebooking");

            $regDate= DB::table('rs_antrian as a')
                    ->where('a.kodeBooking', $kodeBooking)
                    ->value('a.regDate');

            $cekRacikan = DB::table('rs_antrian as a')
                    ->Join('rs_resep_online as b', 'a.strukCode', '=', 'b.strukCode')
                    ->Join('rs_resep_item as c', 'b.kode_transaksi', '=', 'c.kode_transaksi')
                    ->where('a.kodeBooking', $kodeBooking)
                    ->where('c.jml_R', '>', 0)
                    ->exists();


            $jml = DB::table('rs_antrian as a')
                    ->Join('rs_resep_online as b', 'a.strukCode', '=', 'b.strukCode')
                    ->Join('rs_resep_item as c', 'b.kode_transaksi', '=', 'c.kode_transaksi')
                    ->where('c.status_aktif', 1)
                    ->whereDate('c.created_at', $regDate)
                    ->groupBy('c.strukCode')
                    ->count();

            $sisa = DB::table('rs_antrian as a')
                    ->Join('rs_resep_online as b', 'a.strukCode', '=', 'b.strukCode')
                    ->Join('rs_resep_item as c', 'b.kode_transaksi', '=', 'c.kode_transaksi')
                    ->where('c.status_aktif', 1)
                    ->whereDate('c.created_at', $regDate)
                    ->groupBy('c.strukCode')
                    ->where('c.isSerahkan', 0)
                    ->count();
                

            $data = [
                "metadata" => [
                    "code" => 200,
                    "message" => "Ok"
                ],
                "response"=> [
                    "jenisresep" => $cekRacikan ? "Racikan" : "Non Racikan",
                    "totalantrean"  => $jml,
                    "sisaantrean"  => $jml - $sisa,
                    "antreanpanggil" => $sisa,
                    "keterangan" => ""
                ],
            ];

            if(sizeof($data["metadata"]) > 0) {   
                return response()->json([
                    'metadata'    => $data["metadata"],
                    'response'    => $data["response"],
                ], 200);
            }else{
                return response()->json([
                    'metadata'    => [
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

    public function getAntrianManual()
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
				$kodeBooking = $this->get_struk_no($tanggalperiksa);
                $jam = explode('-', $jampraktek);

                // $poliId = MappingPoli::from("rs_mapping_poli_asuransi as a")
                //         ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                //         ->where("a.kodePoliAsuransi", $kodepoli)
                //         ->value("a.kodePoli");

                // $dokterId = MappingDokter::from("rs_mapping_dokter_asuransi as a")
                //         ->leftJoin("rs_dokter as b", "a.kodeDokter", '=', 'b.dokter_id')
                //         ->where("a.kodeDokterAsuransi", $kodedokter)
                //         ->value("a.kodeDokter");

                $cek = Antrian::from("rs_counter_antrian as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->leftJoin("rs_mapping_poli_asuransi as c", "a.kodePoli", '=', 'c.kodePoli')
                        ->where("c.kodePoli", $kodepoli)
                        ->where("a.bookingDate", $tanggalperiksa)
                        ->where("a.noKartu", $nomorkartu)
                        ->where("a.isCancel", 0)
                        ->exists();

                if($cek) {
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Nomor Antrean Hanya Dapat Diambil 1 Kali Pada Tanggal Yang Sama",
						],
					], 201);
                }

                $sttPoli = MappingPoli::from("rs_mapping_poli_asuransi as a")
                        ->leftJoin("rs_poli as b", "a.kodePoli", '=', 'b.poli_id')
                        ->where("a.kodePoli", $kodepoli)
                        ->where("b.status_aktif", 0)
                        ->exists();

                if($sttPoli) {
                    return response()->json([
                        "metadata" => [
                            "code" => 201,
                            "message" => "Pendaftaran ke Poli Ini Sedang Tutup",
                        ],
                    ], 201);
                }

                $cekJadwal = JadwalDokter::where('poli_id', $kodepoli)
								->where('dokter_id', $kodedokter)
								// ->where('buka', $jam[0])
								// ->where('tutup', $jam[1])
								->exists();

                if(!$cekJadwal) {
                    return response()->json([
                        "metadata" => [
                            "code" => 201,
                            "message" => "Jadwal Dokter Tersebut Belum Tersedia, Silahkan Reschedule Tanggal dan Jam Praktek Lainnya",
                        ],
                    ], 201);
                }

                $cekPasien = DB::table('rs_pasien')
                            ->where('pasien_id', $norm)
                            ->exists();

                if(!$cekPasien) {
                    return response()->json([
                        "metadata" => [
                            "code" => 202,
                            "message" => "Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru",
                        ],
                    ], 202);
                }

                if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $tanggalperiksa)){
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd",
						],
					], 201);
                }

                if (strtotime($tanggalperiksa) < strtotime($dateNow->toDateString())) {
                    return response()->json([
						"metadata" => [
							"code" => 201,
							"message" => "Tanggal Periksa Tidak Berlaku",
						],
					], 201);
                }

                $noAntrian = $this->counting($kodepoli, $kodedokter, $tanggalperiksa)->getData();

                $sisa = Antrian::from("rs_counter_antrian as a")
                                        ->where("a.bookingDate", $tanggalperiksa)
                                        ->where("a.jamPraktek", $jampraktek)
                                        ->where("a.kodePoli", $kodepoli)
                                        ->where("a.kodeDokter", $kodedokter)
                                        ->where("a.isCall", 0)
                                        ->count();

				$request = [
					"kodeBooking" => $kodeBooking,
					"kodePoli" => $kodepoli,
					"kodeDokter" => $kodedokter,
					"noKartu" => $nomorkartu,
					"nik" => $nik,
					"noRm" => $norm,
					"noHp" => $nohp,
					"nomorAntrian" => $noAntrian->noAntri,
					"angkaAntrean" => $noAntrian->count,
					"isJkn" => 0,
					"bookingDate" => $tanggalperiksa,
					"jamPraktek" => $jampraktek,
					"noReferensi" => $nomorreferensi,
					"jenisKunjungan" => $jeniskunjungan,
					"isCall" => 0,
					"isCancel" => 0,
					"isCallOn" => 0,
					"isCheckIn" => 0,
					"isOnsite" => 0,
                    "estimasidilayani" => $this->count_time($tanggalperiksa, $jam[0], $noAntrian->count),
                    // "estimasiDilayani" => $this->count_wait_time($sisa),
					"createdAt" => $dateNow->toDateTimeString(),
					"updatedAt" => $dateNow->toDateTimeString()
				];
				
				$jadwal = JadwalDokter::where('poli_id', $kodepoli)
								->where('dokter_id', $kodedokter)
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
                                    ->where("a.kodePoli", $kodepoli)
                                    ->where("a.kodeDokter", $kodedokter);


                $JmlJkn = Antrian::from("rs_counter_antrian as a")
                                        ->where("a.bookingDate", $tanggalperiksa)
                                        ->where("a.jamPraktek", $jampraktek)
                                        ->where("a.kodePoli", $kodepoli)
                                        ->where("a.kodeDokter", $kodedokter)
                                        ->where("a.isJkn", 1);

                $JmlNonJkn = Antrian::from("rs_counter_antrian as a")
                                        ->where("a.bookingDate", $tanggalperiksa)
                                        ->where("a.jamPraktek", $jampraktek)
                                        ->where("a.kodePoli", $kodepoli)
                                        ->where("a.kodeDokter", $kodedokter)
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
                                        "estimasidilayani" => intval($response->first()->estimasiDilayani),
                                        "sisakuotajkn" =>  intval($jadwal->kuotaJkn - $JmlJkn->count()),
                                        "kuotajkn" =>  intval($jadwal->kuotaJkn),
                                        "sisakuotanonjkn" =>  intval($jadwal->kuotaNonJkn - $JmlNonJkn->count()),
                                        "kuotanonjkn" =>  intval($jadwal->kuotaNonJkn),
                                        "keterangan" => "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
                                    ],
								];

						return response()->json([
                            'metadata'    => $data["metadata"],
                            'response'        => $data["response"]
						], 200);
				}else{
						return response()->json([
                            'metadata'    => [
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
}
