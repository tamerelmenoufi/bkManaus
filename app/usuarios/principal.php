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
        valor = $(this).val();
        if(valor.length == 15){
            $.alert('completou os 15')
        }
    })


})

</script>