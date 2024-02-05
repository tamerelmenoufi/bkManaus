<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['idUnico']){
        $n = mysqli_num_rows(mysqli_query($con,"select * from vendas_tmp where id_unico = '{$_POST['idUnico']}'"));
        file_put_contents('id_unico'.$_POST['idUnico'].'.txt', "select * from vendas_tmp where id_unico = '{$_POST['idUnico']}'");
        if(!$n){
            mysqli_query($con, "insert into vendas_tmp set id_unico = '{$_POST['idUnico']}', cliente = '{$_POST['codUsr']}', detalhes='{}'");
            file_put_contents('id_unico_novo-'.$_POST['idUnico'].'.txt', "select * from vendas_tmp where id_unico = '{$_POST['idUnico']}'");
        }
    }

?>