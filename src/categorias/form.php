<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        $attr = [];

        unset($data['categorias_itens']);
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
    
        if($_POST['categorias_itens']){
            $attr[] = "categorias_itens = '" . ($_POST['categorias_itens']) . "'";
        }




        foreach ($data as $name => $value) {
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }

        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update categorias set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else{
            $query = "insert into categorias set {$attr}";
            sisLog($query);
            $cod = mysqli_insert_id($con);
        }

        $retorno = [
            'status' => true,
            'codigo' => $query
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from categorias where codigo = '{$_POST['cod']}'";
    $result = sisLog($query);
    $d = mysqli_fetch_object($result);

    $acoes_itens = json_decode($d->acoes_itens);
    $categorias_itens = json_decode($d->categorias_itens);

    if($d->codigo == 89999999999999){
        $w = 600;
        $h = 228;
    }else{
        $w = 250;
        $h = 108;
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
<h4 class="Titulo<?=$md5?>">Cadastro de Categoria</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Nome da Categoria" value="<?=$d->categoria?>">
                    <label for="categoria">Categoria*</label>
                </div>

                <label for="file_<?= $md5 ?>">Imagem da categoria deve ser nas dimensões (<?=$w?>px Largura X <?=$h?>px Altura) *</label>
                <?php
                if(is_file("icon/{$d->icon}")){
                ?>
                <center><img src="src/categorias/icon/<?=$d->icon?>" class="img-fluid" /></center>
                <?php
                }
                ?>
                <div class="input-group mb-3">
                    <input 
                        type="file" 
                        class="form-control" 
                        id="file_<?= $md5 ?>" 
                        accept="image/*"
                        w="<?=$w?>"
                        h="<?=$h?>"
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

                <?php
                if($d->tipo == 'prd'){
                ?>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input acoes_itens" <?=(($acoes_itens->inclusao == 'true')?"checked":false)?> id="inclusao">
                    <label class="form-check-label" for="inclusao">Inclusão de Itens</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input acoes_itens" <?=(($acoes_itens->substituicao == 'true')?"checked":false)?> id="substituicao">
                    <label class="form-check-label" for="substituicao">Substituição de Itens</label>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input"  <?=(($acoes_itens->remocao == 'true')?"checked":false)?> id="remocao">
                    <label class="form-check-label" for="remocao">Remover Itens do produto</label>
                </div>

                <?php
                $q = "select * from categorias_itens where deletado != '1'";
                $r = mysqli_query($con, $q);
                while($d1 = mysqli_fetch_object($r)){
                ?>
                <div class="form-check">
                    <input class="form-check-input categorias_itens" value="<?=$d1->codigo?>" type="radio" name="categorias_itens" <?=((in_array('true', (array)$acoes_itens))?false:'disabled')?> <?=(($d1->codigo == $categorias_itens)?"checked":false)?> id="categorias_itens<?=$d1->codigo?>">
                    <label class="form-check-label" for="categorias_itens<?=$d1->codigo?>">
                        <?=$d1->categoria?>
                    </label>
                </div>
                <?php
                }
                }
                ?>
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

            $(".acoes_itens").click(function(){
                obj = $(this);
                acao = obj.prop("checked");
                console.log(acao)
                if(acao == true){
                    $(".acoes_itens").prop("checked", false);
                    obj.prop("checked", true);
                }

                $(".categorias_itens").attr("disabled", "disabled");
                categorias_itens = false;
                $(".acoes_itens").each(function(){
                    if($(this).prop("checked") == true){
                        categorias_itens = true;
                    }
                })

                if(categorias_itens == true){
                    $(".categorias_itens").removeAttr("disabled");
                }else{
                    $(".categorias_itens").prop("checked", false);
                }

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

                inclusao = $("#inclusao").prop("checked");
                substituicao = $("#substituicao").prop("checked");
                remocao = $("#remocao").prop("checked");

                campos.push({name: 'acoes_itens', value: `{"inclusao":"${inclusao}", "substituicao":"${substituicao}", "remocao":"${remocao}"}`});


                Carregando();

                $.ajax({
                    url:"src/categorias/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
                        console.log(dados)
                        // if(dados.status){
                            $.ajax({
                                url:"src/categorias/index.php",
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