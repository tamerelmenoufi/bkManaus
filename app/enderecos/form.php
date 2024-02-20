<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    if($_POST['acao'] == 'salvar'){

        $data = $_POST;
        unset($data['idUnico']);
        unset($data['codUsr']);
        unset($data['acao']);
        unset($data['codigo']);
        $campos = [];
        foreach($data as $i => $v){
            $campos[] = "{$i} = '".addslashes($v)."'";
        }
        $campos[] = "cliente = '{$_POST['codUsr']}'";
        $campos[] = "padrao = '1'";
        // if(!$_POST['codigo']){
        $campos[] = "distancias = '{}'";
        // }
        
        mysqli_query($con, "update enderecos set padrao = '0' where cliente = '{$_POST['codUsr']}'");

        if($_POST['codigo']){
            mysqli_query($con, "update enderecos set ".implode(", ",$campos)." where codigo = '{$_POST['codigo']}'");
        }else{
            mysqli_query($con, "insert into enderecos set ".implode(", ",$campos));
        }
        
        
        echo json_encode([
            "status" => true
        ]);
        exit();
    }

    if($_POST['cep']){
        $cep = str_replace('-',false,$_POST['cep']);
        $d = ConsultaCEP($cep);
        if($_POST['retorno']){
            echo json_encode($d);
            exit();
        }
    }

    if($_POST['codigo']){
        $query = "select * from enderecos where codigo = '{$_POST['codigo']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
    }


?>

<div class="row g-0 mb-3 p-2">
        <h4 class="w-100 text-center">Endereços</h4>
        <div class="mb-1">
            <label for="cep" class="form-label">CEP</label>
            <input type="text" class="form-control <?=(($d->cep)?'is-valid':'is-invalidXX')?>" autocomplete="off" value="<?=$d->cep?>" id="cep">
        </div>
        <div class="mb-1">
            <label for="logradouro" class="form-label">Logradouro*</label>
            <input type="text" class="form-control <?=(($d->logradouro)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->logradouro?>" id="logradouro">
        </div>
        <div class="mb-1">
            <label for="numero" class="form-label">Número*</label>
            <input type="text" class="form-control <?=(($d->numero)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->numero?>" id="numero">
        </div>
        <div class="mb-1">
            <label for="complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control <?=(($d->ponto_referencia)?'is-valid':false)?>" autocomplete="off" value="<?=$d->complemento?>" id="complemento">
        </div>  
        <div class="mb-1">
            <label for="ponto_referencia" class="form-label">Ponto de Referência*</label>
            <input type="text" class="form-control <?=(($d->ponto_referencia)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->ponto_referencia?>" id="ponto_referencia">
        </div>        
        <div class="mb-1">
            <label for="bairro" class="form-label">Bairro*</label>
            <input type="text" class="form-control <?=(($d->bairro)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->bairro?>" id="bairro">
        </div>  
        <!--<div class="mb-2">
            <label for="localidade" class="form-label">Cidade*</label>
            <input type="text" class="form-control <?=(($d->localidade)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->localidade?>" id="localidade">
        </div> -->
        <div class="mb-1">
            <button type="button" class="btn btn-outline-success w-100 salvar_endereco">Salvar Endereço</button>
            <input type="hidden" value="AM" id="uf">
            <input type="hidden" value="Manaus" id="localidade">
            <?php
            if($d->codigo){
            ?>
            <input type="hidden" value="<?=$d->codigo?>" id="codigo">
            <?php
            }
            ?>
        </div>
        <div class="mb-1">
            <button type="button" class="btn btn-outline-danger w-100 cancelar_endereco">Cancelar</button>
        </div>
        <?php
        if($d->codigo){
        ?>
        <div class="mb-1">
            <button type="button" class="btn btn-outline-warning w-100 excluir_endereco">Excluir</button>
        </div>
        <?php
        }
        ?>
</div>

