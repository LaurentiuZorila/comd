<?php
function example($string) {
    $array = str_split($string);
    $newArray = [];
    $duplicates = [];

    foreach ($array as $k => $v) {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] == $array[$i + 1]) {
                $array[$i] = $array[$i] * 2;
                $arrDif[] = $array[$i + 1];
                unset($array[$i + 1]);
                $newArray = $array;
                $newArray = array_values($newArray);
            }
        }
    }

    foreach (array_count_values($newArray) as $count) {
        if ($count > 1) {
            $duplicates = [1];
        }
    }

    if (count($duplicates) > 0) {
        foreach ($newArray as $k => $v) {
            for ($i = 0; $i < count($newArray); $i++) {
                if ($newArray[$i] == $newArray[$i + 1]) {
                    $newArray[$i] = $newArray[$i] * 2;
                    unset($newArray[$i + 1]);
                    $newArray = array_values($newArray);
                }
            }
        }
    }
    print_r($newArray);
}
$string = '112344577883244';
example($string);
