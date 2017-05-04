<?php 

$json_input_data=json_decode(file_get_contents('php://input'),TRUE);

echo $json_input_data['name'] ;

?>

    
