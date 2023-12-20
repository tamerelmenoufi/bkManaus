<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

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
    <h2>Lojas</h2>
</div>

<div class="home_corpo">
    <div class="row g-0">
        <div class="col">
            <div class="alert alert-warning m-3" role="alert">
            Selecione uma das lojas para acessar as comandas de pedidos.
            </div>


            <ul class="list-group m-3">
                <?php
                $query = "select * from lojas where situacao = '1' and deletado != '1' order by nome";
                $result = mysqli_query($con, $query);
                while($d = mysqli_fetch_object($result)){
                ?>
                    <li class="list-group-item"><?=$d->nome?></li>
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