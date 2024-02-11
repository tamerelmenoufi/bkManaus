<?php
include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

$i = ((count($_SESSION['historico']))?(count($_SESSION['historico']) - 1):0);
    
$pdAtiva = $_SESSION['historico'][$i]['local'];

$v = mysqli_fetch_object(mysqli_query($con, "select * from vendas_tmp where id_unico = '{$_SESSION['idUnico']}'"));
//object(stdClass)#4 (1) { ["item35"]=> object(stdClass)#6 (9) { ["tipo"]=> string(7) "produto" ["total"]=> float(30.9) ["valor"]=> float(30.9) ["codigo"]=> int(35) ["regras"]=> object(stdClass)#7 (1) { ["categoria"]=> string(1) "5" } ["status"]=> string(0) "" ["adicional"]=> int(0) ["anotacoes"]=> string(0) "" ["quantidade"]=> int(1) } }
$qt_pedidos = 0;
foreach(json_decode($v->detalhes) as $ind => $val){
    if($val->status){
        $qt_pedidos++;
    }
}

?>

<style>
    .rodape{
        position:absolute;
        bottom:0;
        width:100%;
        background-color:#fff;
        height:80px;
        z-index:1;
    }
    .rodape_area{
        position:absolute;
        margin:0px;
        margin-top:15px;
        margin-bottom:10px;
        padding-right:20px;
        border-radius:20px;
        background-color:#b60710;
        left:2px;
        right:5px;
        top:0;
        bottom:5px;
        z-index:10;
    }
    .rodape_area img{
        height:70px;
        width:auto;
        cursor:pointer;
    }
    .rodape_area div{
        height:100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        cursor:pointer;
    }
    .rodape_area i{
        font-size:25px;
        color:#5a0103;
        margin:0;
        padding:0;
    }
    .rodape_area p{
        font-family:Insanibu;
        font-size:12px;
        color:#5a0103;
        margin:0;
        padding:0;
    }
    .telaPedido{
        position:relative;
    }
    .itens_produtos_add{
        position:absolute;
        width:15px;
        height:15px;
        border-radius:100%;
        background:green;
        color:#fff;
        font-size:10px;
        text-align:center;
        top:5px;
        right:0px;
        display:<?=(($qt_pedidos)?'block':'none')?>;
    }
</style>
<div class="rodape">
    <div class="d-flex justify-content-between align-items-center rodape_area">
        <img home src="img/logo.png" />
        <div navegacao="home/index.php">
            <i class="fa-solid fa-house" <?=(($pdAtiva == 'home/index.php')?'style="color:#ffdb37;"':false)?>></i>
            <p <?=(($pdAtiva == 'home/index.php')?'style="color:#ffdb37;"':false)?>>Home</p>
        </div>

        <div navegacao="usuarios/perfil.php" class="telaPedido">
            <i class="fa-solid fa-user" <?=(($pdAtiva == 'usuarios/perfil.php')?'style="color:#ffdb37;"':false)?>></i>
            <p <?=(($pdAtiva == 'usuarios/perfil.php')?'style="color:#ffdb37;"':false)?>>Perfil</p>
        </div>
        
        <!-- <div navegacao="home/index.php" class="telaPedido">
            <i class="fa-solid fa-burger" <?=(($pdAtiva == 'home/index.php')?'style="color:#ffdb37;"':false)?>></i>
            <p <?=(($pdAtiva == 'home/index.php')?'style="color:#ffdb37;"':false)?>>Menu</p>
        </div> -->
        
        <div navegacao="pedido/resumo.php" class="telaPedido">
            <i class="fa-solid fa-bag-shopping" <?=(($pdAtiva == 'pedido/resumo.php')?'style="color:#ffdb37;"':false)?>></i>
            <p <?=(($pdAtiva == 'pedido/resumo.php')?'style="color:#ffdb37;"':false)?>>Pedido</p>
            <span class="itens_produtos_add"><?=$qt_pedidos?></span>
        </div>        

        <div navegacao="pedido/pagar.php">
            <i class="fa-solid fa-circle-dollar-to-slot" <?=(($pdAtiva == 'pedido/pagar.php')?'style="color:#ffdb37;"':false)?>></i>
            <p <?=(($pdAtiva == 'pedido/pagar.php')?'style="color:#ffdb37;"':false)?>>Pagar</p>
        </div> 
        
        <div navegacao="contatos/index.php">
            <i class="fa-solid fa-square-phone" <?=(($pdAtiva == 'contatos/index.php')?'style="color:#ffdb37;"':false)?>></i>
            <p <?=(($pdAtiva == 'contatos/index.php')?'style="color:#ffdb37;"':false)?>>Contato</p>
        </div>           
    </div>
</div>

<script>
    $(function(){

        $("img[home]").click(function(){
            Carregando();

            $.ajax({
                url:"home/index.php",
                type:"POST",
                data:{
                    historico:'.CorpoApp'
                },
                success:function(dados){
                    Carregando('none');
                    $(".CorpoApp").html(dados);
                }
            })

        });

        $("div[navegacao]").click(function(){
            Carregando();
            url = $(this).attr("navegacao");
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");
            $.ajax({
                url,
                type:"POST",
                data:{
                    idUnico,
                    codUsr,
                    historico:'.CorpoApp'
                },
                success:function(dados){
                    Carregando('none');
                    $(".CorpoApp").html(dados);
                }
            })

        });
    })
</script>