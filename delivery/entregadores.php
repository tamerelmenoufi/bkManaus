<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['acao'] == 'consultar'){
        $query = "select * from entregadores where cpf = '{$_POST['cpf']}' and deletado != '1' and situacao != '1'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
        if($d->codigo){
            $d1 = rand(1,9);
            $d2 = rand(0,9);
            $d3 = rand(0,9);
            $d4 = rand(0,9);

            $cod = $d1.$d2.$d3.$d4;

            EnviarWapp($_POST['telefone'],"BK Manaus informe: Seu código de acesso é *{$cod}*");
            $retorno = [
                'status' => true,
                'entregador' => $d->codigo,
                'senha' => $cod,
                'loja' => $_POST['loja']
            ];
        }else{
            $retorno = [
                'status' => false,
                'loja' => false,
                'entregador' => false,
                'senha' => false
            ];            
        }
        echo json_encode($retorno);
        exit();
    }

    if($_POST['acao'] == 'gravar'){
        $query = "update entregadores set loja = '{$_POST['loja']}' where codigo = '{$_POST['entregador']}'";
        if(mysqli_query($con, $query)){
            $retorno = [
                'status' => true
            ];
        }else{
            $retorno = [
                'status' => false
            ];            
        }
        echo json_encode($retorno);
        exit();
    }

?>
<style>
    .barra_topo{
        position:absolute;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        top:0;
        width:100%;
        height:100px;
        background-color:#f4000a;
        color:#670600;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .barra_topo h2{
        color:#f6e13a;
    }
    .home_corpo{
        position: absolute;
        top:100px;
        bottom:0px;
        overflow:auto;
        background-color:#fff;
        left:0;
        right:0;
    }
</style>
<div class="barra_topo">
    <h2>Entregador</h2>
</div>

<div class="home_corpo">
    <div class="row g-0">
        <div class="col">
            <div class="alert alert-warning m-3" role="alert">
                Digite o CPF para logar no sistema
            </div>


            <ul class="list-group m-3">
                <li class="list-group-item">
                    Nome do entregador e a loja
                </li>
            </ul>

        </div>
    </div>
</div>

<script>
    $(function(){
        $("button[entregador]").click(function(){
            cpf = $("#cpf").val();
            loja = $("#loja").val();

            $.ajax({
                url:"entregadores.php",
                type:"POST",
                dataType:"JSON",
                data:{
                    cpf,
                    loja,
                    acao:'consultar'
                },
                success:function(dados){

                    dadosLoja = dados.loja;
                    dadosEntregador = dados.entregador;
                    dadosSenha = dados.senha;

                    if(dados.status == true){

                        $.confirm({
                            title: 'Senha de Acesso',
                            content: '' +
                            '<form action="" class="formName">' +
                            '<div class="form-group">' +
                            '<label>Digite seu código de acesso</label>' +
                            '<input type="text" placeholder="Código de acesso" class="senha form-control" required />' +
                            '</div>' +
                            '</form>',
                            buttons: {
                                formSubmit: {
                                    text: 'Submit',
                                    btnClass: 'btn-blue',
                                    action: function () {
                                        var senha = this.$content.find('.senha').val();
                                        if(!senha){
                                            $.alert({
                                                content:'Favor informe seu código de acesso!',
                                                type:'red'
                                            });
                                            return false;
                                        }else if(senha != dadosSenha){
                                            $.alert({
                                                content:'Código informado não confere!',
                                                type:'red'
                                            });
                                            return false;
                                        }
                                        
                                        $.ajax({
                                            url:"entregadores.php",
                                            type:"POST",
                                            dataType:"JSON",
                                            data:{
                                                entregador:dadosEntregador,
                                                loja:dadosLoja,
                                                acao:'gravar'
                                            },
                                            success:function(dados){
                                                if(dados.status == true){
                                                    localStorage.setItem("loja", dadosLoja);
                                                    localStorage.setItem("entregador", dadosEntregador);
                                                    window.location.href="./";
                                                }else{
                                                    $.alert('Erro na autenticação');
                                                    window.location.href="./";
                                                }
                                            }
                                        })
                                    
                                    }
                                },
                                cancel: function () {
                                    //close
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
                            }
                        });                        

                    }else{
                        $.alert("Dados não cadastrados no sistema!<br><br>Favor entre em contato com a gerência de sua loja.");
                    }

                }
            });





        })
    })
</script>

  </body>
</html>