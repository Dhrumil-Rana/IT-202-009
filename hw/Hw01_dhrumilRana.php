<?php
//this is the newline function 
function newline()
{
 echo "<br>\n";
}

$increment = 1;

$arr1 = array(1,2,3,4,5,6,7,8,9,10);  // array to check 

$result = array(); //result array  

//for loop 
for($x= 0;$x < 10; $x += $increment)
{
   echo "The number:". $arr1[$x]; // printing the number  
   newline();
  
  //I tried to use '%' symbol for the remainder but i think it doesn't 
  //work so I googled and find the mod function 
  
   $iseven = fmod($arr1[$x],2); //variable to check it is even or not 
  
  if($iseven == 0) 
  // I also search for how to add values into the array which was    		     //array_push(array, values)
  	array_push($result, $arr1[$x]);  
}

print_r($result); // printing the resultant array 

?>
