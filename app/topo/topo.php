<?php
include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>

<style>
    .topo{
        position:absolute;
        top:0;
        width:100%;
        background:transparent;
        height:100px;
        z-index:2;
    }
    .topo > .voltar{
        position:absolute;
        bottom:10px;
        left:15px;
        color:#000;
        font-size:30px;
        color:#c45018;
        cursor:pointer;
    }

    
</style>
<div class="topo">
    <i class="voltar fa-solid fa-arrow-left"></i>
</div>
<script>
    $(function(){

        $(".voltar").click(function(){
            Carregando();
            $.ajax({
                url:"lib/voltar.php",
                dataType:"JSON",
                success:function(dados){
                    var data = $.parseJSON(dados.dt);
                    $.ajax({
                        url:dados.pg,
                        type:"POST",
                        data,
                        success:function(retorno){
                            $(`${dados.tg}`).html(retorno);
                            Carregando('none');
                        }
                    })
                }
              })
        })
        
    })
</script>