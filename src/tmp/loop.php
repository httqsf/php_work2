<?php

//whileの使い方

// $i = 0;

// // while(true) {
// //     if($i <= 50){
// //     echo $i . PHP_EOL;
// //     $i += 10;
// //     // echo $i . PHP_EOL;
// //     }else{
// //         break;
// //     }
// // }

// while($i <= 50) {
//     echo $i . PHP_EOL;
//     $i += 10;
// }


//foreachの使い方

$a = 10;

$numbers = [$a, 2, 3, 4, ];

foreach ($numbers as $number){
    echo $number * 2 .PHP_EOL;
}


//foreach連想配列の使い方

// $currencies = [
//     'japan' => 'yen',
//     'us' => 'dollar',
//     'england' => 'pound',
// ];

// foreach ($currencies as $country => $currency){
//     echo $country . ':' . $currency . PHP_EOL;
// }
