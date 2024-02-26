<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    th{
        font-size:13px;
    }
    td{
        font-size:12px;
    }
    
</style>
<h4>Pedido do ifood</h4>
<div style="position:absolute; left:0; right:0; top:70px; bottom:0; overflow:auto;">
    <table class="table table-hover">
    <?php
        $query = "select * from categorias where situacao = '1' and deletado != '1' order by ordem";
        $result = mysqli_query($con, $query);
        while($c = mysqli_fetch_object($result)){
    ?>
        <thead>
            <tr>
                <th colspan="3"><div style="margin-top:20px;"><?=$c->categoria?></div></th>
            </tr>
        </thead>
        <tbody>
    <?php
            $query1 = "select * from produtos where categoria = '{$c->codigo}' and situacao = '1' and deletado != '1' order by produto";
            $result1 = mysqli_query($con, $query1);
            while($p = mysqli_fetch_object($result1)){
    ?>
            <tr>
                <td><?=$p->produto?></td>
                <td>R$ <?=(($c->codigo == 8)?number_format(CalculaValorCombo($p->codigo),2,",",false):number_format($p->valor,2,",",false))?></td>
            </tr>
    <?php
            }
    ?>
        </tbody>
    <?php
        }
    ?>
    </table>
</div>