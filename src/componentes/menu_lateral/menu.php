<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
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
    <h5>Map Censo - AADESAM</h5>
    <?php
    if($_SESSION['ProjectSeLogin']->perfil == 'adm' or $_SESSION['ProjectSeLogin']->perfil == 'crd'){
    ?>
    <!-- <div class="row mb-1">
      <div class="col">
        <a url="src/dashboard/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-pie col-1"></i> <span class="col-11">Dashboard</span>
        </a>
      </div>
    </div> -->
    <?php
    }
    ?>
    <div class="row mb-1">
      <div class="col">
        <a url="src/relatorios/index1.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line col-1"></i> <span class="col-11">Relatórios</span>
        </a>
      </div>
    </div>
    <!-- <div class="row mb-1">
      <div class="col">
        <a url="src/relatorios/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-chart-line col-1"></i> <span class="col-11">Relatórios (Novo)</span>
        </a>
      </div>
    </div> -->
    <?php
    if($_SESSION['ProjectSeLogin']->perfil == 'adm' or $_SESSION['ProjectSeLogin']->perfil == 'sup' or $_SESSION['ProjectSeLogin']->perfil == 'crd' ){
    ?>
    <div class="row mb-1">
      <div class="col">
        <a url="src/usuarios/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-user col-1"></i> <span class="col-11">Usuários do Sistema</span>
        </a>
      </div>
    </div>
    <?php
    }
    if($_SESSION['ProjectSeLogin']->perfil == 'adm' or $_SESSION['ProjectSeLogin']->perfil == 'sup' ){
    ?>
    <div class="row mb-1">
      <div class="col">
        <a url="src/pacs/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-building col-1"></i> <span class="col-11">PACs</span>
        </a>
      </div>
    </div>
    <?php
    }

    if($_SESSION['ProjectSeLogin']->perfil == 'adm' ){
    ?>
    <div class="row mb-1">
      <div class="col">
        <a url="src/mensagens/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-regular fa-paper-plane col-1"></i> <span class="col-11">Mensagens</span>
        </a>
      </div>
    </div>
    <?php
    }    

    if($_SESSION['ProjectSeLogin']->perfil == 'adm' or $_SESSION['ProjectSeLogin']->perfil == 'crd'){
    ?>
    <div class="row mb-1">
      <div class="col">
        <a url="src/se/index.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-users-viewfinder col-1"></i> <span class="col-11">Beneficiários</span>
        </a>
      </div>
    </div>
    <?php
    }
    ?>
    <div class="row mb-1">
      <div class="col">
        <a href="./docs/form-se.pdf" target="formSocioEconimico" class="text-decoration-none">
          <i class="fa-regular fa-file-lines col-1"></i> <span class="col-11">Formulário Socioeconômico</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a href="./docs/form-ef.pdf" target="formEstruturaFamiliar" class="text-decoration-none">
          <i class="fa-regular fa-file-lines col-1"></i> <span class="col-11">Formulário Estrutura Familiar</span>
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a url="scripts/codigos.php" class="text-decoration-none" data-bs-dismiss="offcanvas" aria-label="Close">
          <i class="fa-solid fa-clipboard-list"></i> Lista de Códigos
        </a>
      </div>
    </div>

    <!-- <div class="row mb-1">
      <div class="col">
        <a href="./print.php?u=<?=base64_encode("src/se/se_print.php")?>" target="formSocioEconimico" class="text-decoration-none">
          <i class="fa-solid fa-list-ol"></i> Formulário Socioeconômico
        </a>
      </div>
    </div>

    <div class="row mb-1">
      <div class="col">
        <a href="./print.php?u=<?=base64_encode("src/se/ef_print.php")?>" target="formEstruturaFamiliar" class="text-decoration-none">
          <i class="fa-solid fa-list-ol"></i> Formulário Estrutura Familiar
        </a>
      </div>
    </div> -->

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