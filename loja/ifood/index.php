<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<h4>Pedido do ifood</h4>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Categoria</th>
            <th>Produto</th>
            <th>Valor</th>
            <th>Valor no Combo</th>
        </tr>
    </thead>
    <tbody>
<?php
    $query = "select * from categorias where situacao = '1' and deletado != '1' order by ordem";
    $result = mysqli_query($con, $query);
    while($c = mysqli_fetch_object($result)){
        $query1 = "select * from produtos where categoria = '{$c->codigo}' and situacao = '1' and deletado != '1' order by produto";
        $result1 = mysqli_query($con, $query1);
        while($p = mysqli_fetch_object($result1)){
?>
        <tr>
            <td><?=$c->categoria?></td>
            <td><?=$p->produto?></td>
            <td>R$ <?=(($c->codigo == 8)?number_format(CalculaValorCombo($p->codigo),2,",",false):number_format($p->valor,2,",",false))?></td>
            <td><?=(($c->codigo == 8)?'-':'R$ '.number_format($p->valor_combo,2,",",false))?></td>
        </tr>
<?php
        }
    }
?>
    </tbody>
</table>