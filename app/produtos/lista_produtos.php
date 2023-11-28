<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['categoria']){
        $_SESSION['categoria'] = $_POST['categoria'];
    }

    $c = mysqli_fetch_object(mysqli_query($con, "elect * from categorias where codigo = '{$_SESSION['categoria']}'"));    

?>
<style>
    .barra_topo{
        position:absolute;
        top:0;
        width:100%;
        height:100px;
        background-color:red;
    }

    .home_corpo{
        position: absolute;
        top:100px;
        bottom:90px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:90px;
    }
</style>

<div class="barra_topo">

</div>

<div class="home_corpo">
<?php
echo $query = "select * from produtos where categoria = '{$c->categoria}' and deletado != '1' and situacao = '1'";
$result = mysqli_query($con, $query);
while($d = mysqli_fetch_object($result)){
?>
    <div><?=$d->produto?></div>
<?php
}
?>
</div>
<div class="home_rodape"></div>

<script>

$(function(){

    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });


})

	

</script>