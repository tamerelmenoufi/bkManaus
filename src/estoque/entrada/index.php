<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");


    if($_POST['acao'] == 'excluir'){

        $query = "delete from movimentacao where cod_nota = '{$_POST['excluir']}'";
        mysqli_query($con, $query);

        $query = "delete from notas where codigo = '{$_POST['excluir']}'";
        mysqli_query($con, $query);

        if(is_file("../../volumes/notas/xml/{$_POST['xml']}")) unlink("../../volumes/notas/xml/{$_POST['xml']}");

    }


    if($_POST['acao'] == 'estoque'){

        $query = "select *, if(qConv > 0, qConv, 1) as qConv from movimentacao where cod_nota = '{$_POST['estoque']}'";
        $result = mysqli_query($con, $query);
        while($p = mysqli_fetch_object($result)){

            echo $query = "update estoque_{$_POST['empresa']} set 
                                        uCom = '{$p->uConv}',
                                        qCom = (qCom + ".($p->qConv * $p->qCom)."),
                                        vUnCom = '{$p->vUnConv}',
                                        situacao = '1'
                    where cProd = '{$p->cProd}'
            ";
            mysqli_query($con, $query);

        }

        mysqli_query($con, "update notas set situacao = '2' where codigo = '{$_POST['estoque']}'");

    }


    if($_POST['acao'] == 'upload_xml'){

        if(!is_dir("../../volumes/")) mkdir("../../volumes/");
        if(!is_dir("../../volumes/notas/")) mkdir("../../volumes/notas");
        if(!is_dir("../../volumes/notas/xml/")) mkdir("../../volumes/notas/xml/");
        
        $arquivo = str_replace("data:text/xml;base64,", false, $_POST['base64']);
        $nome = md5($_POST['base64']).".xml";

        if(file_put_contents("../../volumes/notas/xml/{$nome}", base64_decode($arquivo))){

            $xml = simplexml_load_file("../../volumes/notas/xml/{$nome}");
            $json = json_encode($xml);

            $query = "insert into notas set dados = '{$json}', data = NOW(), xml = '{$nome}', situacao = '0'";
            mysqli_query($con, $query);
            $cod_nota = mysqli_insert_id($con);


            ///////////////////////// EMPRESAS ////////////////////////////////
            //fornecedor
            $f = $xml->NFe->infNFe->emit;

            $query = "INSERT INTO empresas SET 
                                            cnpj = '{$f->CNPJ}',
                                            nome = '{$f->xNome}',
                                            fantasia = '{$f->xNome}',
                                            inscricao_estadual = '{$f->IE}',
                                            inscricao_municipal = '{$f->IM}',
                                            inscricao_suframa = '{$f->ISUF}',
                                            registro_crt = '{$f->CRT}',
                                            logradouro = '{$f->enderEmit->xLgr}',
                                            numero = '{$f->enderEmit->nro}',
                                            complemento = '{$f->enderEmit->xCpl}',
                                            bairro = '{$f->enderEmit->xBairro}',
                                            cod_municipio = '{$f->enderEmit->cMun}',
                                            municipio = '{$f->enderEmit->xMun}',
                                            uf = '{$f->enderEmit->UF}',
                                            cep = '{$f->enderEmit->CEP}',
                                            cod_pais = '{$f->enderEmit->cPais}',
                                            pais = '{$f->enderEmit->xPais}',
                                            telefone = '{$f->enderEmit->fone}',
                                            email = '{$f->email}',
                                            coordenadas = '',
                                            tipo = 'f',
                                            situacao = '1',
                                            certificado = '',
                                            deletado = '0'
            ";

            mysqli_query($con, $query);

            //grupo
            $g = $xml->NFe->infNFe->dest;

            $query = "INSERT INTO empresas SET 
                                            cnpj = '{$g->CNPJ}',
                                            nome = '{$g->xNome}',
                                            fantasia = '{$g->xNome}',
                                            inscricao_estadual = '{$g->IE}',
                                            inscricao_municipal = '{$g->IM}',
                                            inscricao_suframa = '{$g->ISUF}',
                                            registro_crt = '{$f->CRT}',
                                            logradouro = '{$g->enderDest->xLgr}',
                                            numero = '{$g->enderDest->nro}',
                                            complemento = '{$g->enderDest->xCpl}',
                                            bairro = '{$g->enderDest->xBairro}',
                                            cod_municipio = '{$g->enderDest->cMun}',
                                            municipio = '{$g->enderDest->xMun}',
                                            uf = '{$g->enderDest->UF}',
                                            cep = '{$g->enderDest->CEP}',
                                            cod_pais = '{$g->enderDest->cPais}',
                                            pais = '{$g->enderDest->xPais}',
                                            telefone = '{$g->enderDest->fone}',
                                            email = '{$g->email}',
                                            coordenadas = '',
                                            tipo = 'g',
                                            situacao = '1',
                                            certificado = '',
                                            deletado = '0'
            ";

            mysqli_query($con, $query);

            list($estoque) = mysqli_fetch_row(mysqli_query($con, "select codigo from empresas where cnpj = '{$g->CNPJ}'"));

            ///////////////////////////////////////////////////////////////////

            mysqli_query($con,
                "CREATE TABLE `estoque_{$estoque}` (
                                        `codigo` bigint(20) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                                        `cProd` char(30) NOT NULL,
                                        `cEAN` char(100) NOT NULL,
                                        `xProd` varchar(255) NOT NULL,
                                        `NCM` char(30) NOT NULL,
                                        `CEST` char(30) NOT NULL,
                                        `CFOP` char(30) NOT NULL,
                                        `uCom` char(20) NOT NULL,
                                        `qCom` decimal(20,4) NOT NULL,
                                        `vUnCom` decimal(20,10) NOT NULL,
                                        `situacao` enum('0','1') NOT NULL COMMENT '0 = Bloqueado, 1 = Liberado'
                                        )"
            );


            ///////////////////////////////////////////////////////////////////


            foreach($xml->NFe->infNFe->det as $i => $val){

                //print_r($val);
                $p = $val->prod;
                $imposto = json_encode($val->imposto);
                $nItem = $val['nItem'];

                $query = "insert into estoque_{$estoque} set 
                                                cProd = '{$p->cProd}',
                                                cEAN = '{$p->cEAN}',
                                                xProd = '{$p->xProd}',
                                                NCM = '{$p->NCM}',
                                                CEST = '{$p->CEST}',
                                                CFOP = '{$p->CFOP}',
                                                uCom = '{$p->uCom}',
                                                qCom = 0,
                                                vUnCom = '".($p->vUnCom)."',
                                                situacao = '1'
                ";
                mysqli_query($con,$query);     

                $query = "insert into movimentacao set 
                                                       cod_nota = '{$cod_nota}',
                                                       data = NOW(),
                                                       tipo = 'e',
                                                       nItem = '{$nItem}',
                                                       cProd = '{$p->cProd}',
                                                       cEAN = '{$p->cEAN}',
                                                       xProd = '{$p->xProd}',
                                                       NCM = '{$p->NCM}',
                                                       CEST = '{$p->CEST}',
                                                       CFOP = '{$p->CFOP}',
                                                       uCom = '{$p->uCom}',
                                                       qCom = '{$p->qCom}',
                                                       vUnCom = '{$p->vUnCom}',

                                                       uConv = (select uCom from estoque_{$estoque} where cProd = '{$p->cProd}'),
                                                       qConv = (select qCom from estoque_{$estoque} where cProd = '{$p->cProd}'),
                                                       vUnConv = (select vUnCom from estoque_{$estoque} where cProd = '{$p->cProd}'),

                                                       vProd = '{$p->vProd}',
                                                       cEANTrib = '{$p->cEANTrib}',
                                                       uTrib = '{$p->uTrib}',
                                                       qTrib = '{$p->qTrib}',
                                                       vUnTrib = '{$p->vUnTrib}',
                                                       indTot = '{$p->indTot}',
                                                       imposto = '{$imposto}'
                ";
                mysqli_query($con,$query);

           

            }




        }

    }

