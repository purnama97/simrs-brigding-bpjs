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

        $router->get('BPJS/Referensi/prosedure/{prosedure}', 'ReferensiBpjsController@prosedur');
        $router->get('BPJS/Referensi/kelasrawat', 'ReferensiBpjsController@kelasRawat');
        $router->get('referensi/dokter/{dokter}', 'ReferensiBpjsController@dokter');
        $router->get('BPJS/Referensi/spesialistik', 'ReferensiBpjsController@spesialistik');
        $router->get('BPJS/Referensi/ruangrawat', 'ReferensiBpjsController@ruangrawat');
        $router->get('BPJS/Referensi/carakeluar', 'ReferensiBpjsController@carakeluar');
        $router->get('BPJS/Referensi/pascapulang', 'ReferensiBpjsController@pascapulang');

        // PESERTA
        $router->get('peserta/noKartu/{noKartu}/tglsep/{tglPelayananSEP}','PesertaController@getByNoKartu');
        $router->get('peserta/nik/{nik}/tglsep/{tglPelayananSEP}','PesertaController@getByNIK');

        // SEP
        $router->post('vclaim/sep','SEPController@insertSEP');
        $router->put('vclaim/sep','SEPController@updateSEP');
        $router->delete('vclaim/sep/nosep/{noSEP}','SEPController@deleteSEP');
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
        $router->get('vclaim/rujukan/{searchBy}/norujukan/{noRujukan}','RujukanController@cariByNoRujukan');
        $router->get('vclaim/rujukan/{searchBy}/nokartu/{noKartu}','RujukanController@cariByNoKartu');
        $router->get('vclaim/rujukan/{searchBy}/list/nokartu/{noKartu}','RujukanController@cariByListNoKartu');
        $router->post('vclaim/rujukan','RujukanController@insertRujukan');
        $router->put('vclaim/rujukan','RujukanController@updateRujukan');
        $router->post('vclaim/rujukan/khusus','RujukanController@insertRujukanKhusus');
        $router->put('vclaim/rujukan/khusus','RujukanController@deleteRujukanKhusus');
        $router->get('vclaim/rujukan/khusus/list/bulan/{bulan}/tahun/{tahun}','RujukanController@cariRujukanKhusus');
        $router->get('vclaim/rujukan/listspesialistik/ppkrujukan/{kodePPK}/tglrujukan/{tglRujuk}','RujukanController@spesialistikRujukan');
        $router->get('vclaim/rujukan/listsarana/ppkrujukan/{kodePPK}','RujukanController@saranaRujukan');
        $router->get('vclaim/rujukan/keluar/list/tglmulai/{tglMulai}/tglakhir/{tglAkhir}','RujukanController@getRujukKeluar');
        $router->get('vclaim/rujukan/norujukan/{noRujukan}','RujukanController@cariByNoRujukan');
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
        $router->get('vclaim/rencanakontrol/nosuratkontrol/{noSurat}','RencanaKontrolController@cariNoSuratKontrol');
        $router->get('vclaim/rencanakontrol/listrencanakontrol/tglawal/{tglAwal}/tglakhir/{tglAkhir}/filter/{filter}','RencanaKontrolController@dataNoSuratKontrol');
        $router->get('vclaim/rencanakontrol/listspesialistik/jnskontrol/{jnsKontrol}/nomor/{nomor}/tglrencanakontrol/{tglKontrol}', 'RencanaKontrolController@poliSpesialistik');
        $router->get('vclaim/rencanakontrol/jadwalpraktekdokter/jnskontrol/{jnsKontrol}/kdpoli/{kdPoli}/tglrencanakontrol/{tglRencanaKontrol}','RencanaKontrolController@dokterKontrol');
         

        // MONITORING
        $router->get('BPJS/VClaim/Monitoring/Kunjungan/Tanggal/{tglSep}/JnsPelayanan/{jnsPelayanan}','MonitoringController@dataKunjungan');
        $router->get('BPJS/VClaim/Monitoring/Klaim/Tanggal/{tglPulang}/JnsPelayanan/{jnsPelayanan}/Status/{statusKlaim}','MonitoringController@dataKlaim');
        $router->get('BPJS/VClaim/Monitoring/HistoriPelayanan/NoKartu/{noKartu}/tglMulai/{tglAwal}/tglAkhir/{tglAkhir}','MonitoringController@historyPelayanan');
        $router->get('BPJS/VClaim/Monitoring/JasaRaharja/JnsPelayanan/{jnsPelayanan}/tglMulai/{tglMulai}/tglAkhir/{tglAkhir}','MonitoringController@dataKlaimJasaRaharja');

        // // APLICARE
        // $router->get('aplicaresws/ref/kelas', 'AplicareController@refKelas');
        // $router->get('aplicaresws/bed/{kodePpk}/{start}/{limit}', 'AplicareController@bedGet');
        // $router->post('aplicaresws/bed/{kodePpk}', 'AplicareController@bedCreate');
        // $router->put('aplicaresws/bed/{kodePpk}', 'AplicareController@bedUpdate');
        // $router->delete('aplicaresws/bed/{kodePpk}/{kodeKelas}/{kodeRuangan}', 'AplicareController@bedDelete');

        // ANTREAN
        $router->get('BPJS/Antrean/ref/poli', 'AntreanController@getPoli');
        $router->get('BPJS/Antrean/ref/dokter', 'AntreanController@getDokter');
        $router->get('BPJS/Antrean/jadwaldokter/kodepoli/{kodePoli}/tanggal/{tglPelayanan}', 'AntreanController@getJadwalDokter');
        $router->put('BPJS/Antrean/jadwaldokter', 'AntreanController@updateJadwalDokter');
        $router->post('BPJS/Antrean/antrean', 'AntreanController@addAntrian');
        $router->put('BPJS/Antrean/antrean/updatewaktu', 'AntreanController@updateWaktuAntrian');
        $router->post('BPJS/Antrean/antrean/batal', 'AntreanController@batalAntrian');
        $router->get('BPJS/Antrean/antrean/getlisttask', 'AntreanController@waktuTasks');
        $router->get('BPJS/Antrean/dashboard/waktutunggu/tanggal/{date}/waktu/{time}', 'AntreanController@getDashboardTgl');
        $router->get('BPJS/Antrean/dashboard/waktutunggu/bulan/{month}/tahun/{year}/waktu/{time}', 'AntreanController@getDashboardBln');

        $router->post('InaCbg/EKlaim/testDektrip', 'EKlaimController@testDektrip');
        $router->post('InaCbg/EKlaim/testEnktrip', 'EKlaimController@testEnktrip');
    }
);



