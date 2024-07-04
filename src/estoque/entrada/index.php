<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");

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

        }



    }

?>
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
                        <th>Impressão</th>
                        <th>Emissão</th>
                        <th>Dígito</th>
                        <th>Ambiente</th>
                        <th>Finalidade</th>
                        <th>Consumidor</th>
                        <th>Presencial</th>
                        <th>Intermediador</th>
                        <th>Aplicativo</th>
                        <th>Versão</th>
                    </tr>
                </thead>
                <tbody>
            <?php
            $query = "select * from notas";
            $result = mysqli_query($con, $query);
            while($d = mysqli_fetch_object($result)){
                $n = json_decode($d->dados);

                // "cUF": "35",
                // "cNF": "09257673",
                // "natOp": "Venda de mercadoria adquirida ou recebida de terceiros, dest",
                // "mod": "55",
                // "serie": "1",
                // "nNF": "251139",
                // "dhEmi": "2024-07-01T18:39:17-03:00",
                // "dhSaiEnt": "2024-07-01T18:39:17-03:00",
                // "tpNF": "1",
                // "idDest": "2",
                // "cMunFG": "3525904",
                // "tpImp": "1",
                // "tpEmis": "1",
                // "cDV": "8",
                // "tpAmb": "1",
                // "finNFe": "1",
                // "indFinal": "0",
                // "indPres": "9",
                // "indIntermed": "0",
                // "procEmi": "0",
                // "verProc": "GSNFE_4-4.0"
            ?>
                    <tr>
            <?php
                foreach($n->NFe->infNFe->ide as $i => $val){
            ?>
                        <th><?=$val?></th>
            <?php
                }
            ?>                        
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

    })
</script>