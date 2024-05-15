<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    // exit();

    $CamposObrigatorios = [
        'nome',
        'cpf',
        'rg',
        'rg_orgao',
        'data_nascimento',
        'telefone',
        'municipio',
        'local',
        'bairro_comunidade',
        'endereco',
        'cep',
        'genero',
        'estado_civil',
        'redes_sociais',
        'meio_transporte',
        'tipo_imovel',
        'tipo_moradia',
        'quantidade_comodos',
        'grau_escolaridade',
        'curos_profissionais',
        'intereese_curso',
        'renda_mensal',
        'renda_familiar',
        'beneficio_social',
        'servico_saude',
        'condicoes_saude',
        'vacina_covid',
        'necessita_documentos',
        'avaliacao_beneficios',
        'atende_necessidades',
        'opiniao_saude',
        'opiniao_educacao',
        'opiniao_cidadania',
        'opiniao_infraestrutura',
        'opiniao_assistencia_social',
        'opiniao_direitos_humanos',
        'opiniao_seguranca',
        'opiniao_esporte_lazer'
    ];

    function preparaJson($d){

        $D = explode(",",$d);
        return '["'.implode('","', $D).'"]';

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);
 
    if(!is_dir('logs')) mkdir('logs');

    echo 'success';

    file_put_contents('logs/'.date("YmdHis").".txt", print_r($_POST, true));


    foreach($_POST['dados'] as $ind => $val){

        $data = $val;
        unset($data['municipio']);
        unset($data['bairro_comunidade']);
        unset($data['local']);
        unset($data['zona_urbana']);
        unset($data['coordenadas']);
        unset($data['codigo']);
        unset($data['percentual']);
        unset($data['acao_relatorio']);
        
        $data['data_nascimento'] = dataMysql($data['data_nascimento']);
        $data['redes_sociais'] = preparaJson($data['redes_sociais']);
        $data['meio_transporte'] = preparaJson($data['meio_transporte']);
        $data['tipo_moradia'] = preparaJson($data['tipo_moradia']);
        $data['necessita_documentos'] = preparaJson($data['necessita_documentos']);
        $data['opiniao_saude'] = preparaJson($data['opiniao_saude']);
        $data['opiniao_infraestrutura'] = preparaJson($data['opiniao_infraestrutura']);
        $data['opiniao_assistencia_social'] = preparaJson($data['opiniao_assistencia_social']);
        $data['opiniao_seguranca'] = preparaJson($data['opiniao_seguranca']);
        $data['opiniao_esporte_lazer'] = preparaJson($data['opiniao_esporte_lazer']);

        $data['data'] = date("Y-m-d H:i:s");
        $codigo = $val['codigo'];

        $campos = [];
        foreach($data as $i => $v){
            // if($i == 'beneficiario_encontrado'){
            //     if($data['situacao'] == 'n'){
            //         $data['beneficiario_encontrado'] = 'Não';
            //     }else if($data['situacao'] != 'i'){
            //         $data['beneficiario_encontrado'] = 'Sim';
            //     }else{
            //         $campos[] = "{$i} = '{$v}'";
            //     }
            // }else{
            //     $campos[] = "{$i} = '{$v}'";
            // }
            $campos[] = "{$i} = '{$v}'";
        }

        ////////////////////////////////PERCENTUAL/////////////////////////////////////

        $tot = count($CamposObrigatorios);
        $qt = 0;
        $remov = ['[""]', 'null', '0', '0.00', ' '];
        foreach ($data as $name => $value) {

            // if(is_array($value)) {
            //     $value = json_encode($value,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            // }
            // $qt = ((trim(str_replace($remov, false,$value)))?($qt+1):$qt);
            if(in_array($name, $CamposObrigatorios) and (trim(str_replace($remov, false,$value))) ){
                $qt = ($qt+1);
            }
        }
            $pct = (100*$qt/$tot);
            $campos[] = "percentual = '{$pct}'";
            $campos[] = "acao_relatorio = '0'";

        //////////////////////////////////////////////////////////////////////////////


        $comando = "UPDATE se set ".implode(", ", $campos)." where codigo = '{$codigo}'";

        mysqli_query($con, $comando);

        file_put_contents('logs/comando-'.date("YmdHis").".txt", $comando);


    }