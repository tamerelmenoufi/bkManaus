<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);
        unset($data['senha']);
        unset($data['data']);

        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        $attr[] = "usuario = '" . $_SESSION['usuario'] . "'";
        $attr[] = "data = '" . dataMysql($_POST['data']) . "'";

        // if(!$_POST['codigo']){
        //     $attr[] = "data = NOW()";
        // }


        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update metas set {$attr} where codigo = '{$_POST['codigo']}'";
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
            $query = "insert into metas set {$attr}";
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
            'codigo' => $query
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from metas where codigo = '{$_POST['meta']}'";
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
<h4 class="Titulo<?=$md5?>">Cadastro de Metas</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <select required name="municipio" id="municipio" class="form-control" placeholder="Município">
                        <option value="">::Selecione o Município::</option>
                        <?php
                            $q = "select * from municipios order by municipio";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->codigo?>" <?=(($d->municipio == $s->codigo)?'selected':false)?>><?=$s->municipio?></option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="email">Município</label>
                </div>

                <div class="form-floating mb-3">
                    <select required name="zona" id="zona" class="form-control" placeholder="Zona">
                        <option value="">::Selecione a Zona::</option>
                        <option value="Urbano" <?=(($d->zona == 'Urbano')?'selected':false)?>>Urbano</option>
                        <option value="Rural" <?=(($d->zona == 'Rural')?'selected':false)?>>Rural</option>
                    </select>
                    <label for="zona">Zona</label>
                </div>

                <div class="form-floating mb-3">
                    <select required name="bairro_comunidade" id="bairro_comunidade" class="form-control" placeholder="Bairro">
                        <option value="">::Selecione a Localização::</option>
                        <?php
                            $q = "select * from bairros_comunidades where municipio = '{$d->municipio}' ".(($d->zona)?" and tipo = '{$d->zona}'":false)." order by descricao";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->codigo?>" <?=(($d->bairro_comunidade == $s->codigo)?'selected':false)?>><?=$s->descricao?> (<?=$s->tipo?>)</option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="bairro_comunidade">Bairro/Comunidade</label>
                </div>

                <div class="form-floating mb-3">
                    <input required type="text" name="data" id="data" class="form-control" placeholder="Data Inicial da Meta" value="<?=dataBr($d->data)?>">
                    <label for="data">Data Inicial da Meta</label>
                </div>

                <div class="form-floating mb-3">
                    <select required name="situacao" class="form-control" id="situacao">
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
                    <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                    <input type="hidden" id="codigo" value="<?=$_POST['meta']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $("#cpf").mask("999.999.999-99");
            $("#telefone").mask("(99) 99999-9999");
            $("#data").mask("99/99/9999");


            var filtro = (bairro_comunidade, zona) => {
                if(!municipio){
                    $("#bairro_comunidade").html('<option value="">::Selecione a Localização::</option>');
                    return false;
                }
                if(!zona){
                    $("#bairro_comunidade").html('<option value="">::Selecione a Localização::</option>');
                    return false;
                }
                $.ajax({
                    url:"src/metas/filtro.php",
                    type:"POST",
                    data:{
                        municipio,
                        zona,
                        acao:'bairro_comunidade'
                    },
                    success:function(dados){
                        $("#bairro_comunidade").html(dados);
                    }
                });
            }

            $("#zona").change(function(){
                municipio = $("#municipio").val();
                zona = $(this).val();
                filtro(municipio, zona);
            });

            $("#municipio").change(function(){
                zona = $("#zona").val();
                municipio = $(this).val();
                filtro(municipio, zona);
            });

            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                Carregando();

                $.ajax({
                    url:"src/metas/form.php",
                    type:"POST",
                    dataType:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        // console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/metas/index.php",
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