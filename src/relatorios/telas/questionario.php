<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $Json = [
        'redes_sociais',
        'meio_transporte',
        'tipo_moradia',
        'necessita_documentos',
        'opiniao_saude',
        'opiniao_infraestrutura',
        'opiniao_assistencia_social',
        'opiniao_seguranca',
        'opiniao_esporte_lazer'
    ];


    function questoes($d){

        global $_SESSION;
        global $con;

        $filtro = $f_usuario = $f_meta = $f_data = false;
        if($_SESSION['relatorio']['usuario']){
            $f_usuario = " and a.monitor_social in( {$_SESSION['relatorio']['usuario']} ) ";
        }
        if($_SESSION['relatorio']['meta']){
            $f_meta = " and a.meta in( {$_SESSION['relatorio']['meta']} ) ";
        }else if($_SESSION['ProjectSeLogin']->perfil == 'crd'){
            $f_meta = " and a.meta in( select codigo from metas where usuario in(select codigo from usuarios where coordenador = '{$_SESSION['ProjectSeLogin']->codigo}') ) ";
        }

        if($_SESSION['relatorio']['data_inicial']){
            $f_data = " and (a.data between '{$_SESSION['relatorio']['data_inicial']} 00:00:00' and '".(($_SESSION['relatorio']['data_final'])?:$_SESSION['relatorio']['data_inicial'])." 23:59:59')";
        }

        $filtro = $f_usuario . $f_meta . $f_data;

        if($d['join']){
            $join = $d['join'];
        }
        if($d['item']){
            $item = ", {$d['item']} as item";
        }
    
        $query = "select a.{$d['campo']} as campo {$item} from se a {$join} where a.monitor_social > 0 and a.meta > 0 {$filtro} ";
        $result = mysqli_query($con, $query);
        $t = 0;
        if(mysqli_num_rows($result)){
        while($s = mysqli_fetch_object($result)){

            if($d['tipo'] == 'json'){
                $J = json_decode($s->campo);
                if($J){
                    foreach($J as $i => $v){
                        
                        $d['legenda'][trim($v)] = ((trim($v))?:'Não Informado');

                        $D[$v] = ($D[$v] + 1);
                        $t = ($t + 1);
                    }
                }
            }else{

                if($item) {$d['legenda'][$s->campo] = $s->item;}
                else if(!$d['legenda'][$s->campo]) { $d['legenda'][trim($s->campo)] = ((trim($s->campo))?:'Não Informado'); }

                $D[$s->campo] = ($D[$s->campo] + 1);
                $t = ($t + 1);
            }
            
        }
?>

<div class="card mb-3">
  <h5 class="card-header"><?=$d['rotulo']?></h5>
  <div class="card-body">
    <ul class="list-group">
<?php
    arsort($D);
    foreach($D as $ind => $val){
        $p = number_format($val*100/$t, 0,false,false);
?>
        <li class="list-group-item">
            <div class="row">
                <div class="col-5"><?=($d['legenda'][$ind])?></div>
                <div class="col-5">
                    <div class="progress">
                        <div class="progress-bar" style="width:<?=$p?>%" role="progressbar" aria-valuenow="<?=$p?>" aria-valuemin="0" aria-valuemax="100"><?=$p?>%</div>
                    </div>
                </div>
                <div class="col-2">
                        <button 
                            class="btn btn-info btn-sm w-100 d-flex justify-content-between"
                            campo="<?=$d['campo']?>"
                            valor="<?=$ind?>" 
                            json="<?=$d['tipo']?>"
                            rotulo_titulo="<?=$d['rotulo']?>"
                            rotulo_campo="<?=$d['legenda'][$ind]?>"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"                          
                        >
                            <i class="fa-solid fa-arrow-up-1-9"></i><span><?=$val?> <i class="fa-solid fa-up-right-from-square"></i></span>
                        </button>                    
                </div>
            </div>
        </li>
<?php
    }
?>
    </ul>
  </div>
</div>

<?php
        }
    }


?>









