<?php
interface iLocation{
    public function insertcountry($country, $country_code);
    public function insertregion($region_name);
    public function insertdistrict($id, $region_code, $district_name);
    public function insertward($id, $district_code, $district_name);
    public function insertstreet($ward_code, $ward_name);
    public function insertplaces($ward_code, $place_name);
    public function getplace($ward_code);
    public function getstreet($ward_code);
    public function getward($district_code);
    public function getdistrict($region_code);
    public function getregion($country_code);
}