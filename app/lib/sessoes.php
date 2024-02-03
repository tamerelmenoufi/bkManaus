<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    echo base64_encode(json_encode($_SESSION));
?>