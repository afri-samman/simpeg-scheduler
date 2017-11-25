<?php
function test($var){
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}
function post_sync($action, $data){
	global $CI;
	$curl = $CI->curl->simple_post(host_server($action), $data);
	//test($curl);
	$response = json_decode($curl);
	return $response;
}
function host_server($action){
	//http://dashboard-01.dev.bantenprov.go.id/api/kenaikan-pangkat-otomatis-pegawai/v1/
	$host_server = 'http://dashboard-01.dev.bantenprov.go.id/api/'.$action;
	//$host_server = 'http://localhost/erapor_server/sync/'.$action;
	return $host_server;
}