<?php 

    function toJadenCase($string) 
    {
     
        // $result = ucwords(strtolower($string));
        // return $result;

        // $splitArray = explode(" ", $string);

        // for ($i=0; $i < $splitArray; $i++) { 
            
        //     $result_two = ucfirst($splitArray[$i]);
        // }
        // return implode(" ", $result_two);
    }


    function dont_give_me_five($start, $end) {

        $result = 0;
        for ($i = $start; $i <= $end; $i++) { 
            # code...
            if(strpos($i, '5') === false)
                $result++;

            if(str_contains($i, 5) === false)
                $result++;
        }

        return $result;
    }

    // isValidIP("139.17.57.155");

    function isValidIP(string $str): bool
    {
        $str = trim($str);
        // TODO
        if($str !== ""){

            $rule1 = explode($str, ".");

            print_r($rule1);

            foreach ($rule1 as $item) {

                if(is_numeric($item)) return false;
                
                if(count($rule1) != 4 && $item != "") return false;

                if($item >= 0 && $item <= 255){
                    return true;
                }else{
                    return false;
                }
            }   
        }
        

        return true;
    }
    // solution([1, 2, 10, 50, 5]);
    function solution($nums) {


        $samp = sort($nums);

        if($nums == null)
        {
            return [];
        }else if($nums === []){
            return [];
        }

        print_r($nums);
        return $samp;
    }


    function odd_or_even(array $a): string {
  
        if($a === [0]) return "even";

        if(!empty($a)){
             $sum = array_sum($a);

            while($value = $sum){
                if($value % 2 === 0){
                    return "even";
                break;

                }else{
                    return "odd";
                break;
                }
            }
        }else{
            return "even";
        }
       

        return "odd";

    }

    likes("Alex", "Jacob", "Mark", "Max");

    function likes($names) {


        if (empty($names)) {
            return "no one likes this";
        }
         else {
        if (count($names) == 1) {
            return $names[0] . " likes this";
        }
        elseif(count($names) == 2) {
            return $names[0] . " and " . $names[1] . " like this";
        }
        elseif(count($names) == 3) {
            return $names[0]. ", " . $names[1] . " and ". $names[2] . " like this";
        }else {
            $l = count($names) - 2;
            return $names[0] . ", " . $names[1] . " and " . $l . " others like this";
        }
    }
}

?>