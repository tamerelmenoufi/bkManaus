<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
  a[url]{
    cursor:pointer;
  }
</style>
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <img src="img/logo_h.png" style="height:60px;" alt="">
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <h5>Burger King - Manaus</h5>
  
    <div class="row mb-1">
      <div class="col">
        <a url="src/dashboard/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line col-1"></i> <span class="col-11">Dashboard</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/usuarios/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Usuários do Sistema</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/entregadores/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Entregadores</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/lojas/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Lojas</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/categorias/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Categorias de Produtos</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="src/categorias_itens/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Categorias de Itens</span>
        </a>
      </div>
    </div>


    <div class="row mb-1">
      <div class="col">
        <a url="src/combos/index.php?categoria=8" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Combos</span>
        </a>
      </div>
    </div>

  </div>
</div>

<script>
  $(function(){
    $("a[url]").click(function(){
      Carregando();
      url = $(this).attr("url");
      $.ajax({
        url,
        success:function(dados){
          $("#paginaHome").html(dados);
        }
      });
    });
  })
</script>