<?php

    function dataBr($dt){
        list($d, $h) = explode(" ",$dt);
        list($y,$m,$d) = explode("-",$d);
        $data = false;
        if($y && $m && $d){
            $data = "{$d}/{$m}/$y"; //.(($h)?" {$h}":false);
        }
        return $data;
    }

    function dataMysql($dt){
        list($d, $h) = explode(" ",$dt);
        list($d,$m,$y) = explode("/",$d);
        $data = false;
        if($y && $m && $d){
            $data = "{$y}-{$m}-$d"; //.(($h)?" {$h}":false);
        }
        return $data;
    }

    function sisLog($d){

        global $con;
        global $_POST;
        global $_SESSION;
    
        $query = addslashes($d['query']);
        $file = $d['file'];
        $sessao = addslashes(json_encode($_SESSION));
        $dados = json_encode($_POST);
        $registro = $d['registro'];
        $p = explode(" ",$query);
        $operacao = strtoupper(trim($p[0]));
        if(strtolower(trim($p[0])) == 'insert'){
            $tabela =  strtolower(trim($p[2]));
        }
        if(strtolower(trim($p[0])) == 'update'){
            $tabela =  strtolower(trim($p[1]));
        }
    
        mysqli_query($con, "
            INSERT INTO sisLog set 
                                    file = '{$file}',
                                    tabela = '{$tabela}',
                                    operacao = '{$operacao}',
                                    registro = '{$registro}',
                                    sessao = '{$sessao}',
                                    dados = '{$dados}',
                                    query = '{$query}',
                                    data = NOW()
        ");
        
        return "
            INSERT INTO sisLog set 
                                    file = '{$file}',
                                    tabela = '{$tabela}',
                                    operacao = '{$operacao}',
                                    registro = '{$registro}',
                                    sessao = '{$sessao}',
                                    dados = '{$dados}',
                                    query = '{$query}',
                                    data = NOW()
        ";
    
    }