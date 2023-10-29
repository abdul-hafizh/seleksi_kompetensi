<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Umum
{
	public function show_tanggal($input)
	{
		if(!empty($input)){
		if (date("G", strtotime($input)) > 0){
		return date("d F Y - G:i:s", strtotime($input));
		}
		else{
		return date("d F Y", strtotime($input));
		}
		}
		else{
		return "";
		}
	}
	
	public function unixtotime($input)
	{
		if(!empty($input)){
		$input = $input/1000;
		$date = date_create();
		date_timestamp_set($date, $input);
		return date_format($date, 'Y-m-d H:i:s');
		}
		else{
		return "";
		}
	}
	
	public function cetakuang($nominal, $currency)
	{
	if($nominal > 0){
	if($currency == "USD"){
	return "$".number_format($nominal, 2, '.', ',');
	}
	else if($currency == "IDR"){
	return "Rp".number_format($nominal, 2, ',', '.');
	}
	else{
	return number_format($nominal, 2, '.', ',');
	}
	}
	else{
	return "-";
	}
	}
	
	public function forbidden($input, $mode){
	if($mode == 'enkrip'){
	return str_replace(array('+', '/', '='), array('-', '_', '~'), $input);
	}
	else if($mode == 'dekrip'){
	return str_replace(array('-', '_', '~'), array('+', '/', '='), $input);
	}
	}
}