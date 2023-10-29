<?php

  function date_db($dateval)
  {
    $timestamp = strtotime($dateval);
    if ($timestamp === FALSE) {
      $timestamp = strtotime(str_replace('/', '-', $dateval));
    }
    $date = date('Y-m-d', $timestamp);
    return $date;
  }

  function replace_comma($number){

    if (strpos($number, ',') !== false) {
      $number = str_replace(',', '.', str_replace('.', '', $number));
    }

    return $number;

  }

  function contains($string, array $array) {
      $count = 0;
      foreach($array as $value) {
          if (false !== stripos($string,$value)) {
              ++$count;
          };
      }
      return $count == count($array);
  }

  function errormessage($str){

    if(!empty($str)){
      return "<span class='help-block'>".$str."</p>";
    }

  }
  function default_submit_modal_button($url = ""){
    return "<a href='$url' class='btn btn-secondary' data-dismiss='modal' aria-label='Close'>Kembali</a> &nbsp; <input class='btn btn-info pull-right' type='submit' value='Simpan'>";
  }


  function todatetime($value){
    return date("Y-m-d h:s:i",strtotime($value));
  }

  function romanic_number($integer, $upcase = true)
  {
    $table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
    $return = '';
    while($integer > 0)
    {
      foreach($table as $rom=>$arb)
      {
        if($integer >= $arb)
        {
          $integer -= $arb;
          $return .= $rom;
          break;
        }
      }
    }
    return $return;
  }

  function tree(array $data, &$tree = array(), $level = 0) {    
    if (!isset($tree[$level])) $tree[$level] = array();

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $tree[$level][] = $key;
            tree($value, $tree, $level+1);
        }
        else {
            $tree[$level][] = $value;
        }
    }
  }

  function buttonsubmit($url,$back = "Kembali", $save = "Simpan"){
    $html = "<div class='row'>
    <div class='col-md-12'>    
      <a href='".site_url($url)."' target='_self' class='btn btn-secondary'>".$back."</a>
      <button type='submit' class='btn btn-info pull-right'>".$save."</button>    
    </div>
  </div>";
  return $html;
  }

  function buttonback($url,$back = "Kembali"){
    $html = "<div class='row'>
    <div class='col-md-12'>
        <a href='".site_url($url)."' target='_self' class='btn btn-secondary'>".$back."</a>    
    </div>
  </div>";
  return $html;
  }

  function createDateRangeArray($strDateFrom,$strDateTo)
  {
    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
      array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
      while ($iDateFrom<$iDateTo)
      {
          $iDateFrom+=86400; // add 24 hours
          array_push($aryRange,date('Y-m-d',$iDateFrom));
        }
      }
      return $aryRange;
  }
  
  function moneytoint($money = ""){

    $int = 0;

    if(!empty($money)){

      $int = str_replace(".", "", $money);
      $int = str_replace(",", ".", $int);

    }

    return $int;

  }
  
  function inttomoney($money){
    $int = number_format($money,2,",",".");

    return $int;
  }

  function array2csv(array &$array)
    {
      if (count($array) == 0) {
        return null;
      }
      ob_start();
      $df = fopen("php://output", 'w');
      foreach ($array as $row) {
      fputcsv($df, $row);
    }
    fclose($df);
    return ob_get_clean();
  }

  function urut($bulan, $tahun, $prefix, $inv, $id){
    $urut = str_repeat(0, 2-strlen($bulan)).$bulan;
    $urut .= (strlen($tahun) == 4) ? $tahun : str_repeat(0, 4-strlen($tahun)).$tahun;
    $urut .= $prefix;
    $urut .= str_repeat(0, 6-strlen($inv)).$inv;
    $urut .= str_repeat(0, 6-strlen($id)).$id;
    return $urut;
  }

  function urut_id($id,$maxdigit){
    $urut = str_repeat(0, $maxdigit-strlen($id)).$id;
    return $urut;
  }

  function month_select_box( $field_name = 'month' ,$current_val = "", $attr = "class='form-control'") {
    $month_options = '';
    for( $i = 1; $i <= 12; $i++ ) {
      $month_num = str_pad( $i, 2, 0, STR_PAD_LEFT );
      $month_name = date( 'F', mktime( 0, 0, 0, $i + 1, 0, 0 ) );
      $selected = ($current_val == $month_num) ? "selected" : "";
      $month_options .= '<option '.$selected.' value="' .$month_num . '">' . $month_name . '</option>';
    }
    return '<select '.$attr.' name="' . $field_name . '">' . $month_options . '</select>';
  }

  function getmonthname($month){
    return date("F",strtotime("1994-".$month."-01"));
  }

  function imgcheck($path,$imgname,$exception = "default.jpg"){
    $return = "uploads/default.jpg";
    if(!empty($imgname) && !empty($path) && file_exists($path."/".$imgname)){
      $return = $path."/".$imgname;
    }
    return $return;
  }

  function download_send_headers($filename) {
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
  }

  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }

  function convertDatetime_sqlsrv($format, $datetime){

    $datetime = explode(" ", $datetime);

    $tgl = explode("-", $datetime[0]);

    $waktu = explode(":", $datetime[1]);

    $tahun = $tgl[0];

    $bulan = $tgl[1];

    $hari = $tgl[2];

    $jam = $waktu[0];

    $menit = $waktu[1];

    $detik = explode(".", $waktu[2]);

    $detik = $detik[0];

    $hasil = mktime($jam, $menit, $detik, $bulan, $hari, $tahun);

    return date($format, $hasil);

  }

  function getTime_sqlsrv($datetime){

    $datetime = explode(" ", $datetime);

    $tgl = explode("-", $datetime[0]);

    $waktu = explode(":", $datetime[1]);

    $jam = $waktu[0];

    $menit = $waktu[1];

    $detik = explode(".", $waktu[2]);

    $detik = $detik[0];

    $hasil = $jam.":".$menit;

    return $hasil;

  }

  function getDate_sqlsrv($datetime){

    if(!empty($datetime)){

      $datetime = explode(" ", $datetime);

      $tgl = explode("-", $datetime[0]);

      $waktu = explode(":", $datetime[1]);

      $tahun = $tgl[0];

      $bulan = $tgl[1];

      $hari = $tgl[2];

      $hasil = $hari."-".$bulan."-".$tahun;

      return $hasil;

    }

  }
