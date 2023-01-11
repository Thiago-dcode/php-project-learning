<?php

declare(strict_types=1);

// Your Code




function app(string $file)
{
    $fileName = $file;


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
        $expense = array_reduce($data, function ($acc, $dt) {

            $num = parseToInt($dt['amount']);
            if ($num < 0)

                if ($num < 0) {
                    return $acc + $num;
                }
        }, 0);


        return ['income' => $calculation[0], 'expense' => $calculation[1], 'total' => $calculation[2]];
    };

    function printData(array $data)
    {


        return array_map(function ($dt) {

            $date = $dt['date'];
            $check = $dt['check'];
            $description = $dt['description'];
            $amount = $dt['amount'];
            $style = parseToInt($amount) > 0? "style ='color:green'": "style ='color:red'";
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
