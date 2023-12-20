<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['loja']){
        $_SESSION['bkLoja'] = $_POST['loja'];
    }

    $query = "select * from lojas where codigo = '{$_SESSION['bkLoja']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

?>
<style>
    .barra_topo{
        position:absolute;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        top:0;
        width:100%;
        height:100px;
        background-color:#f4000a;
        color:#670600;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .barra_topo h2{
        color:#f6e13a;
    }
    .home_corpo{
        position: absolute;
        top:100px;
        bottom:0px;
        overflow:auto;
        background-color:#fff;
        left:0;
        right:0;
    }
</style>
<div class="barra_topo">
    <h2><?=$nome?></h2>
</div>

<div class="home_corpo">
    <div class="row g-0">

            <ul class="list-group m-3">
                <?php
                echo $query = "select * from vendas where delivery_id = '{$d->mottu}' and situacao = 'pago' order by data desc";
                $result = mysqli_query($con, $query);
                while($d = mysqli_fetch_object($result)){
                ?>
                    <li class="list-group-item" pedido="<?=$d->codigo?>"><?=$d->codigo?></li>
                <?php
                }
                ?>
            </ul>

        </div>
    </div>
</div>

<script>
    $(function(){
        
    })
</script>

  </body>
</html>