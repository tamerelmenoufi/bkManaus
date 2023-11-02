<?php

    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['meta']) $_SESSION['meta'] = $_POST['meta'];

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

    $grupos = str_replace("|",",",$m->grupos);
    $grupos = explode(",",$grupos);
    $grupos = array_filter($grupos);
    $grupos = implode(", ", $grupos);

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
              $query = "select * from se where codigo in (".(($grupos)?:0).") order by meta desc, endereco asc";
              $result = mysqli_query($con, $query);
              while($d = mysqli_fetch_object($result)){
                if(in_array($d->situacao, ['c','f','n'])){
                    if($d->monitor_social == $m->usuario){
                        $cor = '#000';
                    }else{
                        $cor = 'green';
                    }
                }else{
                    $cor = '#ccc';
                }
              ?>
              <tr>
                <td style="color:<?=$cor?>">
                    <?=$d->nome?><br>
                    <small style="color:#a1a1a1"><?=str_replace("  ", " ",trim($d->endereco)).(($d->cep)?"- ".trim($d->cep):false)?></small>  
                </td>
              </tr>
              <?php
              }
              ?>
          </table>
        </div>        
      </div>
    </div>
</div>

<script>

  $(function(){
    Carregando('none');
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
  })

</script>