<?php
ini_set('max_execution_time',0);
	$req = require_once("./Class/Location.php");
//Load all Csv files in folder csv
$regions = glob('./csv/*.csv');
$keys = array();
$array = array();

//Loop in Regions Array to work on each file
foreach($regions as $rg){
	$r=insertRegions($rg);
	if($r){
		if(insertDistrict($r, $rg)){
			if(insertWard(insertDistrict($r,$rg), $rg)){
				if(insertStreet(insertWard(insertDistrict($r, $rg),$rg), $rg)){
					if(insertPlaces(insertStreet(insertWard(insertDistrict($r,$rg),$rg),$rg), $rg))
						echo "It Works";
				}
			}
		}
	}
}

function insertRegions($fp){
	$f = explode('/', $fp);
	$r = explode('.', $f[2]);
	$rgn = $r[0];
	if($rgn!=='')
	global	$req; $req->insertregion($rgn);
		return $rgn;
}

function insertDistrict($region, $rg){
	global	$req;
	$f = csvtoarray($rg, ',');
	$labels = array_shift($f);
	foreach($labels as $label){
		$keys[] = $label;
	}
	$c = count($f)-1;
	$keys[] = 'id';
	for($i=0;$i<$c;$i++){
		$f[$i][] = $i;
	}
	$keys[1] = 'regioncode';
	$keys[3] = 'districtcode';
	for($j=0;$j<$c;$j++){
		$arr=array_combine($keys, $f[$j]);
		$array[$j] = array_change_key_case($arr, CASE_LOWER);
	}
	$ar = array();
	foreach($array as $key){
	  if(in_array(array($region=>$key['district']), $ar))
	    continue;
	  else{
	    $ar[] = array($region=>$key['district']);
	    $req->insertdistrict($key['districtcode'], $key['district'], $key['regioncode']);
	  	}
	  }
	return $key['district'];
}

// Function to convert CSV into associative array
function csvToArray($file, $delimiter) { 
  if (($handle = fopen($file, 'r')) !== FALSE) { 
    $i = 0; 
    while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) { 
      for ($j = 0; $j < count($lineArray); $j++) { 
        $arr[$i][$j] = $lineArray[$j]; 
      } 
      $i++; 
    } 
    fclose($handle); 
  } 
  return $arr; 
}

function insertWard($ds, $rg){
	global	$req;
	$f = csvtoarray($rg, ',');
	$labels = array_shift($f);
	foreach($labels as $label){
		$keys[] = $label;
	}
	$c = count($f)-1;
	$keys[] = 'id';
	for($i=0;$i<$c;$i++){
		$f[$i][] = $i;
	}
	$keys[3] = 'districtcode';
	$keys[5] = 'wardcode';
	for($j=0;$j<$c;$j++){
		$arr=array_combine($keys, $f[$j]);
		$array[$j] = array_change_key_case($arr, CASE_LOWER);
	}
	$ar = array();
	foreach($array as $key){
	  if(in_array(array($ds=>$key['ward']), $ar))
	    continue;
	  else{
	    $ar[] = array($ds=>$key['ward']);
	    $req->insertward($key['districtcode'],$key['ward'], $key['wardcode']);
	  }
	}
	return $key['ward'];
}

function insertStreet($wd, $rg){
	global	$req;
	$f = csvtoarray($rg, ',');
	$labels = array_shift($f);
	foreach($labels as $label){
		$keys[] = $label;
	}
	$c = count($f)-1;
	$keys[] = 'id';
	for($i=0;$i<$c;$i++){
		$f[$i][] = $i;
	}
	$keys[5] = 'wardcode';
	for($j=0;$j<$c;$j++){
		$arr=array_combine($keys, $f[$j]);
		$array[$j] = array_change_key_case($arr, CASE_LOWER);
	}
	$ar = array();
	foreach($array as $key){
	  if(in_array(array($wd=>$key['street']), $ar))
	    continue;
	  else{
	    $ar[] = array($wd=>$key['street']);
	    $req->insertstreet($key['wardcode'],$key['street']);
	  }
	}
	return $key['street'];
}

function insertPlaces($st, $rg){
	global	$req;
	$f = csvtoarray($rg, ',');
	$labels = array_shift($f);
	foreach($labels as $label){
		$keys[] = $label;
	}
	$c = count($f)-1;
	$keys[] = 'id';
	for($i=0;$i<$c;$i++){
		$f[$i][] = $i;
	}
	$keys[1] = 'regioncode';
	$keys[3] = 'districtcode';
	$keys[5] = 'wardcode';
	for($j=0;$j<$c;$j++){
		$arr=array_combine($keys, $f[$j]);
		$array[$j] = array_change_key_case($arr, CASE_LOWER);
	}
	$ar = array();
	array_change_key_case($array, CASE_LOWER);
	foreach($array as $key){
	  if(in_array(array($st,$key['places']), $ar))
	    continue;
	  else{
	    $ar[] = array($st,$key['places']);
		$pl = $req->getWardId($key['districtcode'], $key['wardcode'], $key['street'], $key['regioncode']);
	    $req->insertplaces($pl, $key['places']);
	  }
	}
	return $key['places'];
}