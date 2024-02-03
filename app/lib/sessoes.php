<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    echo bse64_encode(json_encode($_SESSION));
?>