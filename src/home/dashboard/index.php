<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");




    if($_SESSION['filtro_relatorio_bairro_comunidade']){
        $url = 'src/home/dashboard/bairro_comunidade.php';
    }elseif($_SESSION['filtro_relatorio_tipo']){
        $url = 'src/home/dashboard/tipo.php';
    }elseif($_SESSION['filtro_relatorio_municipio']){
        $url = 'src/home/dashboard/municipio.php';
    }else{
        $url = 'src/home/dashboard/geral.php';
    }


?>

<script>
  $(function(){

    $.ajax({
      url:'<?=$url?>',
      success:function(dados){
        $("#paginaHome").html(dados);
      },
      error:function(){
        console.log('Erro');
      }
    });

  })
</script>