<?php
    
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<h3>Lista do período de <?=dataBr($_POST['data'])?></h3>
<div class="table-responsive" style="max-height:500px;">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Loja</th>
                <th>Data</th>
                <th>Pedido</th>
                <th>Valor</th>
                <th>Valor Entrega</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "select 
                            a.*, 
                            c.nome as loja
                        from vendas a 
                             left join lojas c on a.loja = c.codigo 
                        where a.data like '%{$_POST['data']}%'";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
            ?>
            <tr class="table-<?=(($d->producao == 'entregue')?'success':'danger')?>">
                <td><?=$d->loja?></td>
                <td><?=dataBr($d->data)?></td>
                <td>#<?=$d->codigo?></td>
                <td>R$ <?=number_format($d->valor_total,2,',','.')?></td>
                <td>R$ <?=number_format($d->valor_entrega_total,2,',','.')?></td>
                <td><?=strtoupper($d->producao)?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>