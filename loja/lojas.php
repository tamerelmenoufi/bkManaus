<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['loja']){
        $query = "select * from lojas where codigo = '{$_POST['loja']}' and senha = '{$_POST['senha']}'";
        $result = mysqli_query($con, $query);
        $d = mysqli_fetch_object($result);
        if($d->codigo){
            $retorno = [
                'status' => true,
                'loja' => $d->codigo
            ];
        }else{
            $retorno = [
                'status' => false,
                'loja' => false
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
    <h2>Lojas</h2>
</div>

<div class="home_corpo">
    <div class="row g-0">
        <div class="col">
            <div class="alert alert-warning m-3" role="alert">
            Selecione uma das lojas para acessar as comandas de pedidos.
            </div>


            <ul class="list-group m-3">
                <?php
                $query = "select * from lojas where situacao = '1' and deletado != '1' order by nome";
                $result = mysqli_query($con, $query);
                while($d = mysqli_fetch_object($result)){
                ?>
                    <li class="list-group-item" loja="<?=$d->codigo?>"><?=$d->nome?></li>
                <?php
                }
                ?>
            </ul>

        </div>
    </div>
</div>

<script>
    $(function(){
        $("li[loja]").click(function(){
            loja = $(this).attr("loja");



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
                                $.alert('Favor informe seu código de acesso!');
                                return false;
                            }
                            
                            $.ajax({
                                url:"lojas.php",
                                type:"POST",
                                dataType:"JSON",
                                data:{
                                    loja,
                                    senha
                                },
                                success:function(dados){
                                    if(dados.status == true){
                                        localStorage.setItem("loja", dados.loja);
                                        window.location.href="./";
                                    }else{
                                        $.alert({
                                            title:"Erro",
                                            content:"Dados incorretos!",
                                            columnCalss:'col-12',
                                            type:'red'
                                        })
                                    }
                                },
                                error:function(){
                                    console.log('erro no acesso')
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



        })
    })
</script>

  </body>
</html>