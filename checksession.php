<?php
//print_r($_SESSION);
if(empty($_SESSION)){
    echo json_encode(['status'=>true]);
}


?>