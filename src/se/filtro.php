<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

    if($_POST['acao'] == 'gerar_filtro'){

        $_SESSION['filtro_nome'] = $_POST['nome'];
        $_SESSION['filtro_cpf'] = $_POST['cpf'];
        $_SESSION['filtro_rg'] = $_POST['rg'];
        $_SESSION['filtro_telefone'] = $_POST['telefone'];
        $_SESSION['filtro_email'] = $_POST['email'];
        $_SESSION['filtro_municipio'] = $_POST['municipio'];
        $_SESSION['filtro_tipo'] = $_POST['tipo'];
        $_SESSION['filtro_bairro_comunidade'] = $_POST['bairro_comunidade'];

        exit();
    }

    if($_POST['acao'] == 'limpar_filtro'){

        $_SESSION['filtro_nome'] = false;
        $_SESSION['filtro_cpf'] =false;
        $_SESSION['filtro_rg'] = false;
        $_SESSION['filtro_telefone'] = false;
        $_SESSION['filtro_email'] = false;
        $_SESSION['filtro_municipio'] = false;
        $_SESSION['filtro_tipo'] = false;
        $_SESSION['filtro_bairro_comunidade'] = false;

        exit();
    }


    if($_POST['acao'] == 'bairro_comunidade'){

        $q = "select * from bairros_comunidades

                where
                        municipio = '{$_POST['municipio']}'
                        and tipo = '{$_POST['tipo']}'

                order by descricao";
        $r = mysqli_query($con, $q);
?>
        <option value="">::Selecione a Localização::</option>
<?php
        while($s = mysqli_fetch_object($r)){
?>
        <option value="<?=$s->codigo?>"><?=$s->descricao?> (<?=$s->tipo?>)</option>
<?php
        }
        exit();
    }
?>

<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    .Botao<?=$md5?>{
        position:absolute;
        right:20px;
        top:5px;
        z-index:0;
    }
</style>
<h4 class="Titulo<?=$md5?>">Filtro de Busca</h4>

