<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);

    // if(!$_POST['perfil'][0]['codigo']) $_POST['perfil'][0]['codigo'] = 248;


    $q = "select * from metas where data >= DATE_ADD(NOW(), INTERVAL -7 DAY) and data <= NOW() and usuario = '{$_POST['perfil'][0]['codigo']}' and situacao = '1' and deletado != '1'";
    $r = mysqli_query($con, $q);
    $metas = [];
    while($m = mysqli_fetch_object($r)){
        $metas[] = $m->codigo;
    }

    if(!$metas)  exit(); 

    $metas = implode(",", $metas);

    $ignore = [
        'redes_sociais',
        'meio_transporte',
        'tipo_moradia',
        'necessita_documentos',
        'opiniao_saude',
        'opiniao_infraestrutura',
        'opiniao_assistencia_social',
        'opiniao_seguranca',
        'opiniao_esporte_lazer',
        'codigo'
    ];

    $query = "SELECT * FROM `COLUMNS` where TABLE_SCHEMA = 'app' and COLUMN_NAME not in ('".implode("','", $ignore)."') and TABLE_NAME = 'se' order by TABLE_NAME";
    $result = mysqli_query($conApi, $query);
    while($d = mysqli_fetch_object($result)){
        $campos[] = $d->COLUMN_NAME;
        $tipos[$d->COLUMN_NAME] = $d->DATA_TYPE;
    }


    $query = "SELECT * FROM `se` where meta in ($metas)";  /*and situacao not in ('c', 'f', 'n')*/
    $result = mysqli_query($con, $query);

    // $Cmd[] = ['comando' => "DELETE FROM se"];
    
    while($d = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        $D = [];

        $reg['bairros_comunidades'][$d['bairro_comunidade']] = $d['bairro_comunidade'];
        $reg['municipios'][$d['municipio']] = $d['municipio'];
        
        $de = array("\n","\r","\\\"",'[',']',"'");
        $para = array(false,false,false,false,false,"`");
        foreach($d as $i => $v){
            if(!in_array($i, $ignore) or $i == 'codigo'){
                if(strtolower($tipos[$i]) == 'bigint' or strtolower($tipos[$i]) == 'int'){
                    $D[] = str_replace($de, $para, $v);
                }else{
                    if($i == 'data_nascimento'){
                        $D[] = "'".dataBr($v)."'";
                    }else{
                        $D[] = "'".str_replace($de, $para, $v)."'";
                    }
                    
                }
            }
            
        }
        $Cmd[] = ['comando' => "INSERT INTO se (codigo, ".implode(", ", $campos).") VALUES (".implode(", ",$D).")"];
    } 

    $Cmd[] = ['comando' => $_POST['perfil']];


    $addTab = ['bairros_comunidades','municipios', 'metas','mensagens'];

    $reg['bairros_comunidades'] = (($reg['bairros_comunidades'])?" and codigo in (". @implode(",", $reg['bairros_comunidades']).")":false);
    $reg['municipios'] = (($reg['municipios'])?" and codigo in (". @implode(",", $reg['municipios']).")":false);
    $reg['metas'] = (($metas)?" and codigo in (". $metas.")":false);
    $reg['mensagens'] = " and situacao = '1' and deletado != '1' ";

    if($metas){
        $Cmd[] = ['comando' => "DELETE FROM metas WHERE codigo not in ($metas)"];
    }else{
        $Cmd[] = ['comando' => "DELETE FROM metas"];
    }

    $query = "SELECT * FROM `COLUMNS` where TABLE_SCHEMA = 'app' and TABLE_NAME in('".implode("','", $addTab)."') order by TABLE_NAME";
 
    $result = mysqli_query($conApi, $query);
    while($d = mysqli_fetch_object($result)){
        
        $Comando[$d->TABLE_NAME][] = $d->COLUMN_NAME;
        $tipo[$d->TABLE_NAME][$d->COLUMN_NAME] = $d->DATA_TYPE;
    }

    foreach($Comando as $ind => $val){

        if($reg[$ind]){
            $query = "select * from {$ind} where 1 {$reg[$ind]}";
        }else{
            $query = false;
        }
        if($query){
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $D = [];
                $campos = [];
                foreach($d as $i => $v){
                    $campos[] = $i;
                    if($tipo[$ind][$i] == 'bigint' or $i == 'codigo'){
                        $D[] = str_replace("'", "`", $v);
                    }else{
                        $D[] = "'".str_replace("'", "`", $v)."'";
                    }
                }
                $Cmd[] = ['comando' => "INSERT INTO $ind (".implode(", ", $campos).") VALUES (".implode(", ",$D).")"];
            }  
        }          
        
    }

    if($metas){
        $Cmd[] = ['comando' => "DELETE FROM se WHERE meta not in ($metas)"];
    }
    echo json_encode($Cmd);