?>

<style>
    table{
        font-size:12px;
    }
</style>

<div class="m-3">
    <div class="row g-0">
        <div class="col-md-12">
            <h3>Estoque - Entrada por arquivo XML</h3>
            <div class="card">
                <div class="card-header">
                    Indentificação
                </div>
                <div class="card-body">
                    <p class="card-text">Anexe o arquivo XML da nota para iniciar o processo de entrada.</p>
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Anexe o arquivo XML</label>
                        <input class="form-control form-control-sm" id="formFileSm" type="file">
                        <input id="dadosXML" base64="" nome="" tipo="" type="hidden">
                    </div>
                    <a href="#" class="btn btn-primary btn-sm incluir_nota">Incluir Nota</a>
                </div>
            </div>
        </div>
    </div>




    <div class="row g-0">
        <div class="col-md-12">
            <h3>Notas Inseridas</h3>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>UF</th>
                        <th>Código</th>
                        <th>Natureza</th>
                        <th>Modelo</th>
                        <th>Série</th>
                        <th>Nota</th>
                        <th>Emissão</th>
                        <th>Data</th>
                        <th>Operação</th>
                        <th>Destino</th>
                        <th>Município</th>
                        <!-- <th>Impressão</th>
                        <th>Emissão</th>
                        <th>Dígito</th>
                        <th>Ambiente</th>
                        <th>Finalidade</th>
                        <th>Consumidor</th>
                        <th>Presencial</th>
                        <th>Intermediador</th>
                        <th>Aplicativo</th>
                        <th>Versão</th> -->
                        <th>Ações</th>

                    </tr>
                </thead>
                <tbody>
            <?php
            $query = "select a.*, b.codigo as empresa from notas a left join empresas b on a.dados->'$.dest.CNPJ' = b.cnpj";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
                $n = json_decode($d->dados);

                $c = $n->NFe->infNFe->ide;

            ?>
                    <tr>
                        <td><?=$c->cUF?></td>                    
                        <td><?=$c->cNF?></td>                    
                        <td><?=$c->natOp?></td>                    
                        <td><?=$c->mod?></td>                    
                        <td><?=$c->serie?></td>                    
                        <td><?=$c->nNF?></td>                    
                        <td><?=$c->dhEmi?></td>                    
                        <td><?=$c->dhSaiEnt?></td>                    
                        <td><?=$c->tpNF?></td>                    
                        <td><?=$c->idDest?></td>                    
                        <td><?=$c->cMunFG?></td>                    
                        <!-- <td><?=$c->tpImp?></td>                    
                        <td><?=$c->tpEmis?></td>                    
                        <td><?=$c->cDV?></td>                    
                        <td><?=$c->tpAmb?></td>                    
                        <td><?=$c->finNFe?></td>                    
                        <td><?=$c->indFinal?></td>                    
                        <td><?=$c->indPres?></td>                    
                        <td><?=$c->indIntermed?></td>                    
                        <td><?=$c->procEmi?></td>                    
                        <td><?=$c->verProc?></td>                     -->
                        <th>
                            <button 
                                class="btn btn-primary btn-sm produtos"
                                data-bs-toggle="offcanvas"
                                href="#offcanvasDireita"
                                role="button"
                                aria-controls="offcanvasDireita"
                                nota="<?=$d->codigo?>"
                            >
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                            <?php
                            if($d->situacao == '0'){
                            ?>
                            <button class="btn btn-danger btn-sm" excluir="<?=$d->codigo?>" nota="<?=$c->nNF?>" xml="<?=$d->xml?>"><i class="fa-solid fa-trash-can"></i></button>
                            <button class="btn btn-success btn-sm" incluir="<?=$d->codigo?>" nota="<?=$c->nNF?>"><i class="fa-solid fa-file-import"></i></button>
                            <?php
                            }else if($d->situacao == '1'){
                            ?>
                            <button class="btn btn-warning btn-sm" estoque="<?=$d->codigo?>" empresa="<?=$d->empresa?>" nota="<?=$c->nNF?>"><i class="fa-solid fa-dolly"></i></button>
                            <?php
                            }
                            ?>
                            
                        </th>

                    </tr>
            <?php
            }
            ?>
                </tbody>
            </table>            
        </div>
    </div>


