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
            'codigo' => $cod
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from categorias where codigo = '{$_POST['cod']}'";
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
<h4 class="Titulo<?=$md5?>">Cadastro de Categoria</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="categoria" name="categoria" placeholder="Nome da Categoria" value="<?=$d->categoria?>">
                    <label for="categoria">Categoria*</label>
                </div>

                <div class="input-group mb-3">
                    <input type="file" class="form-control" id="file_<?= $md5 ?>" accept="image/*">
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


            if (window.File && window.FileList && window.FileReader) {

            $('input[type="file"]').change(function () {

                if ($(this).val()) {
                    var files = $(this).prop("files");
                    for (var i = 0; i < files.length; i++) {
                        (function (file) {
                            var fileReader = new FileReader();
                            fileReader.onload = function (f) {
                                var Base64 = f.target.result;
                                var type = file.type;
                                var name = file.name;
                                var w = file.width;
                                var h = file.height;

                                $("#encode_file").val(Base64);
                                $("#encode_file").attr("nome", name);
                                $("#encode_file").attr("tipo", type);
                                $("#encode_file").attr("w", w);
                                $("#encode_file").attr("h", h);


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


                Carregando();

                $.ajax({
                    url:"src/categorias/form.php",
                    type:"POST",
                    typeData:"JSON",
                    mimeType: 'multipart/form-data',
                    data: campos,
                    success:function(dados){
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