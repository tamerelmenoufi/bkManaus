<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['categoria']){
      $_SESSION['categoria'] = $_POST['categoria'];
    }

    if($_POST['delete']){
      // $query = "delete from produtos where codigo = '{$_POST['delete']}'";
      $query = "update produtos set deletado = '1' where codigo = '{$_POST['delete']}'";
      sisLog($query);
    }

    if($_POST['situacao']){
      $query = "update produtos set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
      sisLog($query);
      exit();
    }


    if($_POST['filtro'] == 'filtrar'){
      $_SESSION['usuarioBusca'] = $_POST['campo'];
    }elseif($_POST['filtro']){
      $_SESSION['usuarioBusca'] = false;
    }

    if($_SESSION['usuarioBusca']){
      $where = " and produto like '%{$_SESSION['usuarioBusca']}%' ";
    }



?>
<style>
  .btn-perfil{
    padding:5px;
    border-radius:8px;
    color:#fff;
    background-color:#a1a1a1;
    cursor: pointer;
  }
  td, th{
    white-space: nowrap;
  }
</style>
<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Lista de Usuários</h5>
          <div class="card-body">
            <div class="d-none d-sm-block d-md-block">
              <div class="d-flex justify-content-between mb-3">

                  <div class="input-group">
                    <label class="input-group-text" for="inputGroupFile01">Buscar por </label>
                    <input campoBusca type="text" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
                    <button filtro="filtrar" class="btn btn-outline-secondary" type="button">Buscar</button>
                    <button filtro="limpar" class="btn btn-outline-danger" type="button">limpar</button>
                  </div>


                  <button
                      categorias
                      class="btn btn-warning btn-sm"
                      style="margin-left:20px;"
                  >Categorias</button>

                  <button
                      novoCadastro
                      class="btn btn-success btn-sm"
                      data-bs-toggle="offcanvas"
                      href="#offcanvasDireita"
                      role="button"
                      aria-controls="offcanvasDireita"
                      style="margin-left:20px;"
                  >Novo</button>


              </div>
            </div>

            <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Produto</th>
                  <th scope="col">Valor Individual</th>
                  <th scope="col">Valor no Combo</th>
                  <th scope="col">Situação</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select * from produtos where deletado != '1' and categoria = '{$_SESSION['categoria']}' {$where} order by produto asc";
                  $result = sisLog($query);
                  
                  while($d = mysqli_fetch_object($result)){
                ?>
                <tr>
                  <td style='width:100%'><?=$d->produto?></td>
                  <td><?=$d->valor?></td>
                  <td><?=$d->valor_combo?></td>
                  <td>

                  <div class="form-check form-switch">
                    <input class="form-check-input situacao" type="checkbox" <?=(($d->codigo == 1)?'disabled':false)?> <?=(($d->situacao)?'checked':false)?> situacao="<?=$d->codigo?>">
                  </div>

                  </td>
                  <td>
                    <button
                      class="btn btn-primary"
                      edit="<?=$d->codigo?>"
                      data-bs-toggle="offcanvas"
                      href="#offcanvasDireita"
                      role="button"
                      aria-controls="offcanvasDireita"
                    >
                      Editar
                    </button>
                    <button class="btn btn-danger" delete="<?=$d->codigo?>">
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
    </div>

  </div>
</div>


<script>
    $(function(){
        Carregando('none');

        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"src/produtos/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })


        $("button[categorias]").click(function(){
            $.ajax({
                url:"src/categorias/index.php",
                success:function(dados){
                  $("#paginaHome").html(dados);
                }
            })
        })

      
        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[campoBusca]").val();
          $.ajax({
              url:"src/produtos/index.php",
              type:"POST",
              data:{
                  filtro,
                  campo
              },
              success:function(dados){
                  $("#paginaHome").html(dados);
              }
          })
        })


        $("button[edit]").click(function(){
            cod = $(this).attr("edit");
            $.ajax({
                url:"src/produtos/form.php",
                type:"POST",
                data:{
                  cod
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })

        

        $("button[delete]").click(function(){
            deletar = $(this).attr("delete");
            $.confirm({
                content:"Deseja realmente excluir o cadastro ?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"src/produtos/index.php",
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


        $(".situacao").change(function(){

            situacao = $(this).attr("situacao");
            opc = false;

            if($(this).prop("checked") == true){
              opc = '1';
            }else{
              opc = '0';
            }


            $.ajax({
                url:"src/produtos/index.php",
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

    })
</script>