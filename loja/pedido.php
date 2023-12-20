<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    if($_POST['pedido']){
        $_SESSION['pedido'] = $_POST['pedido'];
    }


?>
<style>

</style>

<div class="row g-0 m-3">

    <ul class="list-group">
        <?php
        $query = "select a.*, b.nome, a.delivery_detalhes->>'$.pickupCode' as entrega, a.delivery_detalhes->>'$.returnCode' as retorno from vendas a left join clientes b on a.cliente = b.codigo where a.codigo = '{$_SESSION['pedido']}'";
        $result = mysqli_query($con, $query);
        while($d = mysqli_fetch_object($result)){
        ?>
            <li class="list-group-item" pedido="<?=$d->codigo?>">
                <div class="d-flex justify-content-between">
                    <div>
                        Pedido #<?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?>
                        <br>
                        <?=$d->nome?>
                    </div>
                    <div>
                        Entrega: <?=$d->entrega?>
                        <br>
                        Retorno: <?=$d->retorno?>
                    </div>
                </div>
            </li>
        <?php
        }
        ?>
    </ul>

</div>

<script>
    $(function(){
        

    })
</script>

  </body>
</html>