<form id="form-<?= $md5 ?>">
    <div class="row">
        <div class="col">
            <?php
            if($_SESSION['ProjectSeLogin']->perfil != 'crd'){
            ?>

            <div class="card p-3 mb-3">
                <h5>Geral</h5>

                <div class="form-floating mb-3">
                    <select name="municipio" id="municipio" class="form-control" placeholder="Município">
                        <option value="">::Selecione o Município::</option>
                        <?php
                            $q = "select * from municipios order by municipio";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->codigo?>" <?=(($_SESSION['filtro_municipio'] == $s->codigo)?'selected':false)?>><?=$s->municipio?></option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="email">Município</label>
                </div>

                <div class="form-floating mb-3">
                    <select name="tipo" id="tipo" class="form-control" placeholder="Zona">
                        <option value="">::Selecione a Zona::</option>
                        <option value="Urbano" <?=(($_SESSION['filtro_tipo'] == 'Urbano')?'selected':false)?>>Urbano</option>
                        <option value="Rural" <?=(($_SESSION['filtro_tipo'] == 'Rural')?'selected':false)?>>Rural</option>
                    </select>
                    <label for="tipo">Zona</label>
                </div>

                <div class="form-floating mb-3">
                    <select name="bairro_comunidade" id="bairro_comunidade" class="form-control" placeholder="Bairro">
                        <option value="">::Selecione a Localização::</option>
                        <?php
                            $q = "select * from bairros_comunidades where municipio = '{$_SESSION['filtro_municipio']}' ".(($_SESSION['filtro_tipo'])?" and tipo = '{$_SESSION['filtro_tipo']}'":false)." order by descricao";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->codigo?>" <?=(($_SESSION['filtro_bairro_comunidade'] == $s->codigo)?'selected':false)?>><?=$s->descricao?> (<?=$s->tipo?>)</option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="bairro_comunidade">Bairro/Comunidade</label>
                </div>
            </div>
            <?php
            }
            ?>

            <div class="card p-3 mb-3">
                <h5>Específico</h5>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$_SESSION['filtro_nome']?>">
                    <label for="nome">Nome*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" value="<?=$_SESSION['filtro_cpf']?>">
                    <label for="cpf">CPF*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="rg" id="rg" class="form-control" placeholder="RG" value="<?=$_SESSION['filtro_rg']?>">
                    <label for="cpf">RG*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="telefone" value="<?=$_SESSION['filtro_telefone']?>">
                    <label for="telefone">Telefone*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$_SESSION['filtro_email']?>">
                    <label for="email">E-mail*</label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="Botao<?=$md5?>">
                <button
                    type="button"
                    GerarFiltro
                    class="btn btn-success btn-ms"

                    data-bs-toggle="offcanvas"
                    href="#offcanvasDireita"
                    role="button"
                    aria-controls="offcanvasDireita"

                >
                    <i class="fa-solid fa-filter"></i>
                </button>
                <button
                    type="button"
                    LimparFiltro
                    class="btn btn-danger btn-ms"
                >
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(function(){
        Carregando('none');

        $("#cpf").mask("999.999.999-99");
        $("#telefone").mask("(99) 99999-9999");

        var filtro = (bairro_comunidade, tipo) => {
            if(!municipio){
                $("#bairro_comunidade").html('<option value="">::Selecione a Localização::</option>');
                return false;
            }
            if(!tipo){
                $("#bairro_comunidade").html('<option value="">::Selecione a Localização::</option>');
                return false;
            }
            $.ajax({
                url:"src/se/filtro.php",
                type:"POST",
                data:{
                    municipio,
                    tipo,
                    acao:'bairro_comunidade'
                },
                success:function(dados){
                    $("#bairro_comunidade").html(dados);
                }
            });
        }

        $("#tipo").change(function(){
            municipio = $("#municipio").val();
            tipo = $(this).val();
            filtro(municipio, tipo);
        });

        $("#municipio").change(function(){
            tipo = $("#tipo").val();
            municipio = $(this).val();
            filtro(municipio, tipo);
        });

        $("button[GerarFiltro]").click(function(){

            nome = $("#nome").val();
            cpf = $("#cpf").val();
            rg = $("#rg").val();
            telefone = $("#telefone").val();
            email = $("#email").val();
            municipio = $("#municipio").val();
            tipo = $("#tipo").val();
            bairro_comunidade = $("#bairro_comunidade").val();

            Carregando();
            $.ajax({
                url:"src/se/filtro.php",
                type:"POST",
                data:{
                    nome,
                    cpf,
                    rg,
                    telefone,
                    email,
                    municipio,
                    tipo,
                    bairro_comunidade,
                    acao:'gerar_filtro'
                },
                success:function(dados){
                    $.ajax({
                        url:"src/se/index.php",
                        success:function(dados){
                            $("#paginaHome").html(dados);
                        }
                    });
                }
            });
        });

        $("button[LimparFiltro]").click(function(){

            $.confirm({
                content:"Deseja realmente limpar o filtro?",
                title:false,
                buttons:{
                    'SIM':function(){
                        Carregando();
                        $.ajax({
                            url:"src/se/filtro.php",
                            type:"POST",
                            data:{
                                acao:'limpar_filtro'
                            },
                            success:function(dados){

                                $.ajax({
                                    url:"src/se/index.php",
                                    success:function(dados){
                                        $("#paginaHome").html(dados);
                                    }
                                });

                            }
                        });
                        $("#nome").val('');
                        $("#cpf").val('');
                        $("#rg").val('');
                        $("#telefone").val('');
                        $("#email").val('');
                        $("#municipio").val('');
                        $("#tipo").val('');
                        $("#bairro_comunidade").html('<option value="">::Selecione a Localização::</option>');
                        // let myOffCanvas = document.getElementById('offcanvasDireita');
                        // let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                        // openedCanvas.hide();

                    },
                    'NÃO':function(){

                    }
                }
            });


        })

    })
</script>