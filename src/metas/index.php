<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['usuario']) $_SESSION['usuario'] = $_POST['usuario'];

    if($_POST['delete']){
      // $query = "delete from metas where codigo = '{$_POST['delete']}'";
      $query = "update metas set deletado = '1' where codigo = '{$_POST['delete']}'";
      mysqli_query($con, $query);
      sisLog(
        [
            'query' => $query,
            'file' => $_SERVER["PHP_SELF"],
            'sessao' => $_SESSION,
            'registro' => $_POST['delete']
        ]
    );
      mysqli_query($con, "update se set meta = '0', monitor_social = '0' where meta = '{$_POST['delete']}' and situacao not in('c', 'f', 'n')");
    }

    if($_POST['situacao']){
      $query = "update metas set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
      mysqli_query($con, $query);
      sisLog(
        [
            'query' => $query,
            'file' => $_SERVER["PHP_SELF"],
            'sessao' => $_SESSION,
            'registro' => $_POST['situacao']
        ]
    );
      exit();
    }

    $q = "select * from usuarios where codigo = '{$_SESSION['usuario']}'";
    $u = mysqli_fetch_object(mysqli_query($con, $q));
?>

<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">
            <div class="d-flex justify-content-between">
              <span>Lista de Metas (<?=$u->nome?>)</span>
              <button class="btn btn-secondary btn-sm voltar">Voltar</button>
            </div>
          </h5>
          <div class="card-body">
            <div style="display:flex; justify-content:end">
                <button
                    novoCadastro
                    class="btn btn-success"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                >Novo</button>
            </div>


            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Código</th>
                  <th scope="col">Município</th>
                  <th scope="col">Bairro/Comunidade</th>
                  <th scope="col">Zona</th>
                  <th scope="col">Data</th>
                  <th scope="col">Situação</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select a.*, 
                                   b.municipio as municipio_nome, 
                                   c.descricao as bairro_comunidade_nome,
                                   (select count(*) from se where meta = a.codigo) as beneficiados
                              from metas a 
                                   left join municipios b on a.municipio = b.codigo 
                                   left join bairros_comunidades c on a.bairro_comunidade = c.codigo 
                              where usuario = '{$_SESSION['usuario']}' and a.deletado != '1'
                              order by a.codigo desc";
                  $result = mysqli_query($con, $query);
                  while($d = mysqli_fetch_object($result)){
                ?>
                <tr>
                  <td><?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?></td>
                  <td><?=$d->municipio_nome?></td>
                  <td><?=$d->bairro_comunidade_nome?></td>
                  <td><?=$d->zona?></td>
                  <td><?=dataBr($d->data)?></td>
                  <td>

                  <div class="form-check form-switch">
                    <input class="form-check-input situacao" type="checkbox" <?=(($d->situacao)?'checked':false)?> usuario="<?=$d->codigo?>">
                  </div>

                  </td>
                    <td>

                      <button
                        class="btn btn-primary"
                        beneficiados="<?=$d->codigo?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                      >
                        <?=$d->beneficiados?> Beneficiado(s)
                      </button>

                      <button
                        class="btn btn-primary"
                        edit="<?=$d->codigo?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        <?=(($d->quantidade)?'disabled':false)?>
                      >
                        Editar
                      </button>

                      <button 
                        class="btn btn-danger" 
                        delete="<?=$d->codigo?>" 
                        <?=(($d->quantidade)?'disabled':false)?> 
                      >
                        Excluir
                      </button>

                  </td>
                </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>




















    <div class="row mt-3">
      <div class="col">
        <div class="card">
          <h5 class="card-header">
            <div class="d-flex justify-content-between">
              <span>Lista de Metas expiradas (<?=$u->nome?>)</span>
            </div>
          </h5>
          <div class="card-body">

            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Código</th>
                  <th scope="col">Município</th>
                  <th scope="col">Bairro/Comunidade</th>
                  <th scope="col">Zona</th>
                  <th scope="col">Data</th>
                  <th scope="col">Previsão de metas</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select a.*, 
                                   b.municipio as municipio_nome, 
                                   c.descricao as bairro_comunidade_nome
                              from metas a 
                                   left join municipios b on a.municipio = b.codigo 
                                   left join bairros_comunidades c on a.bairro_comunidade = c.codigo 
                              where usuario = '{$_SESSION['usuario']}' and a.deletado = '1'
                              order by a.codigo desc";
                  $result = mysqli_query($con, $query);
                  while($d = mysqli_fetch_object($result)){

                    $grupos = str_replace("|",",",$d->grupos);
                    $grupos = explode(",",$grupos);
                    $grupos = array_filter($grupos);
                    $grupos = implode(", ", $grupos);
                    list($qt) = mysqli_fetch_row(mysqli_query($con, "select count(*) from se where codigo in(".(($grupos)?:0).")"));
                    
                ?>
                <tr>
                  <td><?=str_pad($d->codigo, 6, "0", STR_PAD_LEFT)?></td>
                  <td><?=$d->municipio_nome?></td>
                  <td><?=$d->bairro_comunidade_nome?></td>
                  <td><?=$d->zona?></td>
                  <td><?=dataBr($d->data)?></td>
                  <td>
                      <button
                        class="btn btn-warning"
                        beneficiados_historico="<?=$d->codigo?>"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        <?=((!$qt)?'disabled':false)?>
                      >
                        <?=$qt?> Beneficiado(s)
                      </button>

                  </td>
                </tr>
                <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>


<script>
    $(function(){
        Carregando('none');
        $("button[novoCadastro]").click(function(){
          $(".LateralDireita").html('');
            $.ajax({
                url:"src/metas/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        $("button[edit]").click(function(){
            meta = $(this).attr("edit");
            $(".LateralDireita").html('');
            $.ajax({
                url:"src/metas/form.php",
                type:"POST",
                data:{
                  meta
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        $("button[beneficiados]").click(function(){
            meta = $(this).attr("beneficiados");
            $(".LateralDireita").html('');
            $.ajax({
                url:"src/metas/beneficiados.php",
                type:"POST",
                data:{
                  meta
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })


        $("button[beneficiados_historico]").click(function(){
            meta = $(this).attr("beneficiados_historico");
            $(".LateralDireita").html('');
            $.ajax({
                url:"src/metas/beneficiados_historico.php",
                type:"POST",
                data:{
                  meta
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })




        $(".voltar").click(function(){
          Carregando();
            $.ajax({
                url:"src/usuarios/index.php",
                type:"POST",
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })
        })


        $(".situacao").change(function(){

            situacao = $(this).attr("usuario");
            opc = false;

            if($(this).prop("checked") == true){
              opc = '1';
            }else{
              opc = '0';
            }


            $.ajax({
                url:"src/metas/index.php",
                type:"POST",
                data:{
                    situacao,
                    opc
                },
                success:function(dados){
                    // $("#paginaHome").html(dados);
                }
            })

        });


        $("button[delete]").click(function(){
            deletar = $(this).attr("delete");
            $.confirm({
                content:"Deseja realmente excluir o cadastro ?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"src/metas/index.php",
                            type:"POST",
                            data:{
                                delete:deletar
                            },
                            success:function(dados){
                                $("#paginaHome").html(dados);
                            }
                        })
                    },
                    'NÃO':function(){

                    }
                }
            });

        })

    })
</script>