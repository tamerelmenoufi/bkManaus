<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
    // exit();

    $query = "select * from municipios where codigo = '{$_SESSION['filtro_relatorio_municipio']}'";
    $result = mysqli_query($con, $query);
    $d1 = mysqli_fetch_object($result);

?>

<style>
  div[tabela]{
    overflow:auto;
  }
</style>
<div class="col">
  <div class="m-3">
    <div class="row" style="margin-top:20px; margin-bottom:20px;">
      <div class="col-md-10">
        <h4>Relatórios e estatísticas <?=$d1->municipio?> (<?=$_SESSION['filtro_relatorio_tipo']?>)</h4>
      </div>
      <?php
      if(!$_GET['p']){
      ?>
      <div class="col-md-8">
        <a
          class="btn btn-warning"
          href="./print.php?u=<?=base64_encode("src/home/dashboard/tipo.php")?>"
          target='relatorio'
        >Imprimir</a>
        <button
          filtrar
          class="btn btn-primary"
          data-bs-toggle="offcanvas"
          href="#offcanvasDireita"
          role="button"
          aria-controls="offcanvasDireita"
        >Filtrar</button>
      </div>
      <?php
      }
      ?>
    </div>

  <!-- Pesquisa de satisfação Gráficos -->
    <div class="row" style="margin-top:20px; margin-bottom:20px;">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">Resumo Geral</h5>
          <div class="card-body">
            <div tabela="resumo" style="width:100%;"></div>
          </div>
        </div>
      </div>
    </div>

  <!-- Pesquisa de satisfação Gráficos -->
    <div class="row" style="margin-top:20px; margin-bottom:20px;">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">Identificação no mapa</h5>
            <div mapa="geral" style="width:100%; height:400px;"></div>
        </div>
      </div>
    </div>

  <!-- Pesquisa de satisfação Gráficos -->
    <div class="row" style="margin-top:20px; margin-bottom:20px;">

      <div class="col-md-3">
        <div class="card">
          <h5 class="card-header">Usuários por Zona</h5>
          <div class="card-body">
            <div grafico="zona_geral" style="width:100%;"></div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <h5 class="card-header">Zona Urbana</h5>
          <div class="card-body">
            <div grafico="urbana" style="width:100%;"></div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <h5 class="card-header">Zona Rural</h5>
          <div class="card-body">
            <div grafico="rural" style="width:100%;"></div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card">
          <h5 class="card-header">Evolução da Pesquisa</h5>
          <div class="card-body">
            <div grafico="pesquisa" style="width:100%;"></div>
          </div>
        </div>
      </div>


    </div>


    <!-- Pesquisa de satisfação Gráficos -->
    <!-- <div class="row" style="margin-top:20px; margin-bottom:20px;">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">Usuários por município</h5>
          <div class="card-body">
            <div grafico="municipios"></div>
          </div>
        </div>
      </div>
    </div> -->

    <!-- Pesquisa de satisfação Gráficos -->
    <!-- <div class="row" style="margin-top:20px; margin-bottom:20px;">
      <div class="col-md-12">
        <div class="card">
          <h5 class="card-header">Usuários na capital Manaus</h5>
          <div class="card-body">
            <div grafico="capital"></div>
          </div>
        </div>
      </div>
    </div> -->

    <div id="relatorio_especifico"></div>


  </div>
</div>







<script>
  $(function(){
    Carregando('none');
    $.ajax({
          url:"src/home/dashboard/relatorio_especifico/index.php",
          success:function(dados){
              $("#relatorio_especifico").html(dados);
          }
      })
    const Graficos = (r) => {
        $.ajax({
          url:`src/home/dashboard/graficos/tipo/${r.local}.php`,
          type:"POST",
          data:{
            rotulo:r.rotulo,
            local:r.local,
          },
          success:function(dados){
            $(`div[grafico="${r.local}"]`).html(dados);
          },
          error:function(){
            console.log('Erro');
          }
        });
    }

    $("div[grafico]").each(function(){
      local = $(this).attr("grafico");
      rotulo = $(this).parent('div').parent('div').children('h5').text();
      Graficos({local, rotulo});
    })



    const Tabelas = (r) => {
        $.ajax({
          url:`src/home/dashboard/tabelas/tipo/${r.local}.php`,
          type:"POST",
          data:{
            rotulo:r.rotulo,
            local:r.local,
          },
          success:function(dados){
            $(`div[tabela="${r.local}"]`).html(dados);
          },
          error:function(){
            console.log('Erro');
          }
        });
    }

    $("div[tabela]").each(function(){
      local = $(this).attr("tabela");
      rotulo = $(this).parent('div').parent('div').children('h5').text();
      Tabelas({local, rotulo});
    })


    $.ajax({
      url:`src/home/dashboard/mapas/tipo/geral.php`,
      type:"POST",
      success:function(dados){
        $(`div[mapa="geral"]`).html(dados);
      },
      error:function(){
        console.log('Erro');
      }
    });


    $("button[filtrar]").click(function(){
      Carregando();
      $.ajax({
          url:"src/home/dashboard/filtro.php",
          success:function(dados){
              $(".LateralDireita").html(dados);
          }
      })
    });


  })
</script>