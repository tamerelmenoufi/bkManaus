<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    $_SESSION['filtro_atual_reg'] = ($_SESSION['filtro_atual_reg'] + 50);


    if($_SESSION['where_ou']){
        $where = implode(" AND ", $_SESSION['where_ou']);
    }

    $query = "select
                    a.*,
                    b.municipio as municipio,
                    c.descricao as bairro_comunidade
                from se a
                    left join municipios b on a.municipio = b.codigo
                    left join bairros_comunidades c on a.bairro_comunidade = c.codigo

                where 1=1 ".(($where)?" AND ".$where:false)."

                order by nome limit {$_SESSION['filtro_atual_reg']}, 50";
    $result = mysqli_query($con, $query);
    $total = mysqli_num_rows($result);

    while($d = mysqli_fetch_object($result)){
?>

    <div class="row">
            <div class="col">
                <div class="card mt-3">
                    <h5 class="card-header"><?=$d->nome?> - <?=$d->cpf?></h5>
                    <div class="card-body">

                        <h5 class="card-title"><?=$d->municipio?> - <?=$d->bairro_comunidade?> (<?=$d->local?>)</h5>
                        <p class="card-text">
                            <?=$d->telefone?><br><?=$d->email?><br><?=$d->situacao?>
                        </p>

                        <button
                            class="btn btn-primary"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"
                            pesquisaSe<?=$md5?>="<?=$d->codigo?>"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            class="btn btn-primary"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"
                            SeEf<?=$md5?>="<?=$d->codigo?>"
                        >
                            <i class="fa-solid fa-users"></i>
                        </button>

                    </div>
                </div>
            </div>
        </div>

<?php
    }
    if($_SESSION['filtro_atual_reg'] <= $_SESSION['filtro_total_reg']){
?>
        <div class="row">
            <button maisResultados type="button" class="btn btn-outline-success btn-block mt-3 mb-3">
                Carregar mais resultados
            </button>
        </div>
<?php
    }
?>

<script>

$(function(){

    $("button[pesquisaSe<?=$md5?>]").click(function(){
        // $(document).on('click',"button[pesquisaSe]",function(){

            cod = $(this).attr("pesquisaSe<?=$md5?>");
            Carregando();
            $.ajax({
                url:"src/se/se.php",
                type:"POST",
                data:{
                    cod,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        });


    $("button[SeEf<?=$md5?>]").click(function(){
        // $(document).on('click',"button[SeEf]",function(){

            cod = $(this).attr("SeEf<?=$md5?>");

            Carregando();
            $.ajax({
                url:"src/se/ef.php",
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