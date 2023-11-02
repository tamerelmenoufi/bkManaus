<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    // exit();

    if(!$_SESSION['filtro_especifico']) exit();

    $fLocal = [];
    if($_SESSION['filtro_relatorio_municipio']) { $fLocal[] = " municipio = '{$_SESSION['filtro_relatorio_municipio']}'"; }
    if($_SESSION['filtro_relatorio_tipo']) { $fLocal[] = " local = '{$_SESSION['filtro_relatorio_tipo']}'"; }
    if($_SESSION['filtro_relatorio_bairro_comunidade']) { $fLocal[] = " bairro_comunidade = '{$_SESSION['filtro_relatorio_bairro_comunidade']}'"; }

    if($fLocal){
        $fLocal = " and ".implode(" and ", $fLocal);
    }else{
        $fLocal = false;
    }

    $query = "select * from se where ({$_SESSION['filtro_especifico']}) {$fLocal} limit 100";
    $result = mysqli_query($con, $query);

?>

<h5>Relatório Específico</h5>
<p><?=$_SESSION['filtro_especifico_descricao']?></p>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
        </tr>
    </thead>
    <tbody>


<?php
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