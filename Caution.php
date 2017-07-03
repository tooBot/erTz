<?php

class Caution 
{
    private $heroId;
    private $heroLevel;
    private $time;
    private $value;
    private $database;
     
    private static $config = array(
        array('valueFrom' => 25, 'levelDelta' => 4),
        array('valueFrom' => 50, 'levelDelta' => 3),
        array('valueFrom' => 75, 'levelDelta' => 2),
        array('valueFrom' => 100, 'levelDelta' => 0),
    );
     
    public function __construct($heroId, $heroLevel, $time)
    {
        $this->heroId = $heroId;
        $this->heroLevel = $heroLevel;
        $this->time = $time;
        $this->database = new Database();
        $this->value = $this->calculateCurrentValue($this->database->getRecord($heroId, $time));
    }
     
    public function calculateCurrentValue($record)
    {
        $value = 0;
        //получаем результирующий набор
        $result = $this->database->getRecord($this->heroId, $this->time);
        //получаем время последнего изменения и значение параметра
        while ($row = $result->fetch_assoc()) {
            $record = $row["last_recalculation_time"];
            $value = $row["caution_value"];
        }
        //пересчитываем параметр
        $value = $value - round(($this->time - $record)/60);
        //меньше 0 быть не может
        if ($value < 0){$value = 0;}
        //обновляем параметр в базе
        $this->database->updateCautionValue($this->heroId, $value, $this->time);
        return $value;
    }
     
    public function getValue()
    {
        return $this->value;
    }
     
    public function filterMonsterLevels($monsterLevels)
    {
        //наш массив уровней монстров
        $monsterLevels = array(10, 11, 12, 13, 14, 15, 16, 17, 18, 19);
        $levelsResult = array();
        //рассчет параметра в %
        $valuePercent = round(self::getValue() * 100 / 1500);
        
        //проверяем каждый параметр из нашего массива
        for($i = 0; $i < count(self::$config); $i++) 
        {
            //если меньше 25% - показываем все уровни
            if($valuePercent < self::$config[$i]['valueFrom'])
            {
                $levelsResult = $monsterLevels;
                return $levelsResult;
            }
            //проверяем для следующих промежутков
            elseif ($valuePercent >= self::$config[$i]['valueFrom'] and $valuePercent < self::$config[$i+1]['valueFrom'])
            {
                for($j =0; $j < count($monsterLevels); $j++)
                {            
                    //добавляем в результирующий массив уровни, которые отвечают нужным условиям               
                    if($monsterLevels[$j] > $this->heroLevel - self::$config[$i]['levelDelta'] and self::$config[$i]['levelDelta'] != 0)
                    {
                        array_push($levelsResult, $monsterLevels[$j]);  
                    }                       
                }
            return $levelsResult;               
            }
            else
            {
                //если 100% - возвращаем пустой массив уровней (не видим монстров)
                $levelsResult = array();
            }
        }       
        return $levelsResult;
    }
     
    public function kill($monsterLevel)
    {
        //берем каждый элемент из массива монстров
        for ($i=0, $size = count($monsterLevel); $i <$size; $i++)
        {
            //если уровень монстра меньше не более чем на:
            if ($this->heroLevel - $monsterLevel[i] + 3 < 10)
            {
                // увеличиваем параметр на нужное кол-во единиц
                $this->value += $this->heroLevel - $monsterLevel[$i] + 3;
            }
            else
            {
                //иначе увеличиваем на 10 единиц
                $this->value += 10;
            }   
        }
        //обновляем паарметр в базе
        $this->database->updateCautionValue($this->heroId, $this->value , $this->time); 
        return $this->value;
    }
}

?>