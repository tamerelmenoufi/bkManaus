<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    function Pefil($p){
      $Perfil = [
        'adm' => 'Administrador',
        'sup' => 'Supervisor',
        'crd' => 'Coordenador',
        'usr' => 'Agente',
      ];
      return $Perfil[$p];
    }

    if($_POST['delete']){
      // $query = "delete from usuarios where codigo = '{$_POST['delete']}'";
      $query = "update usuarios set deletado = '1' where codigo = '{$_POST['delete']}'";
      sisLog($query);
    }

    if($_POST['situacao']){
      $query = "update usuarios set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
      sisLog($query);
      exit();
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
</style>
<div class="col">
  <div class="m-3">

    <div class="row">
      <div class="col">
        <div class="card">
          <h5 class="card-header">Lista de Usuários</h5>
          <div class="card-body">
            <div class="d-flex justify-content-between">
                <div class="input-group mb-3">
                  <label class="input-group-text" for="inputGroupFile01">Buscar por </label>
                  <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" rotulo_busca aria-expanded="false"><?=((!$_SESSION['usuarioBuscaCampo'] or $_SESSION['usuarioBuscaCampo'] == 'nome')?'Nome':(($_SESSION['usuarioBuscaCampo'] == 'perfil')?'Perfil':(($_SESSION['usuarioBuscaCampo'] == 'pac')?'PAC':'CPF')))?></button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" opcao_busca="Nome">Nome</a></li>
                    <li><a class="dropdown-item" href="#" opcao_busca="CPF">CPF</a></li>
                    <li><a class="dropdown-item" href="#" opcao_busca="Perfil">Perfil</a></li>
                  </ul>
                  <input type="text" texto_busca style="display:<?=(($_SESSION['usuarioBuscaCampo'] == 'perfil' or $_SESSION['usuarioBuscaCampo'] == 'pac')?'none':'block')?>" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
                  <select busca_perfil class="form-control" style="display:<?=(($_SESSION['usuarioBuscaCampo'] != 'perfil')?'none':'block')?>">
                    <option value="adm" <?=(($_SESSION['usuarioBusca'] == 'adm')?'selected':false)?>>Administrador</option>
                    <option value="sup" <?=(($_SESSION['usuarioBusca'] == 'sup')?'selected':false)?>>Supervisor</option>
                    <option value="crd" <?=(($_SESSION['usuarioBusca'] == 'crd')?'selected':false)?>>Coordenador</option>
                    <option value="usr" <?=(($_SESSION['usuarioBusca'] == 'usr')?'selected':false)?>>Agente</option>
                  </select>

                  <button filtrar class="btn btn-outline-secondary" type="button">Buscar</button>
                  <button limpar class="btn btn-outline-danger" type="button">limpar</button>
                </div>

                <?php
                if(in_array($_SESSION['ProjectSeLogin']->perfil, ['adm', 'sup'])){
                ?>
                <button
                    novoCadastro
                    class="btn btn-success btn-sm"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                    style="margin-left:20px;"
                >Novo</button>
                <?php
                }
                ?>
            </div>


            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th scope="col">Nome</th>
                  <th scope="col">CPF</th>
                  <th scope="col">Situação</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select * from usuarios where a.deletado != '1' order by a.nome asc";
                  $result = sisLog($query);
                  
                  while($d = mysqli_fetch_object($result)){
                ?>
                <tr>
                  <td><?=$d->nome?></td>
                  <td><?=$d->cpf?></td>
                  <td>

                  <div class="form-check form-switch">
                    <input class="form-check-input situacao" type="checkbox" <?=(($d->codigo == 1)?'disabled':false)?> <?=(($d->situacao)?'checked':false)?> usuario="<?=$d->codigo?>">
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


<script>
    $(function(){
        Carregando('none');
        $("button[novoCadastro]").click(function(){
            $.ajax({
                url:"src/usuarios/form.php",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })
        })



        $("button[edit]").click(function(){
            cod = $(this).attr("edit");
            $.ajax({
                url:"src/usuarios/form.php",
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
                            url:"src/usuarios/index.php",
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

            situacao = $(this).attr("usuario");
            opc = false;

            if($(this).prop("checked") == true){
              opc = '1';
            }else{
              opc = '0';
            }


            $.ajax({
                url:"src/usuarios/index.php",
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