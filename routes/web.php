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
    ['middleware' => 'auth'],
    function () use ($router) {
        $router->get('BPJS/Referensi/diagnosa/{diagnosa}', 'ReferensiBpjsController@diagnosa');
        $router->get('BPJS/Referensi/poli/{poli}', 'ReferensiBpjsController@poli');
        $router->get('BPJS/Referensi/faskes/{kode_faskes}/{jenis_faskes}', 'ReferensiBpjsController@faskes');
        $router->get('BPJS/Referensi/dokter/pelayanan/{jenis_pelayanan}/tglPelayanan/{tgl_pelayanan}/Spesialis/{kode_spesialis}', 'ReferensiBpjsController@dokterDpjp');
        $router->get('BPJS/Referensi/propinsi', 'ReferensiBpjsController@propinsi');
        $router->get('BPJS/Referensi/kabupaten/propinsi/{kabupaten}', 'ReferensiBpjsController@kabupaten');
        $router->get('BPJS/Referensi/kecamatan/kabupaten/{kecamatan}', 'ReferensiBpjsController@kecamatan');

              
        // PESERTA
        $router->get('BPJS/VClaim/Peserta/noKartu/{noKartu}/tglSEP/{tglPelayananSEP}','VClaimBpjsController@getByNoKartu');
        $router->get('BPJS/VClaim/Peserta/nik/{noKartu}/tglSEP/{tglPelayananSEP}','VClaimBpjsController@getByNIK');

        // SEP
        $router->post('BPJS/VClaim/SEP','VClaimBpjsController@insertSEP');
        $router->put('BPJS/VClaim/SEP','VClaimBpjsController@updateSEP');
        $router->delete('BPJS/VClaim/SEP','VClaimBpjsController@deleteSEP');
        $router->get('BPJS/VClaim/SEP','VClaimBpjsController@listSEP');
        $router->post('BPJS/VClaim/SEP/pengajuanSEP', 'VClaimBpjsController@pengajuanSEP');
        $router->post('BPJS/VClaim/SEP/aprovalSEP', 'VClaimBpjsController@approvalPenjaminanSep');
        $router->put('BPJS/VClaim/SEP/updtglplg', 'VClaimBpjsController@updateTglPlg');
        $router->get('BPJS/VClaim/SEP/integrasi_inacbg/{noSEP}','VClaimBpjsController@inacbgSEP');
        $router->get('BPJS/VClaim/SEP/JasaRaharja/Suplesi/{noKartu}/tglPelayanan/{tglPelayananSEP}','VClaimBpjsController@suplesiJasaRaharja');
        $router->get('BPJS/VClaim/SEP/KllInduk/List/{noKartu}','VClaimBpjsController@dataIndukKLL');

        // RUJUKAN
        $router->get('BPJS/VClaim/Rujukan/norujukan/{keyword}','VClaimBpjsController@cariByNoRujukan');
        $router->get('BPJS/VClaim/Rujukan/nokartu/{keyword}','VClaimBpjsController@cariByNoKartu');
        $router->get('BPJS/VClaim/Rujukan/{searchBy}/norujukan/{keyword}','VClaimBpjsController@cariByNoRujukan');
        $router->get('BPJS/VClaim/Rujukan/{searchBy}/nokartu/{keyword}','VClaimBpjsController@cariByNoKartu');


        $router->get('BPJS/VClaim/Monitoring/Kunjungan/Tanggal/{tglSep}/JnsPelayanan/{jnsPelayanan}','VClaimBpjsController@dataKunjungan');
    
        
        $router->post('BPJS/test_connection', 'VClaimBpjsController@test_connection');
        $router->get('BPJS/aplicare/kamar_inap', 'AplicareBpjs@get_kamar_inap');
        $router->get('BPJS/aplicare/kamar_inap', 'AplicareBpjs@get_kamar_inap');
    }
);
