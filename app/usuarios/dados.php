<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

    if($_POST['telefone']){
        echo $q = "SELECT * from usuarios WHERE telefone = '{$_POST['telefone']}'";
        $c = mysqli_fetch_object(mysqli_query($con, $q));
        if($c->codigo){
            $_SESSION['codUsr'] = $c->codigo;
        }else{
            mysqli_query($con, "INSERT INTO usuarios set telefone = '{$_POST['telefone']}'");
            $_SESSION['codUsr'] = mysqli_insert_id($con);
        }
    }

    $query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $_SESSION['codUsr'] = $d->codigo;
    ////////////////

?>