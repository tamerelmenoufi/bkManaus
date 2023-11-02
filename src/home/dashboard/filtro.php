<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");


    $Rotulo = [
        'genero' => 'gênero',
        'estado_civil'  => 'Estado Civil',
        'redes_sociais'  => 'Redes sociais',
        'meio_transporte'  => 'Utiliza como meio de transporte',
        'tipo_imovel'  => 'habita em imóvel',
        'tipo_moradia'  => 'tipo de moradia',
        'quantidade_comodos'  => 'residencia com',
        'grau_escolaridade'  => 'com grau de escolaridade',
        'curos_profissionais'  => 'e que possua cursos profissioais?',
        'renda_mensal'  => 'renda mensal de ',
        'beneficio_social'  => 'que possua beneficíos sociais',
        'vacina_covid'  => 'que tenha tomado a vaciana contra covid',
        'necessita_documentos'  => 'necessita de documentos',
        'avaliacao_beneficios'  => 'avalia os benefícios',
        'atende_necessidades'  => 'benefícios atendem necessidades?',
        'opiniao_saude'  => 'opinão na saúde?',
        'opiniao_infraestrutura'  => 'Opinião na infraestrutura?',
        'opiniao_assistencia_social' => 'Opinião na assistência social?',
        'opiniao_seguranca'  => 'Opinião na segurança?',
        'opiniao_esporte_lazer'  => 'Opinião no esporte e lazer?',
        'recepcao_entrevistado'  => 'recepção do entrvistado',
    ];


    if($_POST['acao'] == 'gerar_filtro'){

        // $_SESSION['filtro_relatorio_nome'] = $_POST['nome'];
        // $_SESSION['filtro_relatorio_cpf'] = $_POST['cpf'];
        // $_SESSION['filtro_relatorio_rg'] = $_POST['rg'];
        // $_SESSION['filtro_relatorio_telefone'] = $_POST['telefone'];
        // $_SESSION['filtro_relatorio_email'] = $_POST['email'];
        $_SESSION['filtro_relatorio_municipio'] = $_POST['municipio'];
        $_SESSION['filtro_relatorio_tipo'] = $_POST['tipo'];
        $_SESSION['filtro_relatorio_bairro_comunidade'] = $_POST['bairro_comunidade'];
        $_SESSION['filtro_especifico'] = [];
        $_SESSION['filtro_especifico_descricao'] = [];
        $filtro_especifico = [];
        for($i=0;$i<count($_POST['especifico']);$i++){
            $campo = str_replace('[]', false, trim($_POST['especifico'][$i]['name']));
            $valor = trim($_POST['especifico'][$i]['value']);
            $filtro_especifico[$campo][] = $valor;
        }
        $_SESSION['fe']  = $filtro_especifico;

        $filtro_preparo2 = [];
        $filtro_preparo_descricao2 = [];
        foreach($filtro_especifico as $campo => $valores){
            $filtro_preparo = [];
            $filtro_preparo_descricao = [];
            for($i=0;$i<count($valores);$i++){
                $filtro_preparo[] = "{$campo} LIKE '%{$valores[$i]}%'";
                $filtro_preparo_descricao[] = "<i>{$valores[$i]}</i>";
            }
            $filtro_preparo2[] = " (".implode(" or ",$filtro_preparo).")";
            $filtro_preparo_descricao2[] = "<i>". $Rotulo[$campo] . "</i> " . implode(" ou ",$filtro_preparo_descricao);
        }
        if($filtro_preparo2){
            $_SESSION['filtro_especifico'] = implode(" or ",$filtro_preparo2);
            $_SESSION['filtro_especifico_descricao'] = "A busca específica determina a listagem dos cadastros que Contem: ".implode(", ",$filtro_preparo_descricao2);

        }



        exit();
    }

    if($_POST['acao'] == 'limpar_filtro'){

        // $_SESSION['filtro_relatorio_nome'] = false;
        // $_SESSION['filtro_relatorio_cpf'] =false;
        // $_SESSION['filtro_relatorio_rg'] = false;
        // $_SESSION['filtro_relatorio_telefone'] = false;
        // $_SESSION['filtro_relatorio_email'] = false;
        $_SESSION['filtro_relatorio_municipio'] = false;
        $_SESSION['filtro_relatorio_tipo'] = false;
        $_SESSION['filtro_relatorio_bairro_comunidade'] = false;

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

            <div class="card p-3 mb-3">
                <h5>Geral</h5>

                <div class="form-floating mb-3">
                    <select id="municipio" class="form-control" placeholder="Município">
                        <option value="">::Selecione o Município::</option>
                        <?php
                            $q = "select * from municipios order by municipio";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->codigo?>" <?=(($_SESSION['filtro_relatorio_municipio'] == $s->codigo)?'selected':false)?>><?=$s->municipio?></option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="email">Município</label>
                </div>

                <div class="form-floating mb-3">
                    <select id="tipo" class="form-control" placeholder="Zona">
                        <option value="">::Selecione a Zona::</option>
                        <option value="Urbano" <?=(($_SESSION['filtro_relatorio_tipo'] == 'Urbano')?'selected':false)?>>Urbano</option>
                        <option value="Rural" <?=(($_SESSION['filtro_relatorio_tipo'] == 'Rural')?'selected':false)?>>Rural</option>
                    </select>
                    <label for="tipo">Zona</label>
                </div>

                <div class="form-floating mb-3">
                    <select id="bairro_comunidade" class="form-control" placeholder="Bairro">
                        <option value="">::Selecione a Localização::</option>
                        <?php
                            $q = "select * from bairros_comunidades where municipio = '{$_SESSION['filtro_relatorio_municipio']}' ".(($_SESSION['filtro_relatorio_tipo'])?" and tipo = '{$_SESSION['filtro_relatorio_tipo']}'":false)." order by descricao";
                            $r = mysqli_query($con, $q);
                            while($s = mysqli_fetch_object($r)){
                        ?>
                        <option value="<?=$s->codigo?>" <?=(($_SESSION['filtro_relatorio_bairro_comunidade'] == $s->codigo)?'selected':false)?>><?=$s->descricao?> (<?=$s->tipo?>)</option>
                        <?php
                            }
                        ?>
                    </select>
                    <label for="bairro_comunidade">Bairro/Comunidade</label>
                </div>
            </div>

            <!-- <div class="card p-3 mb-3">
                <h5>Específico</h5>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$_SESSION['filtro_relatorio_nome']?>">
                    <label for="nome">Nome*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" value="<?=$_SESSION['filtro_relatorio_cpf']?>">
                    <label for="cpf">CPF*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="rg" id="rg" class="form-control" placeholder="RG" value="<?=$_SESSION['filtro_relatorio_rg']?>">
                    <label for="cpf">RG*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="telefone" id="telefone" class="form-control" placeholder="telefone" value="<?=$_SESSION['filtro_relatorio_telefone']?>">
                    <label for="telefone">Telefone*</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$_SESSION['filtro_relatorio_email']?>">
                    <label for="email">E-mail*</label>
                </div>

            </div> -->
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="Botao<?=$md5?>">
                <button
                    type="submit"
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

    <div class="card p-3 mb-3">
        <h5>Filtro Específico</h5>
        <div especifico></div>
    </div>
</form>


<script>
    $(function(){
        // Carregando('none');

        $.ajax({
            url:"src/home/dashboard/filtro_especifico.php",
            success:function(dados){
                $("div[especifico]").html(dados);
            }
        });

        // $("#cpf").mask("999.999.999-99");
        // $("#telefone").mask("(99) 99999-9999");

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
                url:"src/home/dashboard/filtro.php",
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

        $('#form-<?=$md5?>').submit(function (e) {


            e.preventDefault();

            especifico = $(this).serializeArray();

            console.log(especifico);
            // nome = $("#nome").val();
            // cpf = $("#cpf").val();
            // rg = $("#rg").val();
            // telefone = $("#telefone").val();
            // email = $("#email").val();
            municipio = $("#municipio").val();
            tipo = $("#tipo").val();
            bairro_comunidade = $("#bairro_comunidade").val();

            Carregando();
            $.ajax({
                url:"src/home/dashboard/filtro.php",
                type:"POST",
                data:{
                    // nome,
                    // cpf,
                    // rg,
                    // telefone,
                    // email,
                    especifico,
                    municipio,
                    tipo,
                    bairro_comunidade,
                    acao:'gerar_filtro'
                },
                success:function(dados){
                    $.ajax({
                        url:"src/home/dashboard/index.php",
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
                            url:"src/home/dashboard/filtro.php",
                            type:"POST",
                            data:{
                                acao:'limpar_filtro'
                            },
                            success:function(dados){

                                $.ajax({
                                    url:"src/home/dashboard/index.php",
                                    success:function(dados){
                                        $("#paginaHome").html(dados);
                                    }
                                });

                            }
                        });
                        // $("#nome").val('');
                        // $("#cpf").val('');
                        // $("#rg").val('');
                        // $("#telefone").val('');
                        // $("#email").val('');
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