<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if(!$_SESSION and $_POST['session']){
        $_SESSION = base64_decode(json_decode($_POST['session'])); 
    }

    echo base64_encode(encode($_SESSION));
?>