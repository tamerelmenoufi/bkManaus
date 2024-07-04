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
                    <p class="card-text">Selecione no campo abaixo a empresa de destino e anexe o arquivo XML da nota para iniciar o processo de entrada.</p>
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Selecione a empresa de destino</label>
                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formFileSm" class="form-label">Anexe o arquivo XML</label>
                        <input class="form-control form-control-sm" id="formFileSm" type="file">
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
        
    })
</script>