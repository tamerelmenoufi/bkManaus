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
        background-color:#0f34d3;
        color:#f9de37;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .topo > .voltar{
        color:#f9de37!important;
    }

    .topo > .dados{
        color:#fff!important;
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
    <h2>Contato</h2>
</div>

<div class="home_corpo">
<div class="row">
        <div class="col">
            Para dúvidas, sugestões ou reclamações, entre em contato conosco utilizando um dos contatos a seguinte.
        </div>
    </div>
    <div class="row">
        <div class="col">
            <i class="fa-brands fa-whatsapp"></i> +55 92 986123301
        </div>
    </div>
    <div class="row">
        <div class="col">
            <i class="fa-solid fa-at"></i> atendimento@bkmanaus.com.br
        </div>
    </div>
    <div class="row">
        <div class="col">
            <i class="fa-solid fa-house"></i> https://bkmanaus.com.br
        </div>
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


})

	

</script>