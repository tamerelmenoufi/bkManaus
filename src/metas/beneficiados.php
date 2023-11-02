<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['meta']) $_SESSION['meta'] = $_POST['meta'];


    if($_POST['acao'] == 'addBeneficiarios'){

      $quantidade = count($_POST['opcoes']);
      mysqli_query($con, "update se set meta = '0', monitor_social = '0' where meta = '{$_SESSION['meta']}' and situacao not in('c', 'f', 'n')");
      $opcoes = @implode(",", $_POST['opcoes']);
      mysqli_query($con, "update se set meta = '{$_SESSION['meta']}', monitor_social = '{$_POST['usuario']}' where codigo in ({$opcoes})");
      mysqli_query($con, "update metas set quantidade = '{$quantidade}', grupos = concat(grupos,'|','{$opcoes}') WHERE codigo = '{$_SESSION['meta']}'");
    }

    $query = "select a.*,
                     b.nome,
                     c.municipio as municipio_nome,
                     d.descricao as bairro_nome
            from metas a 
                    left join usuarios b on a.usuario = b.codigo 
                    left join municipios c on a.municipio = c.codigo 
                    left join bairros_comunidades d on a.bairro_comunidade = d.codigo 
            where a.codigo = '{$_SESSION['meta']}'";
    $result = mysqli_query($con, $query);
    $m = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Lista dos Beneficiados</h4>
<div class="col">
    <div class="row">
      <div class="col">

        <div class="card">
          <h5 class="card-header">Dados do Usuário</h5>
          <div class="card-body">
            <?=str_pad($m->codigo, 6, "0", STR_PAD_LEFT)?><br>
            <?=$m->nome?><br>
            <?=$m->municipio_nome?><br>
            <?=$m->bairro_nome?>
          </div>
        </div>
        
        <h6 style="position:absolute; top:270px; left:0; right:0; width:100%; background-color:#fff; padding:20px;">
          Dados do Beneficiados<br>
          <input type="text" class="form-control ph-3" id="pesquisa" placeholder="Filtre aqui sua busca" />
        </h6>
        
        <div style="position:absolute; top:350px; bottom:60px; padding-left:20px; padding-right:20px; left:0; right:0; overflow-y: scroll;">
          <table class="table table-hover">
              <?php
              $query = "select * from se where municipio = '{$m->municipio}' and bairro_comunidade = '{$m->bairro_comunidade}' and local = '{$m->zona}' and (meta = '{$m->codigo}' or meta = '0') order by meta desc, endereco asc";
              $result = mysqli_query($con, $query);
              while($d = mysqli_fetch_object($result)){
              ?>
              <tr>
                  <td>

                  <div class="mb-3 form-check">
                    <input 
                            type="checkbox" 
                            <?=(($d->meta == $_SESSION['meta'])?'checked':false)?> 
                            <?=((in_array($d->situacao,['c', 'n', 'f'])?'disabled':false))?> 
                            class="form-check-input opcoes" 
                            value="<?=$d->codigo?>" 
                            id="opc<?=$d->codigo?>"
                    >
                    <label class="form-check-label filtroDados" for="opc<?=$d->codigo?>">
                      <?=$d->nome?><br>
                      <small style="color:#a1a1a1"><?=str_replace("  ", " ",trim($d->endereco)).(($d->cep)?"- ".trim($d->cep):false)?></small>
                    </label>
                  </div>

                    
                  </td>
              </tr>
              <?php
              }
              ?>
          </table>
        </div>
        
        <button salvarMeta usuario="<?=$m->usuario?>" style="position:absolute; bottom:10px; right:30px;" class="btn btn-success">Salvar</button>
      </div>
    </div>
</div>

<script>

  $(function(){
    Carregando('none');

    console.log(`<?=$query?>`)


    $('#pesquisa').keyup(function(e) {
      var termo = $('#pesquisa').val().toUpperCase();
      $('.filtroDados').each(function() { 
          console.log($(this).text().toUpperCase())
          if($(this).text().toUpperCase().indexOf(termo) === -1) {
              $(this).parent("div").parent("td").parent("tr").hide();
          } else {
            $(this).parent("div").parent("td").parent("tr").show();
          }
      });
    });


    $("button[salvarMeta]").click(function(){
      usuario = $(this).attr("usuario")
      opcoes = [];
      $(".opcoes").each(function(){
        if($(this).prop("checked") == true){
          opcoes.push($(this).val())
        }
      });
      Carregando();
      $.ajax({
        url:"src/metas/beneficiados.php",
        type:"POST",
        data:{
          opcoes,
          usuario,
          acao:"addBeneficiarios"
        },
        success:function(dados){
          $(".LateralDireita").html(dados);
          $.ajax({
            url:"src/metas/index.php",
            success:function(dados){
              $("#paginaHome").html(dados);
              
            }
          })
        }
      })
    });
  })

</script>