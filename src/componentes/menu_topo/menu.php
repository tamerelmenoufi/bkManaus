<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
?>
<style>
  .MenuLogin{
    min-width:250px;
    margin:0 10px 0 10px;
  }
</style>

<nav class="navbar navbar-expand bg-light">
  <div class="container-fluid">
    <div data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
      <img src="img/logo_h.png" style="height:40px; margin-right:20px;" >
      <i class="fa-solid fa-bars"></i>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarScroll">

        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
          <li class="nav-item">

          </li>
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <?=$_SESSION['ProjectSeLogin']->nome?> <i class="fa-solid fa-user"></i>
                </a>
                <ul class="dropdown-menu  dropdown-menu-end" aria-labelledby="navbarScrollingDropdown">
                    <li class="MenuLogin">
                      <ul class="list-group  list-group-flush">
                        <!-- <li class="list-group-item" aria-disabled="true">
                          <i class="fa-solid fa-user"></i> Dados Pessoais
                        </li>
                        <a class="list-group-item" href='#'>
                          <i class="fa-solid fa-key"></i> Senha de Acesso
                        </a>
                        <li class="list-group-item">
                          <i class="fa-solid fa-calendar-check"></i> Atividades
                        </li> -->
                        <a class="list-group-item" href='?s=1'>
                          <i class="fa-solid fa-right-from-bracket"></i> Sair
                        </a>
                      </ul>
                    </li>
                </ul>
            </li>
        </ul>

    </div>
  </div>
</nav>


<script>
  $(function(){

      setInterval(() => {
        $("div[conexao]").remove();
        $.ajax({
          url:'lib/sessoes.php',
          success:function(dados){
              // console.log(dados);
              $("body").attr("session", dados);
              $.ajax({
                  url:'lib/sessoes.php',
                  type:"POST",
                  data:{
                      dados
                  },
                  success:function(dados){
                      // console.log(dados);
                      $("body").attr("session", dados);
                  },
                  error:function(){
                    $("body").append("<div conexao style='position:fixed; bottom:5px; right:20px; width:200px; height:40px; padding:20px; color:#fff; background:#f0f0f0';>Erro de conexão!</div>");
                  }
              });
          },
          error:function(){
            $("body").append("<div conexao style='position:fixed; bottom:5px; right:20px; width:200px; height:40px; padding:20px; color:#fff; background:#f0f0f0';>Erro de conexão!</div>");
          }
        });
      }, 60000);
  })
</script>