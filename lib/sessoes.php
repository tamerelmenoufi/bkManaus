<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['session']){
        $session = json_decode(base64_decode($_POST['session']));
        if($session){
            foreach($session as $ind => $val){
                $_SESSION[$ind] = $val;
            }
        }
    }

    if($_SESSION['appLogin']->codigo > 0){
        echo base64_encode(json_encode($_SESSION)); 
    }else{
        echo 'error';
    }