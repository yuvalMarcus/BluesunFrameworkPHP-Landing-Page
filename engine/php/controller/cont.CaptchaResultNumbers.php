<?php

class CaptchaResultNumbers {

    public function make(array &$data): string {

        $min_number = 1;
        $max_number = 15;

        $random_number1 = mt_rand($min_number, $max_number);
        $random_number2 = mt_rand($min_number, $max_number);


        $captchaResult = $random_number1 . ' + ' . $random_number2 . ' = ';

        \Session::make('captchaResult', $random_number1 + $random_number2);

        return $captchaResult;
    }

}
