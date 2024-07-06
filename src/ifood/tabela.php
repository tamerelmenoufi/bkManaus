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
                <th>Entregador</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "select 
                            a.*, 
                            IF(b.nome,b.nome,a.entregador) as entregador,
                            c.nome as loja
                        from ifood a 
                             left join entregadores b on a.entregador = b.codigo 
                             left join lojas c on a.loja = c.codigo 
                        where a.data like '%{$_POST['data']}%'";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){

                if(!$d->entregador){
                    $entregador = 'RETIRADA NA LOJA';
                }else if($d->entregador === 1){
                    $entregador = 'RETIRADA PELO PARCEIRO';
                }else{
                    $entregador = $d->entregador;
                }

            ?>
            <tr class="table-<?=(($d->producao == 'entregue')?'success':'danger')?>">
                <td><?=$d->loja?></td>
                <td><?=dataBr($d->data)?></td>
                <td>#<?=$d->ifood?></td>
                <td>R$ <?=number_format($d->valor,2,',','.')?></td>
                <td><?=$entregador?></td>
                <td><?=strtoupper($d->producao)?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>