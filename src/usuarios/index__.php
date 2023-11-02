<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");
?>

<div class="container">
    <div class="row mt-3">
        <div class="d-flex justify-content-between">
            <div class="p-10"><h3>Usuários do Sistema</h3></div>
            <div class="p-2">
                <button class="btn btn-primary">
                    Novo
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-3">

        <div class="d-none d-md-block mb-2">
            <div class="row">
                <div class='col-md-4'><b>NOME</b></div>
                <div class='col-md-4'><b>E-MAIL</b></div>
                <div class='col-md-2'><b>SITUAÇÃO</b></div>
                <div class='col-md-2'><b>AÇÕES</b></div>
            </div>
        </div>

        <?php
            $query = "select * from usuarios order by nome limit 0, 20";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
        ?>
        <div class="row">
            <div class='col-md-4'><?=$d->nome?></div>
            <div class='col-md-4'><?=$d->email?></div>
            <div class='col-md-2'><?=$d->situacao?></div>
            <div class='col-md-2'>
                <div class="d-flex justify-content-between">
                    <button
                        class="btn btn-success"
                        data-bs-toggle="offcanvas"
                        href="#offcanvasDireita"
                        role="button"
                        aria-controls="offcanvasDireita"
                        editarUsuario="<?=$d->codigo?>"
                    >
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button
                        class="btn btn-danger"
                        excluirUsuario="<?=$d->codigo?>"
                    >
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?
            }
        ?>
    </div>
</div>

<script>
    $(function(){
        Carregando('none');
        $("button[editarUsuario]").click(function(){

            cod = $(this).attr("editarUsuario");
            Carregando();
            $.ajax({
                url:"src/usuarios/form.php",
                type:"POST",
                data:{
                    cod,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        });
    })
</script>