<?php

include("config.php");

// Seleciona os dados de configuração
$stmt = $PDO->prepare('SELECT * FROM configuracao WHERE id = ?');
$stmt->execute(1);
$rowconfig = $stmt->fetch(PDO::FETCH_ASSOC);
if($rowconfig["dados"]!=""){
    $settings = json_decode($rowconfig["dados"]);
}else{
    $settings = (object) array();
}

?>
<body style="margin: 25px;background: #FFF;">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="./jquery.mask.min.js"></script>
<section class="content">
    <div class="row">

        <div class="col-xs-12">
			<h3>Exportar XML das Notas Fiscais</h3>
            <div class="box box-primary">
        
                <div class="box-body mt-4">

                    <div class="row">
                          
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Mês de emissão</label>
                                    <select id="messelect" required="required" class="form-control">
									<option value="01" <? if(date("m")=="01") echo "selected='selected'";?>>Janeiro</option>
									<option value="02" <? if(date("m")=="02") echo "selected='selected'";?>>Fevereiro</option>
									<option value="03" <? if(date("m")=="03") echo "selected='selected'";?>>Março</option>
									<option value="04" <? if(date("m")=="04") echo "selected='selected'";?>>Abril</option>
									<option value="05" <? if(date("m")=="05") echo "selected='selected'";?>>Maio</option>
									<option value="06" <? if(date("m")=="06") echo "selected='selected'";?>>Junho</option>
									<option value="07" <? if(date("m")=="07") echo "selected='selected'";?>>Julho</option>
									<option value="08" <? if(date("m")=="08") echo "selected='selected'";?>>Agosto</option>
									<option value="09" <? if(date("m")=="09") echo "selected='selected'";?>>Setembro</option>
									<option value="10" <? if(date("m")=="10") echo "selected='selected'";?>>Outubro</option>
									<option value="11" <? if(date("m")=="11") echo "selected='selected'";?>>Novembro</option>
									<option value="12" <? if(date("m")=="12") echo "selected='selected'";?>>Dezembro</option>
                                    </select>
                                </div>
                            </div>

							<div class="col-6">
                                <div class="form-group">
                                    <label>Ano de emissão</label>
									<select id="anoselect" required="required" class="form-control">
										<?php for($x = 20; $x <= date("y"); $x++): ?>
										<option value="<?=$x;?>" <? if($x == date("y")) echo "selected='selected'";?>>20<?=$x;?></option>
										<?php endfor; ?>
									</select>
                               </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12" style="margin:20px 10px">
                                <button name='update' type="button" onclick="exportXML()" name="submit"  class="form-control btn btn-primary">Exportar</buttopn>
                                </div>
                            </div>

                            </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>

	function exportXML(){
		window.open("<?=$endpoint?>gerador/ExportarXML.php?codigoUF=<?= $settings->codigoUF ?>&cnpj=<? echo str_replace(array(".", ",", "-", " ", "/"), "", $settings->vat_no); ?>&modelo=65&mes=" + $("#messelect").val() + "&ano=" + $("#anoselect").val());
		return;
	}
</script>
                                           