<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['catgegoria']}'"));


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        
        if ($data['file-base']) {

            if(!is_dir("icon")) mkdir("icon");

            list($x, $icon) = explode(';base64,', $data['file-base']);
            $icon = base64_decode($icon);
            $pos = strripos($data['file-name'], '.');
            $ext = substr($data['file-name'], $pos, strlen($data['file-name']));
    
            $atual = $data['file-atual'];
    
            unset($data['file-base']);
            unset($data['file-type']);
            unset($data['file-name']);
            unset($data['file-atual']);
    
            if (file_put_contents("icon/{$md5}{$ext}", $icon)) {
                $attr[] = "icon = '{$md5}{$ext}'";
                if ($atual) {
                    unlink("icon/{$atual}");
                }
            }
    
        }


        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update produtos set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into produtos set {$attr}";
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


    $query = "select * from produtos where codigo = '{$_POST['cod']}'";
    $result = sisLog($query);
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
<h4 class="Titulo<?=$md5?>">Cadastro do Produtos - <?=$c->categoria?></h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="produto" name="produto" placeholder="Nome do produto" value="<?=$d->produto?>">
                    <label for="produto">Produto*</label>
                </div>


                <label for="file_<?= $md5 ?>">Imagem da categoria deve ser nas dimensões (270px Largura X 240px Altura) *</label>
                <?php
                if(is_file("icon/{$d->icon}")){
                ?>
                <center><img src="src/produtos/icon/<?=$d->icon?>" style="margin: 20px;" /></center>
                <?php
                }
                ?>
                <div class="input-group mb-3">
                    <input 
                        type="file" 
                        class="form-control" 
                        id="file_<?= $md5 ?>" 
                        accept="image/*"
                        w="270"
                        h="240"
                    >
                    <label class="input-group-text" for="file_<?= $md5 ?>">Selecionar</label>
                    <input
                            type="hidden"
                            id="encode_file"
                            nome=""
                            tipo=""
                            value=""
                            atual="<?= $d->icon; ?>"
                    />
                </div>



                <div class="form-floating mb-3">
                    <textarea type="text" name="descricao" id="descricao" class="form-control" placeholder="Descrição"
                     style="height:150px;"><?=$d->descricao?></textarea>
                    <label for="descricao">Descrição*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="valor" id="valor" class="form-control" placeholder="Valor Individual" value="<?=$d->valor?>">
                    <label for="valor">Valor Individual</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="valor_combo" id="valor_combo" class="form-control" placeholder="Valor no combo" value="<?=$d->valor_combo?>">
                    <label for="valor_combo">Valor no combo</label>
                </div>



                <div class="accordion mb-3" id="accordionExample">
                    <?php
                    $q = "select * from categorias_itens where deletado != '1'";
                    $r = mysqli_query($con, $q);
                    while($d1 = mysqli_fetch_object($r)){
                    ?>
            
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#itens<?=$d1->codigo?>" aria-expanded="false" aria-controls="itens<?=$d1->codigo?>">
                            <?=$d1->categoria?>
                        </button>
                        </h2>
                        <div id="itens<?=$d1->codigo?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                <?php
                                    
                                    $q2 = "select * from itens where categoria = '{$d1->codigo}' and deletado != '1'";
                                    $r2 = mysqli_query($con, $q2);
                                    while($d2 = mysqli_fetch_object($r2)){
                                ?>
                                    <li class="d-flex justify-content-start list-group-item list-group-item-action" >
                                        <input class="form-check-input me-1 opcao" type="checkbox" <?=(($d2->situacao == '0')?'checked':false)?> value="<?=$d2->codigo?>"  id="opcao<?=$d2->codigo?>">
                                            <label class="form-check-label w-100" for="opcao<?=$d2->codigo?>">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-break"><?=$d2->item?></span>
                                                    <input type="text" inputmode="numeric" id="quantidade<?=$d2->codigo?>" class="form-control quantidade" style="width:50px;" maxlength="1" />
                                                </div>
                                            </label> 
                                    </li>
                                <?php

                                    }

                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>    
                    <?php
                    }
                    ?>
                </div>



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
                    <button type="submit" class="btn btn-success btn-ms">Salvar</button>
                    <input type="hidden" id="codigo" value="<?=$_POST['cod']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            $(".quantidade").mask("9");

            $(".opcao").change(function(){
                opc = $(this).val();
                acao = $(this).prop("checked");
                quantidade = (($(`#quantidade${opc}`).val()>0)?$(`#quantidade${opc}`).val():1);
                console.log(opc + " : " + acao + " : " + quantidade)
            })


            if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {
                var mW = $(this).attr("w")
                var mH = $(this).attr("h")
                console.log(`W: ${mW} & H: ${mH}`)
                if ($(this).val()) {
                    var files = $(this).prop("files");
                    for (var i = 0; i < files.length; i++) {
                        (function (file) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {


                                var image = new Image();
                                image.src = fileReader.result;
                                image.onload = function() {

                                    var Base64 = f.target.result;
                                    var type = file.type;
                                    var name = file.name;
                                    var w = image.width;
                                    var h = image.height;

                                    if(mW != w || mH != h){
                                        $.alert('Erro de compatibilidade da dimensão da imagem.<br>Favor seguir o padrão de medidas:<br><b>270px Largura X 240px Altura</b>')
                                        $("#encode_file").val('');
                                        $("#encode_file").attr("nome", '');
                                        $("#encode_file").attr("tipo", '');
                                        $("#encode_file").attr("w", '');
                                        $("#encode_file").attr("h", '');                                        
                                        return false;
                                    }else{
                                        $("#encode_file").val(Base64);
                                        $("#encode_file").attr("nome", name);
                                        $("#encode_file").attr("tipo", type);
                                        $("#encode_file").attr("w", w);
                                        $("#encode_file").attr("h", h);
                                    }

                                };

                            };
                            fileReader.readAsDataURL(file);
                        })(files[i]);
                    }
                }
            });
            } else {
                alert('Nao suporta HTML5');
            }



            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})


                file_name = $("#encode_file").attr("nome");
                file_type = $("#encode_file").attr("tipo");
                file_base = $("#encode_file").val();
                file_atual = $("#encode_file").attr("atual");

                if(file_name && file_type && file_base){

                    campos.push({name: 'file-name', value: file_name})
                    campos.push({name: 'file-type', value: file_type})
                    campos.push({name: 'file-base', value: file_base})
                    campos.push({name: 'file-atual', value: file_atual})

                }


                Carregando();

                $.ajax({
                    url:"src/produtos/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        // if(dados.status){
                            $.ajax({
                                url:"src/produtos/index.php",
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