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

    function montaCheckbox($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = json_decode($v['dados']);
        $exibir = $v['exibir'];
        $destino = $v['campo_destino'];
        // $lista[] = print_r($dados, true);
        $lista[] = '<div class="mb-3"><label for="'.$campo.'"><b>'.$rotulo.'</b></label></div>';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <div class="mb-3 form-check">
            <input
                    type="checkbox"
                    name="'.$campo.'[]"
                    value="'.$vetor[$i].'"
                    class="form-check-input"
                    id="'.$campo.$i.'"
                    '.((@in_array($vetor[$i],$dados))?'checked':false).'
                    '.(($exibir[$vetor[$i]])?' exibir="'.$destino.'" ':' ocultar="'.$destino.'"').'
            >
            <label class="form-check-label" for="'.$campo.$i.'">'.$vetor[$i].'</label>
            </div>';
        }

        if($lista){
            return implode(" ",$lista);
        }
    }

    function montaRadio($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = $v['dados'];
        $exibir = $v['exibir'];
        $destino = $v['campo_destino'];

        $lista[] = '<div class="mb-3"><label for="'.$campo.'"><b>'.$rotulo.'</b></label></div>';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <div class="mb-3 form-check">
            <input
                    type="radio"
                    name="'.$campo.'"
                    value="'.$vetor[$i].'"
                    class="form-check-input"
                    id="'.$campo.$i.'"
                    '.(($vetor[$i] == $dados)?'checked':false).'
                    '.(($exibir[$vetor[$i]])?' exibir="'.$destino.'" ':' ocultar="'.$destino.'"').'
            >
            <label class="form-check-label" for="'.$campo.$i.'">'.$vetor[$i].'</label>
            </div>';
        }
        if($lista){
            return implode(" ",$lista);
        }
    }



    function montaCheckboxFiltro($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = $v['dados'];
        $exibir = $v['exibir'];
        $destino = $v['campo_destino'];
        // $lista[] = print_r($dados, true);
        $lista[] = '<div class="mb-3"><label for="'.$campo.'"><b>'.$rotulo.'</b></label></div>';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <div class="mb-3 form-check">
            <input
                    type="checkbox"
                    name="'.$campo.'[]"
                    value="'.$vetor[$i].'"
                    class="form-check-input"
                    id="'.$campo.$i.'"
                    '.((@in_array($vetor[$i],$dados))?'checked':false).'
                    '.(($exibir[$vetor[$i]])?' exibir="'.$destino.'" ':' ocultar="'.$destino.'"').'
            >
            <label class="form-check-label" for="'.$campo.$i.'">'.$vetor[$i].'</label>
            </div>';
        }

        if($lista){
            return implode(" ",$lista);
        }
    }



    function montaOpcPrint($v){
        $campo = $v['campo'];
        $vetor = $v['vetor'];
        $rotulo = $v['rotulo'];
        $dados = json_decode($v['dados']);
        // $lista[] = print_r($dados, true);
        $lista[] = '<div class="mt-3" style="width:100%; float:none;"><b>'.$rotulo.'</b></div><div style="width:100%; float:none;">';
        for($i=0;$i<count($vetor);$i++){
            $lista[] = '  <span margin-left:15px;">
            <i class="fa-solid fa-square" style="color:#ccc"></i> '.$vetor[$i].'</span>';
        }
        $lista[] = '</div>';
        if($lista){
            return implode(" ",$lista);
        }
    }


    function array_multisum($arr){
        $sum = array_sum($arr);
        foreach($arr as $child) {
            $sum += is_array($child) ? array_multisum($child) : 0;
        }
        return $sum;
    }

    function sisLog($d){

        global $con;
        global $_POST;
    
        $query = addslashes($d['query']);
        $file = $d['file'];
        $sessao = addslashes(json_encode($d['sessao']));
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