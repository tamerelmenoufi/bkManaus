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

        $existe = mysqli_fetch_object(mysqli_query($con, "select * from lojas where cnpj = '{$_POST['cnpj']}'"));

        if($_POST['codigo']){
            $query = "update lojas set {$attr} where codigo = '{$_POST['codigo']}'";
            sisLog($query);
            $cod = $_POST['codigo'];
        }else if($existe->codigo){
            $query = "update lojas set {$attr} where codigo = '{$existe->codigo}'";
            sisLog($query);
            $cod = $_POST['codigo'];            
        }else{
            $query = "insert into lojas set {$attr}";
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

    $query = "select * from lojas where codigo = '{$_POST['cod']}'";
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
<h4 class="Titulo<?=$md5?>">Cadastro da Loja</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row">
            <div class="col">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$d->nome?>">
                    <label for="nome">Nome*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="cnpj" id="cnpj" class="form-control" placeholder="CNPJ" value="<?=$d->cnpj?>">
                    <label for="cnpj">CNPJ*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" value="<?=$d->telefone?>">
                    <label for="telefone">Telefone</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Endereço" value="<?=$d->endereco?>">
                    <label for="endereco">Endereço</label>
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

            $("#cnpj").mask("99.999.999/9999-99");
            $("#telefone").mask("(99) 99999-9999");


            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                var codigo = $('#codigo').val();
                var campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                cnpj = $("#cnpj").val();
                if(cnpj){
                    if(!validarCNPJ(cnpj)){
                        $.alert('Confira o CNPJ, o informado é inválido!');
                        return;
                    }
                }

                endereco = $("#endereco").val();
                if(!endereco){
                    $.alert('Favor informe o endereço completo da loja no campo correspondente!');
                    return;                    
                }


                geocoder<?=$md5?> = new google.maps.Geocoder();
                geocoder<?=$md5?>.geocode({ 'address': `${endereco}, Manaus, Amazonas, Brasil`, 'region': 'BR' }, (results, status) => {

                    if (status == google.maps.GeocoderStatus.OK) {

                        if (results[0]) {

                            var latitude<?=$md5?> = results[0].geometry.location.lat();
                            var longitude<?=$md5?> = results[0].geometry.location.lng();

                            coordenadas = `${latitude<?=$md5?>},${longitude<?=$md5?>}`;
                            campos.push({name: 'coordenadas', value: coordenadas});

                            Carregando();

                            $.ajax({
                                url:"src/lojas/form.php",
                                type:"POST",
                                typeData:"JSON",
                                mimeType: 'multipart/form-data',
                                data: campos,
                                success:function(dados){
                                    // if(dados.status){
                                        $.ajax({
                                            url:"src/lojas/index.php",
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

                            

                        }
                    }
                })



                

            });

        })
    </script>