<div class="row" style="margin-bottom:20px;">


    <?php



        $filtro = $f_usuario = $f_meta = $f_data = false;
        if($_SESSION['relatorio']['usuario']){
            $f_usuario = " and monitor_social in( {$_SESSION['relatorio']['usuario']} ) ";
        }
        if($_SESSION['relatorio']['meta']){
            $f_meta = " and meta in( {$_SESSION['relatorio']['meta']} ) ";
        }else if($_SESSION['ProjectSeLogin']->perfil == 'crd'){
            $f_meta = " and meta in( select codigo from metas where usuario in(select codigo from usuarios where coordenador = '{$_SESSION['ProjectSeLogin']->codigo}') ) ";
        }

        if($_SESSION['relatorio']['data_inicial']){
            $f_data = " and (data between '{$_SESSION['relatorio']['data_inicial']} 00:00:00' and '".(($_SESSION['relatorio']['data_final'])?:$_SESSION['relatorio']['data_inicial'])." 23:59:59')";
        }

        $filtro = $f_usuario . $f_meta . $f_data;


        $query = "SELECT *, count(*) as qt, (select count(*) from relatorios where  ordem = 1 {$filtro}) as total FROM relatorios where 1 {$filtro} group by ordem, campo, legenda ORDER BY ordem ASC, qt desc";
        $result = mysqli_query($con, $query);
        $grupo = false;
        while($d = mysqli_fetch_object($result)){

            if($grupo != $d->rotulo){

                if($grupo != false){
?>
    </ul>
  </div>
</div>
<?php
                }

?>
<div class="card mb-3">
  <h5 class="card-header"><?=$d->rotulo?></h5>
  <div class="card-body">
    <ul class="list-group">
<?php

            }


            $p = number_format($d->qt*100/$d->total, 0,false,false);

?>

        <li class="list-group-item">
            <div class="row">
                <div class="col-5"><?=$d->legenda?></div>
                <div class="col-5">
                    <div class="progress">
                        <div class="progress-bar" style="width:<?=$p?>%" role="progressbar" aria-valuenow="<?=$p?>" aria-valuemin="0" aria-valuemax="100"><?=$p?>%</div>
                    </div>
                </div>
                <div class="col-2">
                        <button 
                            class="btn btn-info btn-sm w-100 d-flex justify-content-between"
                            campo="<?=$d->campo?>"
                            valor="<?=$d->valor?>" 
                            json="<?=in_array($d->campo,$Json)?>"
                            rotulo_titulo="<?=$d->rotulo?>"
                            rotulo_campo="<?=$d->legenda?>"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"                          
                        >
                            <i class="fa-solid fa-arrow-up-1-9"></i><span><?=$d->qt?> - <?=$d->total?> <i class="fa-solid fa-up-right-from-square"></i></span>
                        </button>                    
                </div>
            </div>
        </li>

<?php
            $grupo = $d->rotulo;
        }



        // questoes([
        //     'rotulo' => 'Situação da Pesquisa',
        //     'campo' => 'situacao',
        //     'legenda' => [
        //         'i' => 'Iniciada',
        //         'c' => 'Concluida',
        //         'n' => 'Não encontrado',
        //         'p' => 'Pendente',
        //         '' => 'Não Informada',
        //     ]
        // ]);

        // questoes([
        //     'rotulo' => 'Municípios',
        //     'campo' => 'municipio',
        //     'join' => "left join municipios b on a.municipio = b.codigo ",
        //     'item' => "b.municipio"
        // ]);

        // questoes([
        //     'rotulo' => 'Bairros / Comunidades',
        //     'campo' => 'bairro_comunidade',
        //     'join' => "left join bairros_comunidades b on a.bairro_comunidade = b.codigo ",
        //     'item' => "b.descricao"
        // ]);

        // questoes([
        //     'rotulo' => 'Zonas',
        //     'campo' => 'local',
        // ]);

        // questoes([
        //     'rotulo' => 'Genéro',
        //     'campo' => 'genero',
        // ]);

        // questoes([
        //     'rotulo' => 'Estado Civil',
        //     'campo' => 'estado_civil',
        // ]);

        // questoes([
        //     'rotulo' => 'Redes Sociais',
        //     'campo' => 'redes_sociais',
        //     'tipo' => 'json' 
        // ]);

        // questoes([
        //     'rotulo' => 'Meio de Trasporte',
        //     'campo' => 'meio_transporte',
        //     'tipo' => 'json' 
        // ]);

        // questoes([
        //     'rotulo' => 'Tipo de Imóvel',
        //     'campo' => 'tipo_imovel',
        // ]);

        // questoes([
        //     'rotulo' => 'Tipo de Moradia',
        //     'campo' => 'tipo_moradia',
        //     'tipo' => 'json'
        // ]);

        // questoes([
        //     'rotulo' => 'Quantidade de Cômodos na Moradia',
        //     'campo' => 'quantidade_comodos',
        // ]);

        // questoes([
        //     'rotulo' => 'Grau de escolaridade',
        //     'campo' => 'grau_escolaridade',
        // ]);

        // questoes([
        //     'rotulo' => 'Cursos Profissionalizantes',
        //     'campo' => 'curos_profissionais',
        // ]);

        // questoes([
        //     'rotulo' => 'Interesse por novos Cursos',
        //     'campo' => 'intereese_curso',
        // ]);

        // questoes([
        //     'rotulo' => 'Renda Mensal',
        //     'campo' => 'renda_mensal',
        // ]);


        // questoes([
        //     'rotulo' => 'Renda Familiar',
        //     'campo' => 'renda_familiar',
        // ]);

        // questoes([
        //     'rotulo' => 'Beneficio Social',
        //     'campo' => 'beneficio_social',
        // ]);

        // questoes([
        //     'rotulo' => 'Serviço de Saúde',
        //     'campo' => 'servico_saude',
        // ]);

        // questoes([
        //     'rotulo' => 'Condições de Saúde',
        //     'campo' => 'condicoes_saude',
        // ]);


        // questoes([
        //     'rotulo' => 'Vacina contra o Covid-19',
        //     'campo' => 'vacina_covid',
        // ]);

        // questoes([
        //     'rotulo' => 'Necessita de Documentos',
        //     'campo' => 'necessita_documentos',
        //     'tipo' => 'json',
        // ]);
        // questoes([
        //     'rotulo' => 'Como você avalia o Beneficio',
        //     'campo' => 'avaliacao_beneficios',
        // ]);
        // questoes([
        //     'rotulo' => 'O beneficio atendido as Necessidades',
        //     'campo' => 'atende_necessidades',
        // ]);
        // questoes([
        //     'rotulo' => 'Opinião na Saúde',
        //     'campo' => 'opiniao_saude',
        //     'tipo' => 'json',
        // ]);
        // questoes([
        //     'rotulo' => 'Opinião na Educação',
        //     'campo' => 'opiniao_educacao',
        // ]);

        // questoes([
        //     'rotulo' => 'Opinião na Cidadania',
        //     'campo' => 'opiniao_cidadania',
        // ]);

        // questoes([
        //     'rotulo' => 'Opinião na Infraestrutura',
        //     'campo' => 'opiniao_infraestrutura',
        //     'tipo' => 'json',
        // ]);

        // questoes([
        //     'rotulo' => 'Opinião na Assistência Social',
        //     'campo' => 'opiniao_assistencia_social',
        //     'tipo' => 'json',
        // ]);

        // questoes([
        //     'rotulo' => 'Opinião nos Direitos Humanos',
        //     'campo' => 'opiniao_direitos_humanos',
        // ]);

        // questoes([
        //     'rotulo' => 'Opinião na Segurança',
        //     'campo' => 'opiniao_seguranca',
        //     'tipo' => 'json',
        // ]);

        // questoes([
        //     'rotulo' => 'Opinião no Esporte e Lazer',
        //     'campo' => 'opiniao_esporte_lazer',
        //     'tipo' => 'json',
        // ]);

        // questoes([
        //     'rotulo' => 'Recepção pelo Beneficiado',
        //     'campo' => 'recepcao_entrevistado',
        // ]);

                
    ?>

</div>

<script>
    $(function(){
        Carregando('none');
        $("button[campo]").click(function(){
            campo = $(this).attr("campo")
            valor = $(this).attr("valor")
            json = $(this).attr("json")
            rotulo_titulo = $(this).attr("rotulo_titulo")
            rotulo_campo = $(this).attr("rotulo_campo")
            $(".LateralDireita").html('');
            Carregando();
            $.ajax({
                url:"src/relatorios/telas/lista_beneficiados.php",
                type:"POST",
                data:{
                    campo,
                    valor,
                    json,
                    titulo:`${rotulo_titulo} - ${rotulo_campo}`
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                    // $.dialog({
                    //     title:`${rotulo_titulo} - ${rotulo_campo}`,
                    //     content:dados,
                    //     columnClass:'col-md-12'
                    // });
                }
            })
        });
    })
</script>