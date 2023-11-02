<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
?>
<div class="list-group">
<?php
    $query = "select * from pacs where situacao = '1' and deletado != '1'";
    $result = mysqli_query($con, $query);
    while($d = mysqli_fetch_object($result)){
?>
  <button pac="<?=$d->codigo?>" pac_nome="<?=$d->nome?>" type="button" class="list-group-item list-group-item-action"><i class="fa-solid fa-square" style="color:<?=$d->cor?>"></i> <?=$d->nome?></button>
<?php
    }
?>
</div>


<script>
    $(function(){
        $("button[pac]").click(function(){
            pac = $(this).attr("pac")
            pac_nome = $(this).attr("pac_nome")
            if(pac){
                $.confirm({
                    content:`Deseja realmente incluir o PAC ${pac_nome}?`,
                    title:false,
                    buttons:{
                        'SIM':function(){
                            Carregando()
                            $.ajax({
                                url:"src/usuarios/index.php",
                                type:"POST",
                                data:{
                                    pac,
                                    usu:'<?=$_POST['usu']?>',
                                    acao:'pac'
                                },
                                success:function(dados){
                                    Carregando('none')
                                    $("#paginaHome").html(dados);
                                    JanelaPACs.close()
                                }
                            })
                        },
                        'NÃO':function(){
                            
                        }
                    }
                })
            }
        })
    })
</script>