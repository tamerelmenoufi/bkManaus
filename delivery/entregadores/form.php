<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        $attr[] = "deletado = '0'";

        $attr = implode(', ', $attr);

        $existe = mysqli_fetch_object(mysqli_query($con, "select * from entregadores where cpf = '{$_POST['cpf']}'"));

        if($_POST['codigo']){
            $query = "update entregadores set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else if($existe->codigo){
            $query = "update entregadores set {$attr} where codigo = '{$existe->codigo}'";
            sisLog($query);
            $cod = $_POST['codigo'];            
        }else{
            $query = "insert into entregadores set data_cadastro = NOW(), {$attr}";
            sisLog($query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'status' => true,
            'codigo' => $cod
        ];

        echo json_encode($retorno);

        exit();
    }

    $query = "select * from entregadores where codigo = '{$_POST['cod']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);
?>
<div class="col">
    <div class="m-3">

        <div class="row">
            <div class="col">
                <div class="card">
                    <h5 class="card-header">Cadastro do Entregador</h5>
                    <div class="card-body">
            
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
                                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" value="<?=$d->telefone?>">
                                        <label for="telefone">Telefone</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$d->email?>">
                                        <label for="email">E-mail</label>
                                    </div>

                                    <!-- <div class="form-floating mb-3">
                                        <select name="loja" class="form-control" id="loja">
                                            <option value="">:: selecione ::</option>
                                            <?php
                                            $q = "select * from lojas where situacao = '1' and deletado != '1' order by nome";
                                            $r = mysqli_query($con, $q);
                                            while($s = mysqli_fetch_object($r)){
                                            ?>
                                            <option value="<?=$s->codigo?>" <?=(($d->loja == $s->codigo)?'selected':false)?>><?=$s->nome?></option>
                                            <?php
                                            }
                                            ?>                        
                                        </select>
                                        <label for="loja">Loja</label>
                                    </div> -->

                                    <div class="form-floating mb-3">
                                        <select name="situacao" class="form-control" id="situacao">
                                            <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
                                            <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
                                        </select>
                                        <label for="email">Situação</label>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div style="display:flex; justify-content:end">
                                        <button type="button" class="btn btn-warning btn-ms me-3 voltar">Voltar</button>
                                        <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                                        <input type="hidden" id="codigo" value="<?=$_POST['cod']?>" />
                                        <input type="hidden" name="loja" value="<?=$_POST['loja']?>" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        $(function(){
            Carregando('none');

            $("#cpf").mask("999.999.999-99");
            $("#telefone").mask("(99) 99999-9999");


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
                    url:"entregadores/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        // if(dados.status){
                            loja = localStorage.getItem("loja");
                            $.ajax({
                                url:"entregadores/index.php",
                                type:"POST",
                                data:{
                                    loja
                                },
                                success:function(dados){
                                    $(".popupPalco").html(dados);
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

            $(".voltar").click(function(){
                loja = localStorage.getItem("loja");
                $.ajax({
                    url:"entregadores/index.php",
                    type:"POST",
                    data:{
                        loja
                    },
                    success:function(dados){
                        $(".popupPalco").html(dados);
                    }
                });                
            })

        })
    </script>