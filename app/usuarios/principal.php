<?php
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

    $("#telefone").mask("(99) 99999-9999");
    $("#telefone").keyup(function(){
        telefone = $(this).val();
        if(telefone.length == 15){
            idUnico = localStorage.getItem("idUnico");
            $.ajax({
                url:"usuarios/telefone_validar.php",
                type:"POST",
                dataType:"JSON",
                data:{
                    telefone:telefone,
                    idUnico
                },
                success:function(dados){
                    if(dados.status == 'novo'){



//////////////////////////////////////////////////////////////////////


$.confirm({
    title: `Validar ${telefone}` ,
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
                        Title:"Erro",
                        content:'Digite o código enviado!'
                    });
                    return false;
                }else if(codigoValida.length != 4){
                    $.alert({
                        type:"red",
                        Title:"Erro",
                        content:'O Código deve ser de 4 dígitos!'
                    });
                    return false;
                }else if(codigoValida != dados.codigo){
                    $.alert({
                        type:"red",
                        Title:"Erro",
                        content:'O Código informado não confere!'
                    });
                    return false;
                }
                $.alert('Your name is ' + codigoValida);
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






/////////////////////////////////////////////////////////////////////




                    }
                }
            });


        }
    })





})

</script>