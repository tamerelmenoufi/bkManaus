<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=detalhes.csv');
?>
#;Loja;Data;Pedido;Valor;Entregador;Situação
<?php
        $query = "select 
                        a.*, 
                        b.nome as entregador,
                        c.nome as loja 
                    from ifood a 
                         left join entregadores b on a.entregador = b.codigo 
                         left join lojas c on a.loja = c.codigo 
                    where 1 {$_SESSION['where']} order by /*b.nome asc, a.data asc*/ a.data desc";
        $result = mysqli_query($con, $query);
        $i=1;
        $valor_total = 0;
        while($d = mysqli_fetch_object($result)){
        ?>
        <?=$i?>;<?=$d->loja?>;<?=dataBr($d->data)?>;<?=$d->ifood?>;<?=$d->valor?>;<?=(($d->entregador)?:"RETIRADA NA LOJA")?>;<?=strtoupper($d->producao)?>
        <?php
        $valor_total = $valor_total + $d->valor;
        $i++;
        }
        ?>
        ;;;TOTAL DAS VENDAS;<?=$valor_total?>;;