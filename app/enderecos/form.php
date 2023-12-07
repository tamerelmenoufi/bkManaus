<?php
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
        $campos = [];
        foreach($data as $i => $v){
            $campos[] = "{$i} = '{$v}'";
        }
        $campos[] = "cliente = '{$_POST['codUsr']}'";
        mysqli_query($con, "replace into enderecos set ".implode(", ",$campos));
        
        echo json_encode([
            "status" => true
        ]);
        exit();
    }

    if($_POST['cep']){
        $cep = str_replace('-',false,$_POST['cep']);
        $d = ConsultaCEP($cep);
    }


?>

<div class="row g-0 mb-3 p-2">
        <h4 class="w-100 text-center">Endereços</h4>
        <div class="mb-1">
            <label for="cep" class="form-label">CEP*</label>
            <input type="text" class="form-control <?=(($d->cep)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->cep?>" id="cep">
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
            <input type="text" class="form-control" autocomplete="off" value="<?=$d->complemento?>" id="complemento">
        </div>  
        <div class="mb-1">
            <label for="ponto_referencia" class="form-label">Ponto de Referência*</label>
            <input type="text" class="form-control <?=(($d->ponto_referencia)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->ponto_referencia?>" id="ponto_referencia">
        </div>        
        <div class="mb-1">
            <label for="bairro" class="form-label">Bairro*</label>
            <input type="text" class="form-control <?=(($d->bairro)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->bairro?>" id="bairro">
        </div>  
        <div class="mb-2">
            <label for="localidade" class="form-label">Localidade*</label>
            <input type="text" class="form-control <?=(($d->localidade)?'is-valid':'is-invalid')?>" autocomplete="off" value="<?=$d->localidade?>" id="localidade">
            <input type="hidden" value="AM" id="uf">
        </div> 
        <div class="mb-1">
            <button type="button" class="btn btn-outline-success w-100 salvar_endereco">Salvar Endereço</button>
        </div>
        <div class="mb-1">
            <button type="button" class="btn btn-outline-danger w-100 cancelar_endereco">Cancelar</button>
        </div>
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
                    data:{
                        idUnico,
                        codUsr,
                        cep
                    },
                    success:function(dados){
                        $(".dados_enderecos").html(dados);                     
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
        

        $(".salvar_endereco").click(function(){
            cep = $("#cep").val();
            logradouro = $("#logradouro").val();
            numero = $("#numero").val();
            complemento = $("#complemento").val();
            ponto_referencia = $("#ponto_referencia").val();
            bairro = $("#bairro").val();
            localidade = $("#localidade").val();
            uf = $("#uf").val();

            if(cep.length > 0 && (cep.length != 9 || cep.substring(0,2) != 69)){
                $.alert({
                    title:"Erro",
                    content:"CEP inválido ou fora da área de atendimento",
                    type:"red"
                })
                return false;
            }

            if(
            !cep ||
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

            console.log('antes do salvar')

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
                            console.log('agora aqui')
                            $.alert('Endereço salvo com sucesso!');  
                            // JanelaForm.close();
                            $(".dados_enderecos").html(dados);
                        }
                    }) 

                },
                error:function(){
                    console.log('No erro')
                }
            });


        })
    })
</script>