<?php
/**
 * This class is a blueprint of all location in a specific country
 * The project developed with a target of Tanzania rep
 */
require_once"./Class/Bodaboda.php";
require_once"./Interfaces/iLocation.php";
class Location extends Bodaboda implements iLocation
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insertcountry($cc, $cn)
    {
        $sql = "INSERT INTO country(Country_Code, Country_Name) VALUES(?,?)";
        return $this->insert($sql, [$cc, $cn]);
    }
    public function insertregion($rn)
    {
        $sql = "INSERT INTO regions(Region_Name) VALUES(?)";
        return $this->insert($sql, [$rn]);
    }
    public function insertdistrict($id, $rn, $rid)
    {
        $sql = "INSERT INTO districts(District_ID, regionID, District_Name) VALUES(?,?,?)";
        try {
            return $this->insert($sql, [$id, $rid, $rn]);
        } catch (Exceptional $th) {
            return false;
        }
    }
    public function insertward($did,$rn,$id)
    {
        $sql = "INSERT INTO wards(Ward_ID, districtID, Ward_Name) VALUES(?,?,?)";
        return $this->insert($sql, [$id, $did, $rn]);
    }
    public function insertstreet($rid, $street_name)
    {
        $sql = "INSERT INTO streets(wardID, Street_Name) VALUES(?,?)";
        return $this->insert($sql, [$rid, $street_name]);
    }
    public function insertplaces($rid, $rn)
    {
        $sql = "INSERT INTO places(streetID, Place_Name) VALUES(?,?)";
        return $this->insert($sql, [$rid, $rn]);
    }
    public function getplace($street_code)
    {
        $sql = "SELECT * FROM places WHERE streetID = ?";
        return $this->selectall($sql, [$street_code]);
    }
    public function getstreet($ward_code)
    {
        $sql = "SELECT * FROM streets WHERE streetID = ?";
        return $this->selectall($sql, [$ward_code]);
    }
    public function getward($district_code)
    {
        $sql = "SELECT * FROM wards WHERE districtID = ?";
        return $this->selectall($sql, [$district_code]);
    }
    public function getdistrict($region_code)
    {
        $sql = "SELECT * FROM districts WHERE regionID = ?";
        return $this->selectall($sql, [$region_code]);
    }
    public function getregion($country_code)
    {
        $sql = "SELECT * FROM regions WHERE countryID = ?";
        return $this->selectall($sql, [$country_code]);
    }
    public function getWardId($k1, $k2, $k3, $k)
    {
        $q = "SELECT Street_ID as id FROM streets s
              INNER JOIN wards w ON s.wardID=w.Ward_ID
              INNER JOIN districts d ON w.districtID=d.District_ID
              WHERE w.Ward_ID=? AND d.District_ID=? AND d.regionID=? AND s.Street_Name=? LIMIT 1";
        $return = $this->select($q, [$k2, $k1, $k, $k3]);
        return $return['id'];
    }
}
return new Location;
