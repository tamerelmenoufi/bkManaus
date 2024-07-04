<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['nota']) $_SESSION['nota'] = $_POST['nota'];

    echo $query = "select * from notas where codigo = '{$_SESSION['nota']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    $n = json_decode($d->dados);
    // print_r($n);

    if(is_array($n->NFe->infNFe->det)){
        foreach($n->NFe->infNFe->det as $i => $val){
            echo $val->prod->xProd."<br>";
        }
    }else{
        echo $n->NFe->infNFe->det->prod->xProd."<br>";
    }