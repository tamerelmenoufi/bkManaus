<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['categoria']) $_SESSION['categoria'] = $_POST['categoria'];

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);

        
        if ($data['file-base']) {

            $md52 = md5($md5.$data['file-name']);

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
    
            if (file_put_contents("icon/{$md52}{$ext}", $icon)) {
                $attr[] = "icon = '{$md52}{$ext}'";
                if ($atual) {
                    unlink("icon/{$atual}");
                }
            }
    
        }


        if ($data['capa-base']) {

            $md52 = md5($md5.$data['capa-name']);

            if(!is_dir("icon")) mkdir("icon");

            list($x, $icon) = explode(';base64,', $data['capa-base']);
            $icon = base64_decode($icon);
            $pos = strripos($data['capa-name'], '.');
            $ext = substr($data['capa-name'], $pos, strlen($data['capa-name']));
    
            $atual = $data['capa-atual'];
    
            unset($data['capa-base']);
            unset($data['capa-type']);
            unset($data['capa-name']);
            unset($data['capa-atual']);
    
            if (file_put_contents("icon/{$md52}{$ext}", $icon)) {
                $attr[] = "capa = '{$md52}{$ext}'";
                if ($atual) {
                    unlink("icon/{$atual}");
                }
            }
    
        }


        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        if($_POST['codigo']){
            $attr = implode(', ', $attr);
            $query = "update produtos set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $j = [
                'categorias_itens', 'categorias_troca', 'itens_add', 'itens_troca', 'itens'
            ];
            foreach($j as $ji => $jv){
                $attr[] = "{$jv} = '[]'";
            }
            $attr = implode(', ', $attr);
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

    $dados = json_decode($d->produtos);

    $produtos = [];

    if($dados){
        foreach($dados as $p => $q){
            $produtos[$q->produto] = $q->quantidade;
        }        
    }

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
                <center><img src="src/combos/icon/<?=$d->icon?>" style="margin: 20px;" /></center>
                <?php
                }
                ?>
                <div class="input-group mb-3">
                    <input 
                        type="file" 
                        class="form-control" 
                        id="file_<?= $md5 ?>" 
                        target="encode_file"
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
                    <textarea type="text" name="descricao" id="descricao" class="form-control" style="height:120px;" placeholder="Descrição"><?=$d->descricao?></textarea>
                    <label for="descricao">Descrição*</label>
                </div>


                <div class="accordion mb-3" id="accordionExample">
                    <?php
                    $q = "select * from categorias where tipo = 'prd' and situacao = '1' and deletado != '1'";
                    $r = mysqli_query($con, $q);
                    while($d1 = mysqli_fetch_object($r)){
                    ?>
            
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#produtos<?=$d1->codigo?>" aria-expanded="false" aria-controls="produtos<?=$d1->codigo?>">
                            <?=$d1->categoria?>
                        </button>
                        </h2>
                        <div id="produtos<?=$d1->codigo?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="list-group">
                                <?php
                                    
                                    $q2 = "select * from produtos where categoria = '{$d1->codigo}' and situacao = '1' and deletado != '1'";
                                    $r2 = mysqli_query($con, $q2);
                                    while($d2 = mysqli_fetch_object($r2)){
                                ?>
                                    <li class="d-flex justify-content-start list-group-item list-group-item-action" >
                                        <input class="form-check-input me-1 opcao" codigo="<?=$d2->codigo?>" type="checkbox" valor="<?=$d2->valor_combo?>" <?=(($produtos[$d2->codigo])?'checked':false)?> value="<?=$d2->codigo?>"  id="acao<?=$d2->codigo?>">
                                            <label class="form-check-label w-100" for="acao<?=$d2->codigo?>">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-break"><?=$d2->produto?> - <?=$d2->valor_combo?></span>
                                                    <select class="form-select opcao" codigo="<?=$d2->codigo?>" style="width:60px" id="quantidade<?=$d2->codigo?>">
                                                    <?php
                                                    for($i = 1; $i <= 9; $i++){
                                                    ?>
                                                    <option value="<?=$i?>" <?=(($produtos[$d2->codigo] == $i)?'selected':false)?>><?=$i?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                    </select>
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
                    <div id="valor" class="form-control" ><?=$d->valor?></div>
                    <label for="valor">Valor</label>
                </div>


                <div class="card mb-3" style="background-color:#eee">
                    <div class="card-header">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="promocao" <?=(($d->promocao)?'checked':false)?>>
                            <label class="form-check-label" for="promocao">Ativar Combo em Promoção</label>
                        </div>
                    </div>
                    
                    <div class="p-2">
                    
                        <label for="capa_<?= $md5 ?>">Imagem da capa promocional deve ser nas dimensões (600px Largura X 638px Altura) *</label>
                        <?php
                        if(is_file("icon/{$d->capa}")){
                        ?>
                        <center><img src="src/combos/icon/<?=$d->capa?>" class="img-fluid" /></center>
                        <?php
                        }
                        ?>
                        <div class="input-group mb-3">
                            <input 
                                type="file" 
                                class="form-control" 
                                id="capa_<?= $md5 ?>" 
                                target="encode_capa"
                                accept="image/*"
                                w="600"
                                h="638"
                            >
                            <label class="input-group-text" for="capa_<?= $md5 ?>">Selecionar</label>
                            <input
                                    type="hidden"
                                    id="encode_capa"
                                    nome=""
                                    tipo=""
                                    value=""
                                    atual="<?= $d->capa; ?>"
                            />
                        </div>

                        <div class="form-floating">
                            <input type="text" name="valor_promocao" id="valor_promocao" class="form-control" placeholder="Valor Promocional" value="<?=$d->valor_promocao?>">
                            <label for="valor_promocao">Valor Promocial</label>
                        </div>
                    </div>
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
                    <input type="hidden" name="categoria" id="categoria" value="<?=$_SESSION['categoria']?>" />
                </div>
            </div>
        </div>
    </form>

    <script>
        $(function(){
            Carregando('none');

            function CalcularValorCombo(){
                total = 0;
                $("input.opcao").each(function(){
                    if($(this).prop("checked") == true){
                        item = $(this).attr("codigo");
                        quantidade = $(`#quantidade${item}`).val();
                        total = (total*1) + (($(this).attr("valor"))*quantidade*1);
                    }
                })
                $("#valor").html(total.toFixed(2));                
            }

            CalcularValorCombo();


            $(".opcao").change(function(){
                CalcularValorCombo();
            })


            if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {
                var mW = $(this).attr("w")
                var mH = $(this).attr("h")
                var tgt = $(this).attr("target")
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
                                        $.alert(`Erro de compatibilidade da dimensão da imagem.<br>Favor seguir o padrão de medidas:<br><b>${mW}px Largura X ${mH}px Altura</b>`)
                                        $(`#${tgt}`).val('');
                                        $(`#${tgt}`).attr("nome", '');
                                        $(`#${tgt}`).attr("tipo", '');
                                        $(`#${tgt}`).attr("w", '');
                                        $(`#${tgt}`).attr("h", '');                                        
                                        return false;
                                    }else{
                                        $(`#${tgt}`).val(Base64);
                                        $(`#${tgt}`).attr("nome", name);
                                        $(`#${tgt}`).attr("tipo", type);
                                        $(`#${tgt}`).attr("w", w);
                                        $(`#${tgt}`).attr("h", h);
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


                capa_name = $("#encode_capa").attr("nome");
                capa_type = $("#encode_capa").attr("tipo");
                capa_base = $("#encode_capa").val();
                capa_atual = $("#encode_capa").attr("atual");

                if(capa_name && capa_type && capa_base){

                    campos.push({name: 'capa-name', value: capa_name})
                    campos.push({name: 'capa-type', value: capa_type})
                    campos.push({name: 'capa-base', value: capa_base})
                    campos.push({name: 'capa-atual', value: capa_atual})

                }


                produtos = [];
                $("input.opcao").each(function(){
                    if($(this).prop("checked") == true){
                        produto = $(this).attr("codigo");
                        quantidade = $(`#quantidade${produto}`).val();
                        produtos.push({'produto':produto, 'quantidade':quantidade});                            
                    }
                })

                produtos = JSON.stringify(produtos)

                campos.push({name:'produtos', value:produtos})

                if($("#promocao").prop("checked") == true){
                    campos.push({name:'promocao', value:'1'})                           
                }else{
                    campos.push({name:'promocao', value:'0'})                           
                }

                Carregando();

                $.ajax({
                    url:"src/combos/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        // console.log(dados);
                        // if(dados.status){
                            $.ajax({
                                url:"src/combos/index.php",
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