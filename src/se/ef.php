<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['cod']) $_SESSION['se_codigo'] = $_POST['cod'];

?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .tab-content{
        border-right:1px #ccc solid;
        border-bottom:1px #ccc solid;
        border-left:1px #ccc solid;
        padding:20px;
        position: absolute;
        bottom:10px;
        top:105px;
        left:16px;
        right:15px;
        overflow:auto;
    }
</style>
<h4 class="Titulo<?=$md5?>">Estrutura Familiar</h4>


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button
            EstruturaFamiliarList
            class="nav-link active"
            id="lista-tab"
            data-bs-toggle="tab"
            data-bs-target="#EstruturaFamiliar"
            type="button"
            role="tab"
            aria-controls="EstruturaFamiliar"
            aria-selected="true"
            estruturaFamiliar="src/se/ef_lista.php"
    >Lista de cadastros</button>
  </li>
  <li class="nav-item" role="presentation">
    <button
            EstruturaFamiliarForm
            class="nav-link"
            id="form-tab"
            data-bs-toggle="tab"
            data-bs-target="#EstruturaFamiliar"
            type="button"
            role="tab"
            aria-controls="EstruturaFamiliar"
            aria-selected="false"
            estruturaFamiliar="src/se/ef_form.php"
    >Formulário</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div
        class="tab-pane fade show active"
        id="EstruturaFamiliar"
        role="tabpanel"
        aria-labelledby="lista-tab"
        tabindex="0"
    ></div>
</div>

    <script>
        $(function(){

            $.ajax({
                url:"src/se/ef_lista.php",
                success:function(dados){
                    $("#EstruturaFamiliar").html(dados);
                },
                error:function(erro){
                    Carregando('none');
                    // $.alert('Ocorreu um erro!' + erro.toString());
                    //dados de teste
                }
            });

            $("button[estruturaFamiliar]").click(function(){
                url = $(this).attr("estruturaFamiliar");
                target = $(this).attr("data-bs-target");
                Carregando();
                $.ajax({
                    url,
                    success:function(dados){
                        $(target).html(dados);
                    },
                    error:function(erro){
                        Carregando('none');
                        // $.alert('Ocorreu um erro!' + erro.toString());
                        //dados de teste
                    }
                });
            });

        })
    </script>