</div>





<script>
    $(function(){
        Carregando('none');


        if (window.File && window.FileList && window.FileReader) {

        $('input[type="file"]').change(function () {
            if ($(this).val()) {
                var files = $(this).prop("files");
                for (var i = 0; i < files.length; i++) {
                    (function (file) {
                        var fileReader = new FileReader();
                        fileReader.onload = function (f) {

                            var Base64 = f.target.result;
                            var type = file.type;
                            var name = file.name;

                            $("#dadosXML").attr("base64", Base64);
                            $("#dadosXML").attr("tipo", type);
                            $("#dadosXML").attr("nome", name);

                        };
                        fileReader.readAsDataURL(file);
                    })(files[i]);
                }
            }
        });
        } else {
            alert('Nao suporta HTML5');
        }
        
        $(".incluir_nota").click(function(){

            base64 = $("#dadosXML").attr("base64");
            tipo = $("#dadosXML").attr("tipo");
            nome = $("#dadosXML").attr("nome");
            Carregando()
            $.ajax({
                url:"src/estoque/entrada/index.php",
                type:"POST",
                data:{
                    base64,
                    tipo,
                    nome,
                    acao:"upload_xml"
                },
                success:function(dados){
                    $("#paginaHome").html(dados);
                }
            })

        })


        $(".produtos").click(function(){

            nota = $(this).attr("nota");

            $.ajax({
                url:"src/estoque/entrada/produtos.php",
                type:"POST",
                data:{
                    nota
                },
                success:function(dados){
                    $(".LateralDireita").html(dados);
                }
            })

        })

        $("button[excluir]").click(function(){

            nota = $(this).attr("nota");
            excluir = $(this).attr("excluir");
            xml = $(this).attr("xml");

            $.confirm({
                type:"red",
                title:"Alerta de Exclusão",
                content:`Deseja realmente excluir a nota ${nota}?`,
                buttons:{
                    'Sim':{
                        text:'Sim',
                        btnClass:'btn btn-danger',
                        action:function(){
                            Carregando()
                            $.ajax({
                                url:"src/estoque/entrada/index.php",
                                type:"POST",
                                data:{
                                    excluir,
                                    xml,
                                    acao:'excluir'
                                },
                                success:function(dados){
                                    $("#paginaHome").html(dados);
                                }
                            })
                        }
                    },
                    'Não':{
                        text:'Não',
                        btnClass:'btn btn-success',
                        action:function(){
                            
                        }
                    }
                }
            })

        })


        $("button[incluir]").click(function(){

            nota = $(this).attr("nota");
            id = $(this).attr("incluir");

            $.confirm({
                type:"red",
                title:"Alerta de Operação",
                content:`Confirma a operação para entrada da nota ${nota}?`,
                buttons:{
                    'Sim':{
                        text:'Sim',
                        btnClass:'btn btn-danger',
                        action:function(){
                            Carregando()
                            $.ajax({
                                url:"nf/emissorNF.php",
                                type:"POST",
                                //typeData:"JSON",
                                data:{
                                    id
                                },
                                success:function(dados){

                                    if(dados == 'success'){
                                        $.ajax({
                                            url:"src/estoque/entrada/index.php",
                                            success:function(dados){
                                                $("#paginaHome").html(dados);
                                            }
                                        })
                                    }else{
                                        Carregando('none')
                                        $.alert(dados);
                                    }

                                }
                            })

                        }
                    },
                    'Não':{
                        text:'Não',
                        btnClass:'btn btn-success',
                        action:function(){
                            
                        }
                    }
                }
            })

        })


        $("button[estoque]").click(function(){

            nota = $(this).attr("nota");
            estoque = $(this).attr("estoque");
            empresa = $(this).attr("empresa");

            $.confirm({
                type:"red",
                title:"Alerta de Operação",
                content:`Confirma a inclusão dos produtos da nota ${nota} no estoque?`,
                buttons:{
                    'Sim':{
                        text:'Sim',
                        btnClass:'btn btn-danger',
                        action:function(){
                            Carregando()
                            $.ajax({
                                url:"src/estoque/entrada/index.php",
                                type:"POST",
                                data:{
                                    estoque,
                                    nota,
                                    empresa,
                                    acao:'estoque'
                                },
                                success:function(dados){
                                    $("#paginaHome").html(dados);
                                }
                            })
                        }
                    },
                    'Não':{
                        text:'Não',
                        btnClass:'btn btn-success',
                        action:function(){
                            
                        }
                    }
                }
            })

        })


    })
</script>