<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");


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
      mysqli_query($con, $query);
      sisLog(
        [
            'query' => $query,
            'file' => $_SERVER["PHP_SELF"],
            'sessao' => $_SESSION,
            'registro' => $_POST['delete']
        ]
    );
    }

    if($_POST['situacao']){
      $query = "update usuarios set situacao = '{$_POST['opc']}' where codigo = '{$_POST['situacao']}'";
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

    if($_POST['acao'] == 'pac'){
      $query = "update usuarios set pac = '{$_POST['pac']}' where codigo = '{$_POST['usu']}'";
      mysqli_query($con, $query);
      sisLog(
        [
            'query' => $query,
            'file' => $_SERVER["PHP_SELF"],
            'sessao' => $_SESSION,
            'registro' => $_POST['usu']
        ]
    );
    }

    if($_POST['acao'] == 'filtro'){
      $_SESSION['usuarioBuscaCampo'] = $_POST['campo'];
      $_SESSION['usuarioBusca'] = $_POST['busca'];
    }
    if($_POST['acao'] == 'limpar'){
      $_SESSION['usuarioBuscaCampo'] = false;
      $_SESSION['usuarioBusca'] = false;      
    }

    $where = false;
    if($_SESSION['usuarioBuscaCampo'] and $_SESSION['usuarioBusca']){
      if($_SESSION['usuarioBuscaCampo'] == 'pac'){
        $where = " and (a.{$_SESSION['usuarioBuscaCampo']} = '{$_SESSION['usuarioBusca']}' or b.{$_SESSION['usuarioBuscaCampo']} = '{$_SESSION['usuarioBusca']}')";
      }else{
        $where = " and a.{$_SESSION['usuarioBuscaCampo']} like '%{$_SESSION['usuarioBusca']}%'";
      }
    }

    if($_SESSION['ProjectSeLogin']->perfil == 'sup'){
      $where .= " and a.perfil = 'crd' ";
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
                    <li><a class="dropdown-item" href="#" opcao_busca="PAC">PAC</a></li>
                  </ul>
                  <input type="text" texto_busca style="display:<?=(($_SESSION['usuarioBuscaCampo'] == 'perfil' or $_SESSION['usuarioBuscaCampo'] == 'pac')?'none':'block')?>" class="form-control" value="<?=$_SESSION['usuarioBusca']?>" aria-label="Digite a informação para a busca">
                  <select busca_perfil class="form-control" style="display:<?=(($_SESSION['usuarioBuscaCampo'] != 'perfil')?'none':'block')?>">
                    <option value="adm" <?=(($_SESSION['usuarioBusca'] == 'adm')?'selected':false)?>>Administrador</option>
                    <option value="sup" <?=(($_SESSION['usuarioBusca'] == 'sup')?'selected':false)?>>Supervisor</option>
                    <option value="crd" <?=(($_SESSION['usuarioBusca'] == 'crd')?'selected':false)?>>Coordenador</option>
                    <option value="usr" <?=(($_SESSION['usuarioBusca'] == 'usr')?'selected':false)?>>Agente</option>
                  </select>

                  <select busca_pac class="form-control" style="display:<?=(($_SESSION['usuarioBuscaCampo'] != 'pac')?'none':'block')?>">
                    <?php
                        $queryp = "select * from pacs where situacao = '1' and deletado != '1'";
                        $resultp = mysqli_query($con, $queryp);
                        while($p = mysqli_fetch_object($resultp)){
                    ?>
                    <option value="<?=$p->codigo?>" <?=(($_SESSION['usuarioBusca'] == $p->codigo)?'selected':false)?>><?=$p->nome?></option>
                    <?php
                        }
                    ?>
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
                  <th scope="col">Telefone</th>
                  <th scope="col">E-mail</th>
                  <th scope="col">Perfil</th>
                  <th scope="col">PAC</th>
                  <th scope="col">Situação</th>
                  <th scope="col">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $query = "select 
                                  a.*,
                                  b.nome as coordenador_nome,
                                  c.cor,
                                  c.nome as pac_nome,
                                  (select count(*) from metas where usuario = a.codigo and deletado != '1') as metas 
                                  
                            from usuarios a 
                                  left join usuarios b on a.coordenador = b.codigo 
                                  left join pacs c on (IF(a.perfil = 'crd', a.pac = c.codigo, b.pac = c.codigo)) 
                                  
                            where 
                                  (a.deletado != '1' {$where} )".
                                  ($_SESSION['ProjectSeLogin']->codigo != 1?(($_SESSION['ProjectSeLogin']->perfil == 'crd')?" and a.coordenador = '{$_SESSION['ProjectSeLogin']->codigo}' ":false).(($_SESSION['ProjectSeLogin']->perfil == 'adm')?" and (a.perfil != 'adm' or a.codigo = '{$_SESSION['ProjectSeLogin']->codigo}') ":false):false)." order by a.nome asc";
                  $result = mysqli_query($con, $query);
                  while($d = mysqli_fetch_object($result)){
                ?>
                <tr>
                  <td><?=$d->nome?><br><span><?=(($d->coordenador_nome)?:'Não Informado')?></span></td>
                  <td><?=$d->cpf?></td>
                  <td><?=$d->telefone?></td>
                  <td><?=$d->email?></td>
                  <td>
                    <?php
                    if($d->perfil == 'crd'){
                    ?>
                    <span class="btn-perfil" usu="<?=$d->codigo?>" style="background-color:<?=$d->cor?>">
                      <?=Pefil($d->perfil)?>
                    </span>
                    <?php
                    }else{
                    ?>
                    <?=Pefil($d->perfil)?>
                    <?php
                    }
                    ?>
                  </td>
                  <td><span style="color:<?=$d->cor?>"><?=$d->pac_nome?></span></td>
                  <td>

                  <div class="form-check form-switch">
                    <input class="form-check-input situacao" type="checkbox" <?=(($d->codigo == 1)?'disabled':false)?> <?=(($d->situacao)?'checked':false)?> usuario="<?=$d->codigo?>">
                  </div>

                  </td>
                  <td>
                    <?php
                    if(in_array($_SESSION['ProjectSeLogin']->perfil, ['adm', 'sup']) and $d->perfil == 'usr'){
                    ?>
                    <button
                      class="btn btn-primary"
                      metas="<?=$d->codigo?>"
                    >
                      <?=$d->metas?> Meta(s)
                    </button>
                    <?php
                    }
                    ?>
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
                    <?php
                    if($d->codigo != 1 and $_SESSION['ProjectSeLogin']->codigo != $d->codigo){
                    ?>
                    <button class="btn btn-danger" delete="<?=$d->codigo?>">
                      Excluir
                    </button>
                    <?php
                    }
                    ?>
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


        $(".btn-perfil").click(function(){
            usu = $(this).attr("usu")
            $.ajax({
                url:"src/usuarios/pacs.php",
                type:"POST",
                data:{
                  usu
                },
                success:function(dados){
                    JanelaPACs = $.alert({
                      content:dados,
                      title:"Lista de PACs",
                      buttons:{
                        'Cancelar':function(){

                        }
                      }
                    })
                }
            })
        })



        // opcao, rotulo, texto
        $("a[opcao_busca]").click(function(){
          opc = $(this).attr("opcao_busca");
          $("button[rotulo_busca]").text(opc);
          $("input[texto_busca]").val('')
          $("select[busca_perfil]").val('')
          $("select[busca_pac]").val('')
          if(opc == 'Nome'){
            $("input[texto_busca]").unmask();
            $("input[texto_busca]").css('display','block')
            $("select[busca_perfil]").css('display','none')
            $("select[busca_pac]").css('display','none')

          }else if(opc == 'CPF'){
            $("input[texto_busca]").mask("999.999.999-99");
            $("input[texto_busca]").css('display','block')
            $("select[busca_perfil]").css('display','none')
            $("select[busca_pac]").css('display','none')

          }else if(opc == 'Perfil'){
            $("input[texto_busca]").css('display','none')
            $("select[busca_perfil]").css('display','block')
            $("select[busca_pac]").css('display','none')

          }else if(opc == 'PAC'){
            $("input[texto_busca]").css('display','none')
            $("select[busca_perfil]").css('display','none')
            $("select[busca_pac]").css('display','block')
          }
        });

        $("button[limpar]").click(function(){
          Carregando()
          $.ajax({
              url:"src/usuarios/index.php",
              type:"POST",
              data:{
                acao:"limpar"
              },
              success:function(dados){
                $("#paginaHome").html(dados);
              }
            });
        })

        $("button[filtrar]").click(function(){
          opc = $("button[rotulo_busca]").text();
          busca = $("input[texto_busca]").val();
          campo = false;
          if(opc == 'Nome'){
            campo = 'nome';
          }else if(opc == 'CPF'){
            campo = 'cpf';
          }else if(opc == 'Perfil'){
            campo = 'perfil';
            busca = $("select[busca_perfil]").val();
          }else if(opc == 'PAC'){
            campo = 'pac';
            busca = $("select[busca_pac]").val();
          }
          if(campo && busca){
            // console.log(`campo:${campo} && Busca: ${busca}`);
            Carregando()
            $.ajax({
              url:"src/usuarios/index.php",
              type:"POST",
              data:{
                campo,
                busca,
                acao:"filtro"
              },
              success:function(dados){
                $("#paginaHome").html(dados);
              }
            });
          }else{
            $.alert('Favor preencher o campo da busca!')
          }

        });

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

        $("button[metas]").click(function(){
            usuario = $(this).attr("metas");
            Carregando()
            $.ajax({
                url:"src/metas/index.php",
                type:"POST",
                data:{
                  usuario
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
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