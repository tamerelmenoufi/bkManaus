<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
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
                    <a href="#" class="btn btn-primary btn-sm">Incluir Nota</a>
                </div>
            </div>
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
        
    })
</script>