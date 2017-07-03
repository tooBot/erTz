<?php
require('caution.php');
require_once('database.php');
    $monsterLevels = array(10, 11, 12, 13, 14, 15, 16, 17, 18, 19);

    echo "<p><b> new parametr </b></p>";
    $caution = new Caution(1, 15, mktime(0, 0, 0, 7, 3, 2017));
    echo $caution->getValue();// Должно быть 0

    echo "<p><b> array of monsters </b></p>";
    print_r($caution->filterMonsterLevels($monsterLevels)); // Все уровни должны быть доступны
    echo "<br>_____________________________________________________________________________________________________<br>";
    // Чтобы догнать параметр до 25%
    $caution->kill(array(5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5));
    echo "<p><b> >25? </b></p>";
    echo $caution->getValue(); // Должно быть около 25%
    echo " ===390*100/1500=<b>26%</b>===";
    echo "<p><b>Filter if >25% </b></p>";
    print_r($caution->filterMonsterLevels($monsterLevels)); // Должны отфильтроваться по уровню - 4
    echo "<br>_____________________________________________________________________________________________________<br>";

    echo "<p><b> 3 hours later </b></p>";
    $caution = new Caution(1, 15, mktime(3, 0, 0, 7, 3, 2017)); // Прошло 3 часа
    echo $caution->getValue(); // Должно быть около 14%, если мы считаем, что в час уменьшается на 60ед.
    echo " ===210*100/1500=<b>14%</b>===";
    echo "<p><b>Filter after 3 hours</b></p>";
    print_r($caution->filterMonsterLevels($monsterLevels)); // Все уровни должны быть доступны
    echo "<br>_____________________________________________________________________________________________________<br>";
    echo "<p><b> 6.5 hours later </b></p>";
    $caution = new Caution(1, 15, mktime(6, 30, 0, 7, 3, 2017)); // Прошло 6.5	 часов
    echo $caution->getValue(); // Должно быть 0%*/
?>