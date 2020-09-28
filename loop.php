<?php
$numbers =array(1,2,3,4,5,6,7,8,9);

$count = count($numbers);
echo "The array has $count elements \n";
echo"The even numbers in the array \n";
for($i = 0; $i < $count; $i++)
{
 if($numbers[$i]%2==0)
 {
 	echo $numbers[$i] . " "; 
 }
}
?>

