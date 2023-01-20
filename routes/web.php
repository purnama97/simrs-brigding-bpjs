<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('BPJS', 'BridgingBpjs@index');

$router->group(
    ['prefix' => 'api/v1/'],
    function () use ($router) {
        // APLICARE
        $router->get('aplicaresws/sync', 'AplicareController@syncKamar');
        $router->get('aplicaresws/ref/kelas', 'AplicareController@refKelas');
        $router->get('aplicaresws/bed/{kodePpk}/{start}/{limit}', 'AplicareController@bedGet');
        $router->post('aplicaresws/bed/{kodePpk}', 'AplicareController@bedCreate');
        $router->put('aplicaresws/bed/{kodePpk}', 'AplicareController@bedUpdate');
        $router->delete('aplicaresws/bed/{kodePpk}/{kodeKelas}/{kodeRuangan}', 'AplicareController@bedDelete');

        //ANTREAN ONLINE WS RS AUTH
        $router->get('antrean/auth', 'AuthController@authenticate');

        $router->post('inacbg/eKlaim/testdektrip', 'EKlaimController@testDekrip');
        $router->post('inacbg/eklaim/testenkrip', 'EKlaimController@testEnkrip');
    }
);

$router->group(
    ['prefix' => 'api/v1/', 'middleware' => 'auth'],
    function () use ($router) {

        // REFERENSI
        $router->get('referensi/diagnosa/{diagnosa}', 'ReferensiBpjsController@diagnosa');
        $router->get('referensi/poli/{poli}', 'ReferensiBpjsController@poli');
        $router->get('referensi/faskes/{kode_faskes}/{jenis_faskes}', 'ReferensiBpjsController@faskes');
        $router->get('referensi/dokter/pelayanan/{jenis_pelayanan}/tglpelayanan/{tgl_pelayanan}/spesialis/{kode_spesialis}', 'ReferensiBpjsController@dokterDpjp');
        $router->get('referensi/propinsi', 'ReferensiBpjsController@propinsi');
        $router->get('referensi/kabupaten/propinsi/{kabupaten}', 'ReferensiBpjsController@kabupaten');
        $router->get('referensi/kecamatan/kabupaten/{kecamatan}', 'ReferensiBpjsController@kecamatan');
        $router->get('referensi/diagnosaprb', 'ReferensiBpjsController@diagnosaPRB');
        $router->get('referensi/obatprb/{namaObat}', 'ReferensiBpjsController@dataObat');

        $router->get('referensi/prosedure/{prosedure}', 'ReferensiBpjsController@prosedur');
        $router->get('referensi/kelasrawat', 'ReferensiBpjsController@kelasRawat');
        $router->get('referensi/dokter/{dokter}', 'ReferensiBpjsController@dokter');
        $router->get('referensi/spesialistik', 'ReferensiBpjsController@spesialistik');
        $router->get('referensi/ruangrawat', 'ReferensiBpjsController@ruangrawat');
        $router->get('referensi/carakeluar', 'ReferensiBpjsController@carakeluar');
        $router->get('referensi/pascapulang', 'ReferensiBpjsController@pascapulang');

        // PESERTA
        $router->get('peserta/noKartu/{noKartu}/tglsep/{tglPelayananSEP}','PesertaController@getByNoKartu');
        $router->get('peserta/nik/{nik}/tglsep/{tglPelayananSEP}','PesertaController@getByNIK');

        // SEP
        $router->post('vclaim/sep','SEPController@insertSEP');
        $router->put('vclaim/sep','SEPController@updateSEP');
        $router->delete('vclaim/sep/nosep/{noSEP}','SEPController@deleteSEP');
        $router->post('vclaim/sep/list','SEPController@listSEP');
        $router->get('vclaim/sep/detail/nosep/{noSEP}','SEPController@detailSEP');
        $router->get('vclaim/sep/nosep/{noSEP}','SEPController@cariSEP');
        $router->get('vclaim/sep/jasaraharja/suplesi/nokartu/{noKartu}/tglpelayanan/{tglPelayananSEP}','SEPController@suplesiJasaRaharja');
        $router->get('vclaim/sep/Kllinduk/list/nokartu/{noKartu}','SEPController@dataIndukKLL');
        $router->post('vclaim/sep/pengajuan', 'SEPController@pengajuanSEP');
        $router->post('vclaim/sep/aproval', 'SEPController@approvalPenjaminanSep');
        $router->put('vclaim/sep/updtglplg', 'SEPController@updateTglPlg');
        $router->get('vclaim/sep/updtglplg/list/bulan/{bulan}/tahun/{tahun}/{filter}', 'SEPController@listUpdateTglPlg');
        $router->get('vclaim/sep/internal/nosep/{noSEP}','SEPController@cariSEPInternal');
        $router->post('vclaim/sep/internal/delete','SEPController@deleteSepInternal');
        $router->get('vclaim/sep/inacbg/nosep/{noSEP}','SEPController@inacbgSEP');

        // $router->get('BPJS/VClaim/SEP','SEPController@listSEP');
        
        // RUJUKAN
        $router->get('vclaim/rujukan/norujukan/{noRujukan}','RujukanController@cariByNoRujukan');
        $router->get('vclaim/rujukan/RS/norujukan/{noRujukan}','RujukanController@cariByNoRujukan');
        $router->get('vclaim/rujukan/nokartu/{noKartu}','RujukanController@cariByNoKartu');
        $router->get('vclaim/rujukan/{searchBy}/list/nokartu/{noKartu}','RujukanController@cariByListNoKartu');
        $router->get('vclaim/rujukan/norujukan/{noRujukan}','RujukanController@cariByNoRujukan');
        $router->get('vclaim/rujukan/nokartu/{noKartu}','RujukanController@cariByNoKartu');
        $router->get('vclaim/rujukan/list/nokartu/{noKartu}','RujukanController@cariByListNoKartu');
        $router->post('vclaim/rujukan','RujukanController@insertRujukan');
        $router->put('vclaim/rujukan','RujukanController@updateRujukan');
        $router->post('vclaim/rujukan/khusus','RujukanController@insertRujukanKhusus');
        $router->put('vclaim/rujukan/khusus','RujukanController@deleteRujukanKhusus');
        $router->get('vclaim/rujukan/khusus/list/bulan/{bulan}/tahun/{tahun}','RujukanController@cariRujukanKhusus');
        $router->get('vclaim/rujukan/listspesialistik/ppkrujukan/{kodePPK}/tglrujukan/{tglRujuk}','RujukanController@spesialistikRujukan');
        $router->get('vclaim/rujukan/listsarana/ppkrujukan/{kodePPK}','RujukanController@saranaRujukan');
        $router->get('vclaim/rujukan/keluar/list/tglmulai/{tglMulai}/tglakhir/{tglAkhir}','RujukanController@getRujukKeluar');
        $router->get('vclaim/rujukan/keluar/norujukan/{noRujukan}','RujukanController@cariRujukKeluar');
        $router->delete('vclaim/rujukan/norujukan/{noRujukan}','RujukanController@deleteRujukan');

        // PRB
        $router->post('vclaim/prb','PRBController@insertPRB');
        $router->put('vclaim/prb','PRBController@updatePRB');
        $router->delete('vclaim/prb/nosrb/{noSrb}/nosep/{noSep}','PRBController@deletePRB');
        $router->get('vclaim/prb/nosrb/{noSRB}/nosep/{noSEP}','PRBController@cariNomorSRB');
        $router->get('vclaim/prb/tglmulai/{tglAwal}/tglakhir/{tglAkhir}','PRBController@cariTanggalSRB');

        // LPK
        $router->get('BPJS/VClaim/ref/dokter/{namaDokter}', 'LPKController@getDokterLPK');
        $router->post('BPJS/VClaim/LPK','LPKController@insertLPK');
        $router->put('BPJS/VClaim/LPK','LPKController@updateLPK');
        $router->delete('BPJS/VClaim/LPK','LPKController@deleteLPK');
        $router->get('BPJS/VClaim/LPK/tglMasuk/{tglMasuk}/jnsPelayanan/{jnsPelayanan}','LPKController@cariLPK');

        // RENCANA KONTROL
        $router->post('vclaim/rencanakontrol','RencanaKontrolController@insertRencanaKontrol');
        $router->put('vclaim/rencanakontrol','RencanaKontrolController@updateRencanaKontrol');
        $router->delete('vclaim/rencanakontrol/nosuratkontrol/{noSurat}','RencanaKontrolController@deleteRencanaKontrol');
        $router->post('vclaim/rencanakontrol/spri','RencanaKontrolController@insertSPRI');
        $router->put('vclaim/rencanakontrol/spri','RencanaKontrolController@updateSPRI');
        $router->get('vclaim/rencanakontrol/nosep/{noSEP}','RencanaKontrolController@cariSEP');
        $router->get('vclaim/rencanakontrol/detail/{noSurat}','RencanaKontrolController@detailKontrol');
        $router->get('vclaim/rencanakontrol/nosuratkontrol/{noSurat}','RencanaKontrolController@cariNoSuratKontrol');
        $router->get('vclaim/rencanakontrol/listrencanakontrol/tglawal/{tglAwal}/tglakhir/{tglAkhir}/filter/{filter}','RencanaKontrolController@dataNoSuratKontrol');
        $router->get('vclaim/rencanakontrol/listspesialistik/jnskontrol/{jnsKontrol}/nomor/{nomor}/tglrencanakontrol/{tglKontrol}', 'RencanaKontrolController@poliSpesialistik');
        $router->get('vclaim/rencanakontrol/jadwalpraktekdokter/jnskontrol/{jnsKontrol}/kdpoli/{kdPoli}/tglrencanakontrol/{tglRencanaKontrol}','RencanaKontrolController@dokterKontrol');
         

        // MONITORING
        $router->get('vclaim/monitoring/kunjungan/tanggal/{tglSep}/jnspelayanan/{jnsPelayanan}','MonitoringController@dataKunjungan');
        $router->get('vclaim/monitoring/klaim/tanggal/{tglPulang}/jnspelayanan/{jnsPelayanan}/status/{statusKlaim}','MonitoringController@dataKlaim');
        $router->get('vclaim/monitoring/historipelayanan/nokartu/{noKartu}/tglmulai/{tglAwal}/tglakhir/{tglAkhir}','MonitoringController@historyPelayanan');
        $router->get('vclaim/monitoring/jasaraharja/jnspelayanan/{jnsPelayanan}/tglmulai/{tglMulai}/tglakhir/{tglAkhir}','MonitoringController@dataKlaimJasaRaharja');

        // // APLICARE
        // $router->get('aplicaresws/ref/kelas', 'AplicareController@refKelas');
        // $router->get('aplicaresws/bed/{kodePpk}/{start}/{limit}', 'AplicareController@bedGet');
        // $router->post('aplicaresws/bed/{kodePpk}', 'AplicareController@bedCreate');
        // $router->put('aplicaresws/bed/{kodePpk}', 'AplicareController@bedUpdate');
        // $router->delete('aplicaresws/bed/{kodePpk}/{kodeKelas}/{kodeRuangan}', 'AplicareController@bedDelete');

        // ANTREAN WS BPJS
        $router->get('antrean/ref/poli', 'WS_BPJS_AntreanController@getPoli');
        $router->get('antrean/ref/dokter', 'WS_BPJS_AntreanController@getDokter');
        $router->get('antrean/jadwaldokter/kodepoli/{kodePoli}/tanggal/{tglPelayanan}', 'WS_BPJS_AntreanController@getJadwalDokter');
        $router->put('antrean/jadwaldokter', 'WS_BPJS_AntreanController@updateJadwalDokter');
        $router->post('antrean', 'WS_BPJS_AntreanController@addAntrian');
        $router->put('antrean', 'WS_BPJS_AntreanController@updateWaktuAntrian');
        $router->post('antrean/batal', 'WS_BPJS_AntreanController@batalAntrian');
        $router->get('antrean/kodebooking/{kodeBooking}', 'WS_BPJS_AntreanController@waktuTasks');
        $router->get('antrean/dashboard/waktutunggu/tanggal/{date}/waktu/{time}', 'WS_BPJS_AntreanController@getDashboardTgl');
        $router->get('antrean/dashboard/waktutunggu/bulan/{month}/tahun/{year}/waktu/{time}', 'WS_BPJS_AntreanController@getDashboardBln');

      
    }
);

$router->group(
    ['prefix' => 'api/v1/', 'middleware' => 'bpjs'],
    function () use ($router) {
        // ANTREAN WS RS
        $router->post('antrean/status', 'WS_RS_AntreanController@getStatus');
        $router->post('antrean/ambil', 'WS_RS_AntreanController@getAntrian');
        $router->post('antrean/sisa', 'WS_RS_AntreanController@getSisaAntrian');
        $router->post('antrean/batal', 'WS_RS_AntreanController@batalAntrian');
        $router->post('antrean/checkin', 'WS_RS_AntreanController@checkinAntrian');
        $router->post('antrean/infopasien', 'WS_RS_AntreanController@getInfoPasien');
        $router->post('antrean/jadwaloperasi/rs', 'WS_RS_AntreanController@jadwalOkRS');
        $router->post('antrean/jadwaloperasi/pasien', 'WS_RS_AntreanController@jadwalOkPasien');
        $router->post('antrean/farmasi/ambil', 'WS_RS_AntreanController@getAntrianFarmasi');
        $router->post('antrean/farmasi/status', 'WS_RS_AntreanController@getStatusAntrianFarmasi');
    }
);

    




