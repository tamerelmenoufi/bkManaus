<?php
    
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<h3>Lista do período de <?=$_POST['data']?></h3>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Data</th>
            <th>Pedido</th>
            <th>Valor</th>
            <th>Entregador</th>
            <th>Situação</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "select * from ifood where data like '%{$_POST['data']}%'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
        ?>
        <tr>
            <td><?=$d->data?></td>
            <td><?=$d->ifood?></td>
            <td><?=$d->valor?></td>
            <td><?=$d->entregador?></td>
            <td><?=$d->producao?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>