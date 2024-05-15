<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    // exit();

    function preparaJson($d){

        $D = explode(",",$d);
        return '["'.implode('","', $D).'"]';

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);
 
    if(!is_dir('logs')) mkdir('logs');

    echo 'success';

    file_put_contents('logs/ef-'.date("YmdHis").".txt", print_r($_POST, true));

    foreach($_POST['dados'] as $ind => $val){

        $data = $val;
        unset($data['codigo']);
        $data['ef_data_nascimento'] = dataMysql($data['ef_data_nascimento']);

        $data['ef_necessita_documentos'] = preparaJson($data['ef_necessita_documentos']);

        // $data['data_cadastro'] = date("Y-m-d H:i:s");
        // $codigo = $val['codigo'];

        $campos = [];
        foreach($data as $i => $v){
            $campos[] = "{$i} = '{$v}'";
        }

        $comando = "INSERT INTO se_estrutura_familiar set ".implode(", ", $campos);

        mysqli_query($con, $comando);

        file_put_contents('logs/comando2-'.date("YmdHis").".txt", $comando);


    }