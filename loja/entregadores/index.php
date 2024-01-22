<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['delete']){
      // $query = "delete from entregadores where codigo = '{$_POST['delete']}'";
      $query = "update entregadores set deletado = '1' where codigo = '{$_POST['delete']}'";
      sisLog($query);
    }

    if($_POST['situacao']){
      $query = "update entregadores set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
      sisLog($query);
      exit();
    }

    if($_POST['filtro'] == 'filtrar'){
      $_SESSION['usuarioBusca'] = $_POST['campo'];
    }elseif($_POST['filtro']){
      $_SESSION['usuarioBusca'] = false;
    }

    if($_SESSION['usuarioBusca']){
      $busca = str_replace( '.', '', str_replace('-', '', $_SESSION['usuarioBusca']));
      $where = " and nome like '%{$busca}%' or REPLACE( REPLACE( cpf, '.', '' ), '-', '' ) = '{$busca}' or REPLACE( REPLACE( telefone, '.', '' ), '-', '' ) = '{$busca}'";
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
  .label{
    font-size:10px;
    color:#a1a1a1;
  }
</style>

  <div class="col">
    <div class="m-3">

      <div class="row">
        <div class="col">
          <div class="card">
            <h5 class="card-header">Lista de Entregadores</h5>
            <div class="card-body" style="position:relative; overflow-y:auto">
            
            <div class="" style="position:relative; width:100%;">
              <div class="d-flex justify-content-between mb-3">

                  <div class="row">
                    <div class="col-12 mb-2">
                      <div class="input-group">
                        <label class="input-group-text" for="inputGroupFile01">Buscar por </label>
                        <input campoBusca type="text" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
                      </div>
                    </div>
                    <div class="col-12 mb-2">
                      <button filtro="filtrar" class="btn btn-outline-secondary w-100" type="button">Buscar</button>
                    </div>
                    <div class="col-12 mb-2">
                      <button filtro="limpar" class="btn btn-outline-danger w-100" type="button">limpar</button>
                    </div>
                    <div class="col-12 mb-2">
                      <button
                          novoCadastro
                          class="btn btn-success btn-sm w-100"
                          data-bs-toggle="offcanvas"
                          href="#offcanvasDireita"
                          role="button"
                          aria-controls="offcanvasDireita"
                      >Novo</button>                      
                    </div>
                  </div>
              </div>


              <?php
                    $query = "select * from entregadores where deletado != '1' and loja = '{$_POST['loja']}' {$where} order by nome asc";
                    $result = sisLog($query);
                    
                    while($d = mysqli_fetch_object($result)){
                  ?>
                  <div class="card mb-3 p-3">
                      <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                          <div class="form-check form-switch">
                            <input class="form-check-input situacao" type="checkbox" <?=(($d->situacao)?'checked':false)?> situacao="<?=$d->codigo?>">
                            Situação
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                          <label class="label">Nome</label>
                          <div><?=$d->nome?></div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                        <label class="label">CPF</label>
                        <div><?=$d->cpf?></div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                        <label class="label">Telefone</label>
                        <div><?=$d->telefone?></div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6 p-2">
                          <button
                            class="btn btn-primary w-100"
                            edit="<?=$d->codigo?>"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"
                          >
                            Editar
                          </button>
                        </div>
                        <div class="col-6 p-2">
                          <button class="btn btn-danger w-100" delete="<?=$d->codigo?>">
                            Excluir
                          </button>
                        </div>
                      </div>
                    </div>
                  <?php
                    }
                  ?>

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
console.log($(".CorpoApp").height())

        $(".card-body").css("height", ($(".CorpoApp").height() - 150))

        $("button[novoCadastro]").click(function(){
            loja = localStorage.getItem("loja");
            $.ajax({
                url:"entregadores/form.php",
                type:"POST",
                data:{
                  loja
                },
                success:function(dados){
                  $(".popupPalco").html(dados);
                }
            })
        })

        

        $("button[filtro]").click(function(){
          filtro = $(this).attr("filtro");
          campo = $("input[campoBusca]").val();
          loja = localStorage.getItem("loja");

          $.ajax({
              url:"entregadores/index.php",
              type:"POST",
              data:{
                  filtro,
                  campo,
                  loja
              },
              success:function(dados){
                $(".popupPalco").html(dados);
              }
          })
        })


        $("button[edit]").click(function(){
            cod = $(this).attr("edit");
            loja = localStorage.getItem("loja");
            $.ajax({
                url:"entregadores/form.php",
                type:"POST",
                data:{
                  cod,
                  loja
                },
                success:function(dados){
                  $(".popupPalco").html(dados);
                }
            })
        })

        

        $("button[delete]").click(function(){
            deletar = $(this).attr("delete");
            loja = localStorage.getItem("loja");

            $.confirm({
                content:"Deseja realmente excluir o cadastro ?",
                title:false,
                buttons:{
                    'SIM':function(){
                        $.ajax({
                            url:"entregadores/index.php",
                            type:"POST",
                            data:{
                                delete:deletar,
                                loja
                            },
                            success:function(dados){
                              $(".popupPalco").html(dados);
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
            loja = localStorage.getItem("loja");
            opc = false;

            if($(this).prop("checked") == true){
              opc = '1';
            }else{
              opc = '0';
            }


            $.ajax({
                url:"entregadores/index.php",
                type:"POST",
                data:{
                    situacao,
                    opc,
                    loja
                },
                success:function(dados){
                    // $("#paginaHome").html(dados);
                }
            })

        });

    })
</script>