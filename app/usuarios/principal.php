<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }
    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
    }

    $query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<style>


</style>


<?php
    //Se o usuário não possui cadastro no app
    if(!$d->codigo){
?>
    <div class="row g-0 p-3">
        <div class="col">
            <div class="mb-3">
                <label for="telefone" class="form-label">Telefone</label>
                <input type="text" inputmode="numeric" class="form-control" id="telefone" aria-describedby="telefoneAjuda">
                <div id="telefoneAjuda" class="form-text">Digite o seu número Telefone/WhatsApp identificação!</div>
            </div>
        </div>
    </div>
<?php
    }
?>


<script>

$(function(){
<?php
    //Se o usuário não possui cadastro no app
    if(!$d->codigo){
?>
    $(".desconectar").css("display","none");
    $("#telefone").mask("(99) 99999-9999");
    $("#telefone").keyup(function(){
        telefone = $(this).val();
        if(telefone.length == 15){
            Carregando();
            $(this).val("");
            idUnico = localStorage.getItem("idUnico");
            $.ajax({
                url:"usuarios/telefone_validar.php",
                type:"POST",
                dataType:"JSON",
                data:{
                    telefone,
                    idUnico
                },
                success:function(dados){
                    if(dados.status == 'success'){
                        Carregando('none');
                        $.confirm({
                            title: `Validar ${telefone}` ,
                            columnClass:'col-12',
                            content: '' +
                            '<form action="" class="FormValidarTelefone">' +
                            '<div class="mb-3">' +
                            '<label for="codigoValida" class="form-label">Telefone</label>' +
                            '<input type="text" inputmode="numeric" class="form-control codigoValida" id="codigoValida" aria-describedby="validarMensagem">' +
                            '<div id="validarMensagem" class="form-text">Digite o código enviado para você (Mensagem WhatsApp ou SMS)</div>' +
                            '</div>' +
                            '</form>',
                            buttons: {
                                formSubmit: {
                                    text: 'Validar',
                                    btnClass: 'btn-danger',
                                    action: function () {
                                        var codigoValida = this.$content.find('.codigoValida').val();
                                        if(!codigoValida){
                                            $.alert({
                                                type:"red",
                                                title:"Erro",
                                                content:'Digite o código enviado!',
                                                columnClass:'col-12',
                                            });
                                            return false;
                                        }else if(codigoValida.length != 4){
                                            $.alert({
                                                type:"red",
                                                title:"Erro",
                                                content:'O Código deve ser de 4 dígitos!',
                                                columnClass:'col-12',
                                            });
                                            return false;
                                        }else if(codigoValida != dados.codigo){
                                            $.alert({
                                                type:"red",
                                                title:"Erro",
                                                content:'O Código informado não confere!',
                                                columnClass:'col-12',
                                            });
                                            return false;
                                        }
                                        Carregando();
                                        $.ajax({
                                            url:"usuarios/dados.php",
                                            type:"POST",
                                            data:{
                                                telefone,
                                                idUnico
                                            },
                                            success:function(dados){
                                                Carregando('none');
                                                if(dados == 'error'){
                                                    $.alert({
                                                        type:"red",
                                                        title:"Erro",
                                                        content:'Erro no número do telefone informado!',
                                                        columnClass:'col-12',
                                                    });
                                                }else{
                                                    $(".dados_pessoais").html(dados);
                                                }
                                            }
                                        });
                                    }
                                },
                                cancel: {
                                    text: 'Cancelar',
                                    btnClass: 'btn-warning',
                                    action: function () {

                                    }
                                },
                            },
                            onContentReady: function () {

                                // bind to events
                                var jc = this;
                                this.$content.find('form').on('submit', function (e) {
                                    // if the user submits the form by pressing enter in the field.
                                    e.preventDefault();
                                    jc.$$formSubmit.trigger('click'); // reference the button and click it
                                });

                                $(".codigoValida").mask("9999");
                            }
                                
                        });
                    }else{
                        $.alert({
                            title:"Erro Telefone",
                            content:"Seu número está errado, favor conferir e repetir a ação.",
                            type:'red',
                            columnClass:'col-12',
                        })
                    }
                }
            });


        }
    })
<?php
    }else{
?>
    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");

    $.ajax({
        url:"usuarios/dados.php",
        type:"POST",
        data:{
            idUnico,
            codUsr
        },
        success:function(dados){
            $(".dados_pessoais").html(dados);
        }
    });

<?php
    }
?>


})

</script>