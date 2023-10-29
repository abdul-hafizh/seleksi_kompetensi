<?php
/*
*
* Class : Terbilang
* Spell quantity numbers in Indonesian or Malay Language
*
*
* author: huda m elmatsani
* 21 September 2004
* freeware
*
* example:
* $bilangan = new Terbilang;
* echo $bilangan -> eja(137);
* result: seratus tiga puluh tujuh
*
*
*/
Class Terbilang {

	function __construct() {
		$this->dasar = array(1=>'satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan');
		$this->angka = array(1000000000000,1000000000,1000000,1000,100,10,1);
		$this->satuan = array('triliun','milyar','juta','ribu','ratus','puluh','');
	}


	function eja($n) {
		$n = number_format($n, 0, '', '');
		$str = '';
		$i=0;
		while($n!=0){
			if(isset($this->angka[$i])) {
				if($this->angka[$i] > 0) {
					$count = (int)($n/$this->angka[$i]);
				} else {
					$count = 0;
				}
			} else {
				$count = 0;
			}
			if($count>=10) $str .= $this->eja($count). " ".$this->satuan[$i]." ";
			else if($count > 0 && $count < 10)
				$str .= $this->dasar[$count] . " ".$this->satuan[$i]." ";
			$n -= @$this->angka[$i] * $count;
			$i++;
		}
		$str = preg_replace("/satu puluh (\w+)/i","\\1 belas",$str);
		$str = preg_replace("/satu (ribu|ratus|puluh|belas)/i","se\\1",$str);
		return strtoupper($str);
	}

	function hari_indo($hari){

		switch($hari){
			case 'Sun':
			$hari_ini = "Minggu";
			break;

			case 'Mon':			
			$hari_ini = "Senin";
			break;

			case 'Tue':
			$hari_ini = "Selasa";
			break;

			case 'Wed':
			$hari_ini = "Rabu";
			break;

			case 'Thu':
			$hari_ini = "Kamis";
			break;

			case 'Fri':
			$hari_ini = "Jumat";
			break;

			case 'Sat':
			$hari_ini = "Sabtu";
			break;

			default:
			$hari_ini = "Tidak di ketahui";		
			break;
		}

		return $hari_ini;

	}

	function bulan_indo($bulan){
		
		switch($bulan){
			case '01':
			$bulan = "Januari";
			break;
			
			case '02':			
			$bulan = "Februari";
			break;
			
			case '03':
			$bulan = "Maret";
			break;
			
			case '04':
			$bulan = "April";
			break;
			
			case '05':
			$bulan = "Mei";
			break;
			
			case '06':
			$bulan = "Juni";
			break;
			
			case '07':
			$bulan = "Juli";
			break;

			case '08':
			$bulan = "Agustus";
			break;

			case '09':
			$bulan = "September";
			break;

			case '10':
			$bulan = "Oktober";
			break;

			case '11':
			$bulan = "November";
			break;

			case '12':
			$bulan = "Desember";
			break;
			
			default:
			$bulan = "Tidak di ketahui";		
			break;
		}
		
		return $bulan;
		
	}
}