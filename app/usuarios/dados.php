<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

    if($_POST['idUnico']){
        $_SESSION['idUnico'] = $_POST['idUnico'];
    }

    if($_POST['codUsr']){
        $_SESSION['codUsr'] = $_POST['codUsr'];
        $where = " where codigo = '{$_SESSION['codUsr']}'";
    }

    if($_SPOT['acao'] == 'atualizar'){
        mysqli_query($con, "update clientes set {$_POST['campo']} = '{$_POST['valor']}'");
        $retorno = [
            'status' => 'success',
            'idUnico' => $_SESSION['idUnico'],
            'codUsr' => $_SESSION['codUsr'],
        ];
        echo json_encode($retorno);
        exit();
    }

    if($_POST['telefone']){
        $q = "SELECT * from clientes WHERE telefone = '{$_POST['telefone']}'";
        $c = mysqli_fetch_object(mysqli_query($con, $q));
        if($c->codigo){
            $_SESSION['codUsr'] = $c->codigo;
        }else{
            mysqli_query($con, "INSERT INTO clientes set telefone = '{$_POST['telefone']}'");
            $_SESSION['codUsr'] = mysqli_insert_id($con);
        }
    }

    $query = "select * from clientes where codigo = '{$_SESSION['codUsr']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);

    echo $_SESSION['codUsr'] = $d->codigo;
    ////////////////

?>

<div class="row g-0 p-3">
    <div class="col">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome Completo</label>
            <input type="text" class="form-control formDados" id="nome">
        </div>
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control formDados" id="cpf">
        </div>
        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <div class="form-control is-valid" ><?=$d->telefone?></div>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control formDados" id="email">
        </div>        
    </div>
</div>


<script>
    $(function(){

        localStorage.setItem("codUsr", '<?=$_SESSION['codUsr']?>');

        ExecutaAtualizacao = (campo, valor)=>{
            $.ajax({
                url:"usuarios/dados.php",
                type:"POST",
                dataType:"JSON",
                data:{
                    campo,
                    valor,
                    acao:'atualizar'
                },
                success:function(dados){
                    console.log(dados)
                }
            })            
        }

        $(".formDados").change(function(){
            campo = $(this).attr("id");
            valor = $(this).val();
            if(campo == 'nome'){
                ExecutaAtualizacao(campo, valor);
            }else if(campo == 'cpf'){
                if(valor.length == 14){
                    ExecutaAtualizacao(campo, valor);
                }
            }else if(campo == 'email'){
                ExecutaAtualizacao(campo, valor);
            }
        })





    })
</script>