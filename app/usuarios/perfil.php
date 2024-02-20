<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }
    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    $query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
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
        background-color:#ffc63a;
        color:#670600;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }


    .home_corpo{
        position: absolute;
        top:100px;
        bottom:80px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:80px;
    }


</style>

<div class="barra_topo">
    <h2>Perfil</h2>
</div>


<div class="home_corpo">
    <div style="padding:3; text-align:center; color:#a1a1a1; font-size:12px;">ID: <?=$_SESSION['idUnico']?></div>
    <div class="dados_pessoais"></div>
    <div class="dados_enderecos"></div>
    <div class="m-2">
        <button type="button" class="btn btn-outline-danger w-100 desconectar">Desconectar</button>
    </div>
</div>   
<div class="home_rodape"></div>

<script>

$(function(){

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");

    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });

    $.ajax({
        url:"topo/topo.php",
        type:"POST",
        data:{
            idUnico,
            codUsr
        },  
        success:function(dados){
            $(".barra_topo").append(dados);
        }
    });

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");

    $.ajax({
        url:"usuarios/principal.php",
        type:"POST",
        data:{
            idUnico,
            codUsr
        },
        success:function(dados){
            $(".dados_pessoais").html(dados);
        }
    });    

    $(".desconectar").click(function(){
        $.confirm({
            title:"Desconcetar",
            type:"red",
            content:"Deseja realmente desconectar do Aplicativo?",
            buttons:{
                confirm:{
                    text:"Sim",
                    btnClass:'btn-danger',
                    action:function(){
                        localStorage.removeItem("codUsr");
                        window.location.href='./?s=1';
                    }
                },
                cancel:{
                    text:'Não',
                    btnClass:'btn-warning',
                    action:function(){

                    }
                }
            },
            columnClass:'col-12'
        })
    })

})

</script>