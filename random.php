<?php
    //http://www.lost-in-code.com/programming/php-code/php-random-string-with-numbers-and-letters/ on Mar 5 2011
   function genRandomString($length) {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyz";
    $str = '';
    
    for ($p = 0; $p < $length; $p++) {
    	$n=mt_rand(0,35);
        $str .= $characters[$n];
    }

    return $str;
    }
?> 
