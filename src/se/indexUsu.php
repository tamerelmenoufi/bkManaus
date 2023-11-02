<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['delFiltro']){
        $_SESSION[$_POST['delFiltro']] = false;
        // exit();
    }

    function Filtros($t){
        global $_SESSION;
        global $con;
        $retorno = [];
        $_SESSION['where_ou'] = [];
        if($_SESSION['filtro_nome']){
            $retorno[] = "<span class='rotuloFultro'>{$_SESSION['filtro_nome']} <i class='fa-solid fa-xmark' delFiltro='filtro_nome'></i></span>";
            // $n = explode(" ",$_SESSION['filtro_nome']);
            // $v = [];
            // for($i=0;$i<count($n);$i++){
            //     $v[] = "a.nome LIKE '%{$n[$i]}%'";
            // }
            // $_SESSION['where_ou'][] = "(".implode(" OR ", $v).")";
            $_SESSION['where_ou'][] = "a.nome LIKE '%{$_SESSION['filtro_nome']}%'";

        }
        if($_SESSION['filtro_cpf']){
            $retorno[] = "<span class='rotuloFultro'>{$_SESSION['filtro_cpf']} <i class='fa-solid fa-xmark' delFiltro='filtro_cpf'></i></span>";
            $_SESSION['where_ou'][] = "a.cpf = '{$_SESSION['filtro_cpf']}'";
        }
        if($_SESSION['filtro_rg']){
            $retorno[] = "<span class='rotuloFultro'>{$_SESSION['filtro_rg']} <i class='fa-solid fa-xmark' delFiltro='filtro_rg'></i></span>";
            $_SESSION['where_ou'][] = "a.rg = '{$_SESSION['filtro_rg']}'";
        }
        if($_SESSION['filtro_telefone']){
            $retorno[] = "<span class='rotuloFultro'>{$_SESSION['filtro_telefone']} <i class='fa-solid fa-xmark' delFiltro='filtro_telefone'></i></span>";
            $_SESSION['where_ou'][] = "a.telefone = '{$_SESSION['filtro_telefone']}'";
        }
        if($_SESSION['filtro_email']){
            $retorno[] = "<span class='rotuloFultro'>{$_SESSION['filtro_email']} <i class='fa-solid fa-xmark' delFiltro='filtro_email'></i></span>";
            $_SESSION['where_ou'][] = "a.email = '{$_SESSION['filtro_email']}'";
        }
        if($_SESSION['filtro_municipio']){
            list($dado) = mysqli_fetch_row(mysqli_query($con, "select municipio from municipios where codigo = '{$_SESSION['filtro_municipio']}'"));
            $retorno[] = "<span class='rotuloFultro'>{$dado} <i class='fa-solid fa-xmark' delFiltro='filtro_municipio'></i></span>";
            $_SESSION['where_ou'][] = "a.municipio = '{$_SESSION['filtro_municipio']}'";
        }
        if($_SESSION['filtro_tipo']){
            $retorno[] = "<span class='rotuloFultro'>{$_SESSION['filtro_tipo']} <i class='fa-solid fa-xmark' delFiltro='filtro_tipo'></i></span>";
            $_SESSION['where_ou'][] = "a.local = '{$_SESSION['filtro_tipo']}'";
        }
        if($_SESSION['filtro_bairro_comunidade']){
            list($dado) = mysqli_fetch_row(mysqli_query($con, "select descricao from bairros_comunidades where codigo = '{$_SESSION['filtro_bairro_comunidade']}'"));
            $retorno[] = "<span class='rotuloFultro'>{$dado} <i class='fa-solid fa-xmark' delFiltro='filtro_bairro_comunidade'></i></span>";
            $_SESSION['where_ou'][] = "a.bairro_comunidade = '{$_SESSION['filtro_bairro_comunidade']}'";
        }

        if($retorno){
            echo '<b>FILTRO: <small total style="color:blue; font-size:14px;"></small> </b>' . implode(" ", $retorno);
        }

    }

?>
<style>
    .rotuloFultro{
        padding:5px;
        background-color:#d2ffc8;
        border-radius:7px;
        border:#eee;
        font-size:12px;
        color:#333;
        width:auto;
        margin:2px;
        float:left;
    }
    .rotuloFultro i{
        color:red;
        font-size:14px;
        font-weight:bold;
        padding-left:5px;
        cursor:pointer;
    }
    .SemRegistros{
        display:flex;
        flex-direction:column;
        justify-content:center;
        align-items:center;
        width:100%;
        min-height:400px;
        color:#a1a1a1;
    }
    .SemRegistros i{
        font-size:100px;
    }
    .SemRegistros p{
        font-size:10px;
    }
