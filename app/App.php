<?php

declare(strict_types=1);

// Your Code

//get a number and concatenated it with a dollar sign and a comma.


function app(string $file)
{
    function formatNumber(float $num)
{
    //variable to fill with comma and dollar sign.
    $numFormated = '';
    //parse num to string and extract the decimal part.
    $numWithoutDecimals = explode('.', (string) $num)[0];
    $floatPart = explode('.', (string) $num)[1];
   
    //$transform the previous string to an array.
    $numToArray = str_split($numWithoutDecimals);
    //reversed to previous array
    $numToArrayReversed = array_reverse($numToArray);

    //variable iterator
    $i = 0;

    //loop through the reversed array

    foreach ($numToArrayReversed as $num) {

        //check if is negative to avoid cocatenated the minus
        //symbol, to cocatened it later.
        if ($num !== '-') $numFormated = $numFormated . $num;

        $i++;
        //each 3 loops concatened a dot
        if ($i % 3 === 0) {

            $numFormated = $numFormated . ',';
        }
    }

    //cocatenating the float part, the dollar sign, and in case
    //the num is negative, concatenated the minus symbol at the end.

    if ($num < 0) $numFormated = $floatPart . '.' . $numFormated . '$' . '-';
    else  $numFormated = $floatPart . '.' . $numFormated . '$';


    //parse to an array and reversing it
    $numFormatedToArray = array_reverse(str_split($numFormated));
    //empty the variable
    $numFormated = '';
    //concatenate to the empty variable each position
    //of the array.
    foreach ($numFormatedToArray as $num) $numFormated = $numFormated . $num;

    return  $numFormated;
}

    function parseToInt(string $str)
    {

        $number = '';

        for ($i = 0; $i < strlen($str); $i++) {

            if (is_numeric($str[$i]) || $str[$i] === '.' || $str[$i] === '-') {
                $number = $number . $str[$i];
            };
        }
        return (float) $number;
    }



    //Store each line of the file passed by argument into an array
    function getFile(string $file)
    {
        if (!is_file($file)) {
            echo 'this function only accept files';

            return;
        }
        $lines = array();

        //open the with reading permission

        $file = fopen($file, 'r');
        $i = 0;
        while (($line = fgetcsv($file)) !== false) {
            $i++;
            if ($i === 1) continue;
            array_push($lines, $line);
        }
        fclose($file);

        return  $lines;
    }


    function getData(array $data)
    {


        return array_map(function ($dt) {



            [$date, $check, $description, $amount] = $dt;


            return [

                'date' => $date,
                'check' => $check,
                'description' => $description,
                'amount' =>  $amount,
            ];
        }, $data);
    };

    function getIncomeAndExpenseAndTotal(array $data)
    {

        $calculation = array_reduce($data, function ($acc, $dt) {

            $sum = 0;
            $substract = 0;

            if (parseToInt($dt['amount']) > 0) {
                $sum = parseToInt($dt['amount']);
            } else $substract = parseToInt($dt['amount']);
            // var_dump($substract);
            return [$acc[0] + $sum, $acc[1] + $substract, $acc[0] + $acc[1]];
        }, [0, 0]);



        return ['income' => formatNumber($calculation[0]), 'expense' => formatNumber($calculation[1]), 'total' => formatNumber($calculation[2])];
    };

    function printData(array $data)
    {


        return array_map(function ($dt) {

            $date = $dt['date'];
            $check = $dt['check'];
            $description = $dt['description'];
            $amount = $dt['amount'];
            $style = parseToInt($amount) > 0 ? "style ='color:green'" : "style ='color:red'";
            return "<tr>
              <td>$date</td>
              <td>$check</td>
              <td>$description</td>
              <td $style >$amount</td>
          </tr>";
        }, $data);
    };
    $dataFromFile = getFile($file);

    $arrFromData = getData($dataFromFile);
    $dataToPrint = printData($arrFromData);
    $numbers = getIncomeAndExpenseAndTotal($arrFromData);

    return [$dataToPrint, $numbers];
}
