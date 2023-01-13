<?php

declare(strict_types=1);

// Your Code


//All functionallity of the program happen inside of this function, it accept a string
//which is the direccion of the file.

function app(string $file)
{

    //get a number and concatenated it with a dollar sign and a comma.
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

    //function that accept a string as argument which extract from it the numbers concatenated with symbols: "$2,345.45" => 2345.45
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

        

    //extract each line of the file passed by argument into an array
    function fileToArray(string $file)
    {

        if (!is_file($file)) {
            echo 'this function only accept files';

            return;
        }
        $lines = array();

        //open the file with reading permission

        $file = fopen($file, 'r');
        //iterator to avoid take the first line of the file, because is not
        //that data is not important
        $i = 0;
        //reading each line of the file, and pushing them to the empty array made previously
        while (($line = fgetcsv($file)) !== false) {
            $i++;
            if ($i === 1) continue;

            //each line is an array with each index is the data separated by commas on the csv file
            // data, 123, transacion =>  line= [data, 123, transaction]
            array_push($lines, $line);
        }
        fclose($file);
       
        return  $lines;
    }

    //accept as argument the previous array made by the csv file, and turn it into an array of associative arrays
    // for working with the data more easily    
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

    //Function that accept the previous array to do arithmetics operations with the numbers inside of it.
    function getIncomeAndExpenseAndTotal(array $data)
    {

        // using only one array reduce to sum all INCOMES, and substract all EXPENSES and last sum them to see the outcome.
        $calculation = array_reduce($data, function ($acc, $dt) {

            $sum = 0;
            $substract = 0;
            //using the previous function to parse the data from the CSV into numbers.
            if (parseToInt($dt['amount']) > 0) {
                $sum = parseToInt($dt['amount']);
            } else $substract = parseToInt($dt['amount']);
            // var_dump($substract);
            return [$acc[0] + $sum, $acc[1] + $substract, $acc[0] + $acc[1]];
        }, [0, 0]);


        //And return the data into an associative array to easily insert later on the html document
        return ['income' => formatNumber($calculation[0]), 'expense' => formatNumber($calculation[1]), 'total' => formatNumber($calculation[2])];
    };


    //Turn the array returned from getData function, into an array where each index are html elements with the data incrusted
    function printData(array $data)
    {


        return array_map(function ($dt) {

            $date = $dt['date'];
            $check = $dt['check'];
            $description = $dt['description'];
            $amount = $dt['amount'];
            //if the amount of the transaccion is positive style it with green color, otherwise with color red.
            $style = parseToInt($amount) > 0 ? "style ='color:green'" : "style ='color:red'";
            return "<tr>
              <td>$date</td>
              <td>$check</td>
              <td>$description</td>
              <td $style >$amount</td>
          </tr>";
        }, $data);
    };

    //calling all the function.
    $dataFromFile = fileToArray($file);

    $arrFromData = getData($dataFromFile);
    $dataToPrint = printData($arrFromData);
    $numbers = getIncomeAndExpenseAndTotal($arrFromData);

    return [$dataToPrint, $numbers];
}