<script>
    $(function(){

        $("#cep").mask("99999-999");
        $("#cep").blur(function(){
            cep = $(this).val();
            // if(!cep || (cep.length == 9 && cep.substring(0,2) == 69)){
            //     idUnico = localStorage.getItem("idUnico");
            //     codUsr = localStorage.getItem("codUsr");
            //     $.ajax({
            //         url:"enderecos/form.php",
            //         type:"POST",
            //         data:{
            //             idUnico,
            //             codUsr,
            //             cep
            //         },
            //         success:function(dados){
            //             $(".dados_enderecos").html(dados);                     
            //         }
            //     });

            // }else 
            
            if( cep.length > 0 && (cep.substring(0,2) != 69 || cep.length != 9)){
                $.alert({
                    title:"Erro",
                    content:"CEP inválido ou fora da área de atendimento",
                    type:"red"
                })
            }else{
                idUnico = localStorage.getItem("idUnico");
                codUsr = localStorage.getItem("codUsr");
                $.ajax({
                    url:"enderecos/form.php",
                    type:"POST",
                    dataType:"JSON",
                    data:{
                        idUnico,
                        codUsr,
                        cep,
                        retorno:1
                    },
                    success:function(dados){
                        // $(".dados_enderecos").html(dados);   
                        
                        //cep = $("#cep").val(dados.cep);
                        logradouro = $("#logradouro").val(dados.logradouro);
                        complemento = $("#complemento").val();
                        bairro = $("#bairro").val(dados.bairro);
                        //localidade = $("#localidade").val(dados.localidade);

                    }
                });
            }
        })

        $(".cancelar_endereco").click(function(){

            Carregando();
            idUnico = localStorage.getItem("idUnico");
            codUsr = localStorage.getItem("codUsr");

            $.ajax({
                url:"enderecos/lista_enderecos.php",
                type:"POST",
                data:{
                    codUsr,
                    idUnico
                },
                success:function(dados){
                    $(".dados_enderecos").html(dados);
                    Carregando('none');
                }
            }) 
        })


        $(".excluir_endereco").click(function(){
            $.confirm({
                title:"Excluir Endereço",
                content:"Deseja realmente excluir o endereço?",
                type:"red",
                buttons:{
                    'Sim':function(){
                        Carregando();
                        idUnico = localStorage.getItem("idUnico");
                        codUsr = localStorage.getItem("codUsr");

                        $.ajax({
                            url:"enderecos/lista_enderecos.php",
                            type:"POST",
                            data:{
                                codUsr,
                                idUnico,
                                excluir:'<?=$d->codigo?>'
                            },
                            success:function(dados){
                                $(".dados_enderecos").html(dados);
                                Carregando('none');
                            }
                        }) 
                    },
                    'Não':function(){

                    }
                }
            })


        })
        

        $(".salvar_endereco").click(function(){

            cep = $("#cep").val();
            logradouro = $("#logradouro").val();
            numero = $("#numero").val();
            complemento = $("#complemento").val();
            ponto_referencia = $("#ponto_referencia").val();
            bairro = $("#bairro").val();
            localidade = $("#localidade").val();
            uf = $("#uf").val();
            <?php
            if($d->codigo){
            ?>
            codigo = $("#codigo").val();
            <?php
            }
            ?>

            if(cep.length > 0 && (cep.length != 9 || cep.substring(0,2) != 69)){
                $.alert({
                    title:"Erro",
                    content:"CEP inválido ou fora da área de atendimento",
                    type:"red"
                })
                return false;
            }

            if(
            //!cep ||
            !logradouro ||
            !numero ||
            !ponto_referencia ||
            !bairro ||
            !localidade ||
            !uf
            ){
                $.alert({
                    content:'Preencha os campos obrigatório (*)!',
                    title:"Erro",
                    type:"red"
                });
                return false;
            }
            geocoder<?=$md5?> = new google.maps.Geocoder();
            geocoder<?=$md5?>.geocode({ 'address': `Rua ${logradouro}, ${numero}, ${bairro}, Manaus, Amazonas, Brasil`, 'region': 'BR' }, (results, status) => {

                if (status == google.maps.GeocoderStatus.OK) {

                    if (results[0]) {

                        var latitude<?=$md5?> = results[0].geometry.location.lat();
                        var longitude<?=$md5?> = results[0].geometry.location.lng();

                        // var location<?=$md5?> = new google.maps.LatLng(latitude<?=$md5?>, longitude<?=$md5?>);
                        // marker<?=$md5?>.setPosition(location<?=$md5?>);
                        // map<?=$md5?>.setCenter(location<?=$md5?>);
                        // map<?=$md5?>.setZoom(18);

                        coordenadas = `${latitude<?=$md5?>},${longitude<?=$md5?>}`;

                        Carregando();

                        idUnico = localStorage.getItem("idUnico");
                        codUsr = localStorage.getItem("codUsr");
                        $.ajax({
                            url:"enderecos/form.php",
                            type:"POST",
                            dataType:"JSON",
                            data:{
                                idUnico,
                                codUsr,
                                cep,
                                logradouro,
                                numero,
                                complemento,
                                ponto_referencia,
                                bairro,
                                localidade,
                                uf,
                                coordenadas,
                                <?php
                                if($d->codigo){
                                ?>
                                codigo,
                                <?php
                                }
                                ?>
                                acao:'salvar'
                            },
                            success:function(dados){
                                console.log(dados)
                                $.ajax({
                                    url:"enderecos/lista_enderecos.php",
                                    type:"POST",
                                    data:{
                                        codUsr,
                                        idUnico
                                    },
                                    success:function(dados){
                                        $.alert('Endereço salvo com sucesso!');  
                                        $(".dados_enderecos").html(dados);
                                        Carregando('none');
                                    }
                                }) 

                            },
                            error:function(){
                                console.log('No erro')
                            }
                        });

                    }
                }
            })

        })
    })
</script>