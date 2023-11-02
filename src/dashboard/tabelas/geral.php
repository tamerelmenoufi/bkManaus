<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    // iniciados pendentes concluidos nao_encontrados
    // echo $_POST['filtro'] . ' & '.$_POST['opc'];

    $filtro = [
        'i' => 'Iniciados',
        'p' => 'Pendentes',
        'c' => 'Concluídos',
        'n' => 'Não Encontrados',
    ];

    $titulo = [];

    if($_SESSION['municipios']['nome'][$_SESSION['filtro_relatorio_municipio']] ){ $titulo[] = $_SESSION['municipios']['nome'][$_SESSION['filtro_relatorio_municipio']]; }
    if($_SESSION['bairro']['nome'][$_SESSION['filtro_relatorio_bairro_comunidade']] ){ $titulo[] = $_SESSION['bairro']['nome'][$_SESSION['filtro_relatorio_bairro_comunidade']]; }
    if($_SESSION['filtro_relatorio_tipo']) { $titulo[] = $_SESSION['filtro_relatorio_tipo']; }

    if($titulo) $titulo = implode(" - ",$titulo).(($_POST['filtro'])?" ({$filtro[$_POST['filtro']]})":false);

    $fLocal = [];
    if($_SESSION['filtro_relatorio_municipio']) { $fLocal[] = " municipio = '{$_SESSION['filtro_relatorio_municipio']}'"; }
    if($_SESSION['filtro_relatorio_tipo']) { $fLocal[] = " local = '{$_SESSION['filtro_relatorio_tipo']}'"; }
    if($_SESSION['filtro_relatorio_bairro_comunidade']) { $fLocal[] = " bairro_comunidade = '{$_SESSION['filtro_relatorio_bairro_comunidade']}'"; }

    if($fLocal){
        $fLocal = " and ".implode(" and ", $fLocal);
    }else{
        $fLocal = false;
    }



    switch($_POST['filtro']){
        case 'i':{
            $where = " percentual > 0 and percentual < 100";
            break;
        }
        case 'p':{
            $where = " percentual = 0 ";
            break;
        }
        case 'c':{
            $where = " percentual = '100'";
            break;
        }
        case 'n':{
            $where = " beneficiario_encontrado = 'Não'";
            break;
        }
        default:{
            $where = 1;
            break;
        }

    }

    // if(!$where) exit();
?>
<div class="col">
    <p>
        Resultado da pesquisa: <?=$titulo?>
    </p>
    <table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
        </tr>
    </thead>
    <tbody>
    <?php
        $query = "select * from se where {$where} {$fLocal} limit 100";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
    ?>
        <tr>
            <td><?=$d->nome?></td>
            <td><?=$d->cpf?></td>
        </tr>
    <?php
        }
    ?>
    </tbody>
    </table>
</div>