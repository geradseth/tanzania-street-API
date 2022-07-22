<?php
class Bodaboda
{
    protected $dbc;
    public function __construct()
    {
        $this->dbc = new PDO(
            "mysql:host=localhost;dbname=tzstreets;charset=utf8",
            'root','',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    public function insert($q, $p=[])
    {
        try{
            $rs = $this->dbc->prepare($q);
            return $rs->execute($p);
        }
        catch(PDOException $e){
            return false;
        }
    }
    public function selectall($q, $p = [])
    {
        $rs = $this->dbc->prepare($q);
        $rs->execute($p);
        return $rs->fetchall();
    }
    public function select($q, $p = [])
    {
        $stm = $this->dbc->prepare($q);
        $stm->execute($p);
        return $stm->fetch();
    }
}
