<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['cupom'] == 'BK0824'){

        $_SESSION['desconto'] = true;

    }else{

        $_SESSION['desconto'] = false;
        echo 'erro';

    }