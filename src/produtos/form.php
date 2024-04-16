<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['acao'] == 'ingredientes'){

        $dados = json_encode($_POST['dados']);
        mysqli_query($con, "update produtos set itens = '{$dados}' where codigo = '{$_POST['produto']}'");
        exit();
    }

    $c = mysqli_fetch_object(mysqli_query($con, "select * from categorias where codigo = '{$_SESSION['categoria']}'"));
    $acoes_itens = json_decode($c->acoes_itens);
    $remocao = $acoes_itens->remocao;
    $substituicao = $acoes_itens->substituicao;


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

        // $query = "SELECT codigo, produtos->'$[*].produto' as codigos FROM `produtos` where produtos->'$[*].produto' like '%\"{$cod}\"%' and categoria = '8'";
        // $result = mysqli_query($con, $query);
        // while($d = mysqli_fetch_object($result)){
        //     $cods = json_decode($d->codigos);
        //     $t = mysqli_fetch_object(mysqli_query($con, "select sum(valor_combo) as total from produtos where codigo in (".(implode(", ", $cods)).")"));
        //     mysqli_query($con, "update produtos set valor = '{$t->total}' where codigo = '{$d->codigo}'");
        // }

        $retorno = [
            'status' => true,
            'codigo' => $query
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from produtos where codigo = '{$_POST['cod']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);

    $dados = json_decode($d->itens);
    $dados_add = json_decode($d->itens_add);
    $dados_troca = json_decode($d->itens_troca);

    $itens = [];
    $itens_add = [];
    $itens_troca = [];

    if($dados){
        foreach($dados as $p => $q){
            $itens[$q->item] = $q->quantidade;
        }        
    }
    if($dados_add){
        foreach($dados_add as $p => $q){
            $itens_add[$q->item] = $q->item;
        }        
    }
    if($dados_troca){
        foreach($dados_troca as $p => $q){
            $itens_troca[$q->item] = $q->item;
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
<h4 class="Titulo<?=$md5?>">Cadastro do Produtos</h4>
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
                    $q = "select * from categorias_itens where situacao = '1' and deletado != '1' and codigo in(".(($c->categorias_itens and $remocao == 'true')?$c->categorias_itens:0).") and deletado != '1'";
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
                                    
                                    $q2 = "select * from itens where categoria = '{$d1->codigo}' and situacao = '1' and deletado != '1'";
                                    $r2 = mysqli_query($con, $q2);
                                    while($d2 = mysqli_fetch_object($r2)){
                                ?>
                                    <li class="d-flex justify-content-start list-group-item list-group-item-action" >
                                        <input class="form-check-input me-1 opcao" codigo="<?=$d2->codigo?>" type="checkbox" <?=(($itens[$d2->codigo])?'checked':false)?> value="<?=$d2->codigo?>"  id="acao<?=$d2->codigo?>">
                                            <label class="form-check-label w-100" for="acao<?=$d2->codigo?>">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-break"><?=$d2->item?></span>
                                                    <select class="form-select opcao" codigo="<?=$d2->codigo?>" style="width:60px" id="quantidade<?=$d2->codigo?>">
                                                    <?php
                                                    for($i = 1; $i <= 9; $i++){
                                                    ?>
                                                    <option value="<?=$i?>" <?=(($itens[$d2->codigo] == $i)?'selected':false)?>><?=$i?></option>
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


                <div class="accordion mb-3" id="accordionExample2">
                    <?php
                    $q = "select * from categorias_itens where situacao = '1' and deletado != '1' and codigo in(".(($c->categorias_itens and $remocao == 'true')?$c->categorias_itens:0).")";
                    $r = mysqli_query($con, $q);
                    while($d1 = mysqli_fetch_object($r)){
                    ?>
            
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#itens_add<?=$d1->codigo?>" aria-expanded="false" aria-controls="itens<?=$d1->codigo?>">
                            <?=$d1->categoria?> ADICIONAIS
                        </button>
                        </h2>
                        <div id="itens_add<?=$d1->codigo?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
                            <div class="accordion-body">
                                <ul class="list-group">
                                <?php
                                    
                                    $q2 = "select * from itens where categoria = '{$d1->codigo}' and situacao = '1' and deletado != '1'";
                                    $r2 = mysqli_query($con, $q2);
                                    while($d2 = mysqli_fetch_object($r2)){
                                ?>
                                    <li class="d-flex justify-content-start list-group-item list-group-item-action" >
                                        <input class="form-check-input me-1 opcao_add" codigo="<?=$d2->codigo?>" type="checkbox" <?=(($itens_add[$d2->codigo])?'checked':false)?> value="<?=$d2->codigo?>"  id="acao_add<?=$d2->codigo?>">
                                            <label class="form-check-label w-100" for="acao_add<?=$d2->codigo?>">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-break"><?=$d2->item?></span>
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


                <div class="accordion mb-3" id="accordionExample3">
                    <?php
                    $q = "select * from categorias where situacao = '1' and deletado != '1' and codigo in(".(($substituicao == 'true')?$c->codigo:0).")";
                    $r = mysqli_query($con, $q);
                    while($d1 = mysqli_fetch_object($r)){
                    ?>
            
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#itens_troca<?=$d1->codigo?>" aria-expanded="false" aria-controls="itens_troca<?=$d1->codigo?>">
                            SUBSTITUIÇÃO
                        </button>
                        </h2>
                        <div id="itens_troca<?=$d1->codigo?>" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
                            <div class="accordion-body">
                                <ul class="list-group">
                                <?php
                                    
                                    $q2 = "select * from produtos where categoria = '{$d1->codigo}' and situacao = '1' and deletado != '1' and codigo not in ($d->codigo)";
                                    $r2 = mysqli_query($con, $q2);
                                    while($d2 = mysqli_fetch_object($r2)){
                                ?>
                                    <li class="d-flex justify-content-start list-group-item list-group-item-action" >
                                        <input class="form-check-input me-1 opcao_troca" codigo="<?=$d2->codigo?>" type="checkbox" <?=(($itens_troca[$d2->codigo])?'checked':false)?> value="<?=$d2->codigo?>"  id="acao_troca<?=$d2->codigo?>">
                                            <label class="form-check-label w-100" for="acao_troca<?=$d2->codigo?>">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-break"><?=$d2->produto?></span>
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



                <div class="card mb-3" style="background-color:#eee">
                    <div class="card-header">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="promocao" <?=(($d->promocao)?'checked':false)?>>
                            <label class="form-check-label" for="promocao">Ativar Produto em Promoção</label>
                        </div>
                    </div>
                    
                    <div class="p-2">
                    
                        <label for="capa_<?= $md5 ?>">Imagem da capa promocional deve ser nas dimensões (600px Largura X 638px Altura) *</label>
                        <?php
                        if(is_file("icon/{$d->capa}")){
                        ?>
                        <center><img src="src/produtos/icon/<?=$d->capa?>" class="img-fluid" /></center>
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


            // $(".opcao").change(function(){

            //     dados = [];
            //     $("input.opcao").each(function(){
            //         if($(this).prop("checked") == true){
            //             item = $(this).attr("codigo");
            //             quantidade = $(`#quantidade${item}`).val();
            //             dados.push({'item':item, 'quantidade':quantidade});                            
            //         }
            //     })

            //     $.ajax({
            //         url:"src/produtos/form.php",
            //         type:"POST",
            //         data:{
            //             dados,
            //             produto:'<?=$d->codigo?>',
            //             acao:'ingredientes'
            //         },
            //         success:function(dados){
            //             // console.log(dados)
            //         },
            //         error:function(erro){

            //             // $.alert('Ocorreu um erro!' + erro.toString());
            //             //dados de teste
            //         }
            //     });

            //     // console.log(dados)


            // })


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


                itens = [];
                $("input.opcao").each(function(){
                    if($(this).prop("checked") == true){
                        item = $(this).attr("codigo");
                        quantidade = $(`#quantidade${item}`).val();
                        itens.push({'item':item, 'quantidade':quantidade});                            
                    }
                })
                itens = JSON.stringify(itens)
                campos.push({name:'itens', value:itens})

                itens = [];
                $("input.opcao_add").each(function(){
                    if($(this).prop("checked") == true){
                        item = $(this).attr("codigo");
                        itens.push({'item':item});                            
                    }
                })
                itens = JSON.stringify(itens)
                campos.push({name:'itens_add', value:itens})


                itens = [];
                $("input.opcao_troca").each(function(){
                    if($(this).prop("checked") == true){
                        item = $(this).attr("codigo");
                        itens.push({'item':item});                            
                    }
                })
                itens = JSON.stringify(itens)
                campos.push({name:'itens_troca', value:itens})





                if($("#promocao").prop("checked") == true){
                    campos.push({name:'promocao', value:'1'})                           
                }else{
                    campos.push({name:'promocao', value:'0'})                           
                }
                
                // console.log(campos);
                // // return false;

                Carregando();

                $.ajax({
                    url:"src/produtos/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){

                        // console.log(dados)
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