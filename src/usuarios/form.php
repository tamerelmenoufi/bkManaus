<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);
        unset($data['senha']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        if($_POST['senha']){
            $attr[] = "senha = '" . md5($_POST['senha']) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update usuarios set {$attr} where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
            $cod = $_POST['codigo'];
            sisLog(
                [
                    'query' => $query,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => $cod
                ]
            );
        }else{
            $query = "insert into usuarios set data_cadastro = NOW(), {$attr}";
            mysqli_query($con, $query);
            $cod = mysqli_insert_id($con);
            sisLog(
                [
                    'query' => $query,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => $cod
                ]
            );
        }

        $retorno = [
            'status' => true,
            'codigo' => $cod
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from usuarios where codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Cadastro do Usuário</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$d->nome?>">
                    <label for="nome">Nome*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" value="<?=$d->cpf?>">
                    <label for="cpf">CPF*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="telefone" value="<?=$d->telefone?>">
                    <label for="telefone">Telefone*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$d->email?>">
                    <label for="email">E-mail</label>
                </div>
                <?php
                if($d->codigo != 1 and $_SESSION['ProjectSeLogin']->perfil == 'adm' and $_SESSION['ProjectSeLogin']->codigo != $d->codigo){
                ?>
                <div class="form-floating mb-3">
                    <select name="perfil" class="form-control" id="perfil">
                        <option value="adm" <?=(($d->perfil == 'adm')?'selected':false)?>>Administrador</option>
                        <option value="sup" <?=(($d->perfil == 'sup')?'selected':false)?>>Supervisor</option>
                        <option value="crd" <?=(($d->perfil == 'crd')?'selected':false)?>>Coordenador</option>
                        <option value="usr" <?=(($d->perfil == 'usr')?'selected':false)?>>Agente</option>

                    </select>
                    <label for="email">Perfil</label>
                </div>
                <?php
                }
                if($d->codigo != 1 and ($_SESSION['ProjectSeLogin']->perfil != 'usr')){
                ?>
                <div class="form-floating mb-3">
                    <input type="text" name="login" id="login" class="form-control" placeholder="Login" value="<?=$d->login?>">
                    <label for="login">Login</label>
                </div>
                <?php
                }
                ?>
                <div class="form-floating mb-3">
                    <input type="text" name="senha" id="senha" class="form-control" placeholder="E-mail" value="">
                    <label for="senha">Senha</label>
                </div>
                <?php
                if( (($d->codigo != 1 and $_SESSION['ProjectSeLogin']->codigo != $d->codigo) or !$_POST['cod']) and $_SESSION['ProjectSeLogin']->perfil != 'crd' and $_SESSION['ProjectSeLogin']->perfil != 'sup'){
                ?>

                <div class="form-floating mb-3">
                    <select name="coordenador" id="coordenador" class="form-control" placeholder="Coordenador">
                        <option value="">::Selecione o Coordenador::</option>
                        <?php
                            $q = "select * from usuarios where perfil in ('crd') and situacao = '1' and deletado != '1' order by nome";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option <?=(($d->perfil != 'usr' and $_SESSION['ProjectSeLogin']->perfil != 'sup')?'disabled':false)?> value="<?=$s->codigo?>" <?=(($d->coordenador == $s->codigo)?'selected':false)?>>
                            <?=$s->nome?>
                        </option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="coordenador">Coordenador</label>
                </div>

                <div class="form-floating mb-3">
                    <select name="situacao" class="form-control" id="situacao">
                        <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
                        <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                    </select>
                    <label for="email">Situação</label>
                </div>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div style="display:flex; justify-content:end">
                    <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                    <input type="hidden" id="codigo" value="<?=$_POST['cod']?>" />
                    <?php
                    if($_SESSION['ProjectSeLogin']->perfil == 'crd'){
                    ?>
                    <input type="hidden" id="coordenador" name="coordenador" value="<?=$_SESSION['ProjectSeLogin']->codigo?>" />
                    <input type="hidden" id="perfil" name="perfil" value="usr" />
                    <?php
                    }
                    if($_SESSION['ProjectSeLogin']->perfil == 'sup'){
                    ?>
                    <input type="hidden" id="coordenador" name="coordenador" value="" />
                    <input type="hidden" id="perfil" name="perfil" value="crd" />
                    <?php
                    }

                    ?>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("#cpf").mask("999.999.999-99");
            $("#telefone").mask("(99) 99999-9999");

            $("#perfil").change(function(){
                opc = $(this).val();
                $("#coordenador").val('')
                if(opc == 'usr'){
                    $("#coordenador option").removeAttr("disabled");
                }else{
                    $("#coordenador option").attr("disabled", "disabled");
                }
            })

            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                cpf = $("#cpf").val();
                if(cpf){
                    if(!validarCPF(cpf)){
                        $.alert('Confira o CPF, o informado é inválido!');
                        return;
                    }
                }

                Carregando();

                $.ajax({
                    url:"src/usuarios/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        // if(dados.status){
                            $.ajax({
                                url:"src/usuarios/index.php",
                                type:"POST",
                                success:function(dados){
                                    $("#paginaHome").html(dados);
                                    let myOffCanvas = document.getElementById('offcanvasDireita');
                                    let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                                    openedCanvas.hide();
                                }
                            });
                        // }
                    },
                    error:function(erro){

                        // $.alert('Ocorreu um erro!' + erro.toString());
                        //dados de teste
                    }
                });

            });

        })
    </script>