</style>
<div class="container">
    <div class="row mt-3">
        <div class="d-flex justify-content-between">
            <div class="p-10"><h3>Registros de Beneficiarios</h3></div>
            <div class="p-2">
                <!-- <button class="btn btn-primary">
                    Novo
                </button> -->
                <button
                    class="btn btn-warning"
                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"
                    BuscaFiltro
                >
                    <i class="fa-solid fa-filter"></i> Filtro
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div CampoResultados>
        <?php

            echo "<div class='row'>";
            Filtros($total);
            echo "</div>";

            if($_SESSION['where_ou']){
                $where = implode(" AND ", $_SESSION['where_ou']);
            }

            if($_SESSION['where_ou']){

             $query = "select
                            a.*,
                            b.municipio as municipio,
                            c.descricao as bairro_comunidade
                        from se a
                            left join municipios b on a.municipio = b.codigo
                            left join bairros_comunidades c on a.bairro_comunidade = c.codigo

                        where 1=1 ".(($where)?" AND ".$where:false)."

                        order by nome";
            $result = mysqli_query($con, $query);
            $total = mysqli_num_rows($result);
            $_SESSION['filtro_total_reg'] = $total;
            $_SESSION['filtro_atual_reg'] = 50;

            $query = $query." limit 0, 50";
            $result = mysqli_query($con, $query);


            if(mysqli_num_rows($result)){
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

                        <!-- <button
                            class="btn btn-success"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"
                            editarSe="<?=$d->codigo?>"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button> -->

                        <button
                            class="btn btn-primary"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"
                            pesquisaSe="<?=$d->codigo?>"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>

                        <button
                            class="btn btn-primary"
                            data-bs-toggle="offcanvas"
                            href="#offcanvasDireita"
                            role="button"
                            aria-controls="offcanvasDireita"
                            SeEf="<?=$d->codigo?>"
                        >
                            <i class="fa-solid fa-users"></i>
                        </button>

                        <!-- <button
                            class="btn btn-danger"
                            excluirSe="<?=$d->codigo?>"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button> -->

                    </div>
                </div>
            </div>
        </div>

        <?
            }
            if($_SESSION['filtro_total_reg'] >= $_SESSION['filtro_atual_reg']){
        ?>
        <div class="row">
            <button maisResultados type="button" class="btn btn-outline-success btn-block mt-3 mb-3">
                Carregar mais resultados
            </button>
        </div>
        <?php
            }
        ?>
        </div>
        <?php
            }else{
        ?>
        <div class="SemRegistros">
            <i class="fa-solid fa-face-sad-tear"></i>
            <p>Não foi encontrado nenhum registro com o filtro definido!</p>
        </div>
        <?php
            }
        ?>
    </div>
</div>
<?php
        }else{
?>
        <div class="SemRegistros">
            <i class="fa-solid fa-users"></i>
            <p>Utilize o Filtro para consultar os beneficiários</p>
        </div>
<?php
        }
?>
<script>
    $(function(){

        Carregando('none');

        $("small[total]").html('<?=$total?> Registro(s)')

        $("button[editarSe]").click(function(){

            cod = $(this).attr("editarSe");
            Carregando();
            $.ajax({
                url:"src/se/form.php",
                type:"POST",
                data:{
                    cod,
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        });


        $("button[pesquisaSe]").click(function(){
        // $(document).on('click',"button[pesquisaSe]",function(){

            cod = $(this).attr("pesquisaSe");
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

        $("button[SeEf]").click(function(){

            cod = $(this).attr("SeEf");

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

        $("button[BuscaFiltro]").click(function(){

            Carregando();
            $.ajax({
                url:"src/se/filtro.php",
                type:"POST",
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        });


        $("i[delFiltro]").click(function(){
            delFiltro = $(this).attr("delFiltro");
            Carregando();
            $.ajax({
                url:"src/se/index.php",
                type:"POST",
                data:{
                    delFiltro
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            });

        });


        $(document).off('click').on('click', 'button[maisResultados]', function(){
            obj = $(this);
            Carregando();
            $.ajax({
                url:"src/se/carregando.php",
                success:function(dados){
                    $("div[CampoResultados]").append(dados);
                    obj.remove();
                    Carregando('none');
                }
            });
        });
    })
</script>