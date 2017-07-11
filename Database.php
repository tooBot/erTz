<?php

class Database
{
    private static $db = null;
    private $mysqli;
    
    public static function getDB()
    {
        if (self::$db == null)
            self::$db = new DataBase();
        return self::$db;
    }
    
    public function __construct()
    {
        $this->mysqli = new mysqli("localhost", "root", "", "newbase");
        if ($this->mysqli->connect_error) {
            die("Connect Error (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error);
        }
    }
    
    public function getRecord($heroId, $time)
    {
        $resultArray = array();
        $sqlq        = "SELECT * FROM hidemonsters WHERE hero_id=$heroId";
        if ($result = $this->mysqli->query($sqlq)) {
            if ($result->num_rows == 0) {
                $sqlq   = "INSERT INTO hidemonsters(hero_id, caution_value, last_recalculation_time) VALUES ($heroId ,0 , $time)";
                $result = $this->mysqli->query($sqlq);
            }
        }
        while ($row = $result->fetch_assoc()) {
            array_push($resultArray, $row["caution_value"], $row["last_recalculation_time"]);
        }
        return $resultArray;
    }
    
    public function updateCautionValue($heroId, $value, $time)
    {
        // Здесь просто обновляем значение и время для соответствующего $heroId
        $sqlq = "UPDATE hidemonsters SET caution_value=$value, last_recalculation_time=$time WHERE hero_id=$heroId";
        $this->mysqli->query($sqlq);
    }
    
    public function __destruct()
    {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
?>