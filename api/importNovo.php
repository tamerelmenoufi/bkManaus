<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    // exit();


    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);
 
    if(!is_dir('logs')) mkdir('logs');

    echo 'success';

    file_put_contents('logs/novo_'.date("YmdHis").".txt", print_r($_POST, true));


    foreach($_POST['dados'] as $ind => $val){

        $data = $val;
        unset($data['codigo']);

        $data['data'] = date("Y-m-d H:i:s");

        $campos = [];
        foreach($data as $i => $v){
            $campos[] = "{$i} = '{$v}'";
        }


        $comando = "INSERT INTO se_novos set ".implode(", ", $campos);

        mysqli_query($con, $comando);

        file_put_contents('logs/comando_novo-'.date("YmdHis").".txt", $comando);


    }