<?php

$fileStream = fopen(__DIR__ . '/abalone.data', 'r');

$data = [];
while($line = fgets($fileStream)) {
    // M,0.455,0.365,0.095,0.514,0.2245,0.101,0.15,15
    if(preg_match('/.(,[0-9.]+){7},(.+)/', $line, $match)) {
        list(
            $gender,
            $length,
            $diameter,
            $height,
            $wholeHeight,
            $shuckedWeight,
            $visceraWeight,
            $shellWeight,
            $age
        ) = explode(',', $line);

        $sex = null;
        switch ($gender) {
            case 'M':
                $sex = 2;
                break;
            case 'F':
                $sex = 1;
                break;
            default:
                $sex = 0;
                break;
        }

        $data[] = [
            $sex,
            floatval($length),
            floatval($diameter),
            floatval($height),
            floatval($wholeHeight),
            floatval($shuckedWeight),
            floatval($visceraWeight),
            floatval($shellWeight),
            intval($age)
        ];
    }
}

file_put_contents('serializedData.ser', serialize($data));
