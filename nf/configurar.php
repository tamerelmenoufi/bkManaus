<?php

include("config.php");

if(!empty($_POST["vat_no"])){

    $post = $_POST;
    unset($post["submit"]);

    // upload certificado
    if(!empty($_FILES['certificadofile']['name'])){

        $uploaddir = __DIR__.'/../api-nfe/certificado_digital/';
        if ( ! is_writeable ( $uploaddir ) )
        {
            echo 'Can\'t write to directory, insufficient permissions.';
            die;
        }

        $filename = $_FILES['certificadofile']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filenewname = md5(sha1("certificadofile")).".".$ext;
        $uploadfile = $uploaddir . $filenewname;
        $allowed = array('pfx', 'p12');
        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES['certificadofile']['tmp_name'], $uploadfile)) {
                $post["certificado"] = $filenewname;
            }
        }else{
            $err[] = "Formato do certificado digital inválido, use apenas arquivo: '.pfx' e .'p12'. Não foi possível alterar o certificado.";
        }

    }

    // senha
    if($post["certificadosenha"]!=""){
        $post["certificado_senha"] = $post["certificadosenha"];
    }

    // upload logo
    if(!empty($_FILES['logonota']['name'])){

        $uploaddir = '../api-nfe/logo/';
        $filename = $_FILES['logonota']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filenewname = md5(sha1("logonota")).".".$ext;
        $uploadfile = $uploaddir . $filenewname;
        $allowed = array('jpeg', 'jpg');
        if (in_array($ext, $allowed)) {
            if (move_uploaded_file($_FILES['logonota']['tmp_name'], $uploadfile)) {
                $post["logo"] = $filenewname;
            }
        }else{
            $err[] = "Formato do logo inválido, use apenas arquivo: '.jpg' e .'jpeg'. Não foi possível alterar o logo.";
        }

    }

    // atualizamos
    unset($post["certificadosenha"]);
    unset($post["update"]);
    $consulta = $PDO->query("UPDATE tblNFconfiguracoes SET dados = '". json_encode($post) ."' ");
    if( $consulta === false ) { 
        die("Erro ao atualizar os dados"); 
    }else{
        $sucess = "Dados atualizados";
    }
}


// Seleciona os dados
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
        <?php if(!empty($sucess)){ ?>
        <div class="col-xs-12" style="background: #4caf50; color: #FFF; width: 100%; padding: 10px; margin: 10px 0px;">
            <?=$sucess;?>
        </div>
        <?php } ?>
        <?php if(!empty($err)){ ?>
        <div class="col-xs-12"  style="background: red; color: #FFF; width: 100%; padding: 10px; margin: 10px 0px;">
            <?php foreach($err as $e){
                echo $e."<br>";
            }?>
        </div>
        <?php } ?>

        <div class="col-xs-12">

            <div class="box box-primary">
               <form id="settings" action="./configurar.php" method="POST" enctype="multipart/form-data"  class="validation" autocomplete="nope">
                <div class="box-body">

                    <div class="row">
                           <div class="col-md-4">
                                <div class="form-group">
                                   <label for="vat_no">CNPJ</label>
                                    <input name='vat_no' value='<?=$settings->vat_no;?>' required="required" class="form-control  tip" id="vat_no">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Inscrição Estadual</label>
                                    <input name='ie' value='<?=$settings->ie;?>'   required="required" class="form-control  tip" id="ie" >
                                </div>
                            </div>
                                                
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Inscrição Municipal</label>
                                    <input name='im' value='<?=$settings->im;?>'  class="form-control  tip" id="im" >
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Razão Social</label>
                                    <input name='razaosocial'  required="required"  value='<?=$settings->razaosocial;?>'  class="form-control  tip" id="razaosocial" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nome Fantasia</label>
                                    <input name='fantasia' value='<?=$settings->fantasia;?>' required="required" class="form-control  tip" id="fantasia" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNAE</label>
                                    <input name='cnae' value='<?=$settings->cnae;?>'  class="form-control  tip" id="cnae">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone/Celular</label>
                                    <input name='phone_number' value='<?=$settings->phone_number;?>'  class="form-control  phone_or_movil_with_ddd" required="required" id="phone_number"  >
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>
    
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input name='address' value='<?=$settings->address;?>' required="required" class="form-control  tip" id="address" >
                                </div>
                            </div>
                            
                           <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número</label>
                                    <input name='numero' value='<?=$settings->numero;?>' required="required" class="form-control  tip" id="numero" >
                                </div>
                            </div>
                          
							<div class="col-md-4">
                                <div class="form-group">
                                    <label>Bairro</label>
                                    <input name='bairro' value='<?=$settings->bairro;?>' required="required" class="form-control  tip" id="bairro">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name='estado' required="required" class="form-control"> 
                                    <?php
                                     $bs = array('' => 'Selecione...', 'AC' => 'AC', 'AL' => 'AL', 'AM' => 'AM', 'AP' => 'AP', 'BA' => 'BA', 'CE' => 'CE', 'DF' => 'DF', 'ES' => 'ES', 'GO' => 'GO', 'MA' => 'MA', 'MG' => 'MG', 'MS' => 'MS', 'MT' => 'MT', 'PA' => 'PA', 'PB' => 'PB', 'PE' => 'PE', 'PI' => 'PI', 'PR' => 'PR', 'RJ' => 'RJ', 'RN' => 'RN', 'RO' => 'RO', 'RR' => 'RR', 'RS' => 'RS', 'SC' => 'SC', 'SE' => 'SE', 'SP' => 'SP', 'TO' => 'TO');
                                     foreach($bs as $k => $v){ 
                                        $selected = ($settings->estado==$k) ? "selected='selected'" : "";
                                        echo "<option $selected value='$k'>$v</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input name='nomeMunicipio' class="form-control  tip"  required="required" value="<?php echo $settings->nomeMunicipio; ?>" id="nomeMunicipio">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Código da Cidade IBGE</label>
                                    <input name='ccidade' class="form-control  tip"  required="required" value="<?php echo $settings->ccidade; ?>" id="ccidade">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEP</label>
                                    <input name='postal_code' value='<?=$settings->postal_code;?>' required="required" class="form-control  tip placeholder" id="postal_code"  >
                                </div>
                            </div>
                        
                            <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ambiente de emissão</label>
                                    <select name="tpAmb" required="required" class="form-control">
                                    <?php
                                    $bs = array( '2' => 'Homologação (Testes)', '1' => 'Produção (Real)');
                                    foreach($bs as $k => $v){ 
                                        $selected = ($settings->tpAmb==$k) ? "selected='selected'" : "";
                                        echo "<option $selected value='$k'>$v</option>";
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            
                           
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Código CSC ID</label>
                                    <input name='CSCid' required="required" value='<?=$settings->CSCid;?>'  class="form-control  tip cscid" id="CSCid" >
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Código CSC</label> 
                                    <input name='CSC' required="required" value='<?=$settings->CSC;?>'  class="form-control  tip" id="CSC">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Token IBPT (Cálculo de impostos) <a href="https://deolhonoimposto.ibpt.org.br/Site/PassoPasso" target="_blank">Como gerar?</a></label>
                                    <input name='tokenIBPT' value='<?=$settings->tokenIBPT;?>'  class="form-control  tip" id="tokenIBPT" >
                                </div>
                            </div>

                            <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">Número da próxima NFC-e</label>
                                    <div class="controls">
                                    <input name='proxima_nfc' required="required" value='<?=$settings->proxima_nfc;?>'  class="form-control  tip justnum" required="required" id="proxima_nfc" >
                                </div>
                                </div>
                            </div>
                            
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Série da NFC-e</label>
                                      <div class="controls">
                                        <input name='serie_nfc' required="required" value='<?=$settings->serie_nfc;?>'  class="form-control  tip justnum" required="required" id="serie_nfc" >
                                    </div>
                                </div>
                              </div>

                            <div class="col-md-7">
                                <div class="form-group">
                                <label>Certificado Digital (.pfx, .p12)</label> <?php echo ($settings->certificado=="")? " (<b style='color:red'>Certificado não enviado</b>)" : " (<b style='color:green'>Certificado enviado</b>)";  ?> 
                                    <input type="file" name="certificadofile" class="form-control" id="certificadofile">
                                    <input type="hidden" name="certificado" value="<?=$settings->certificado;?>">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Senha Certificado Digital</label><?php echo ($settings->certificado_senha=="")? " (<b style='color:red'>Senha não enviada</b>)" : " (<b style='color:green'>Senha enviada</b>)"; ?>
                                    <input name='certificadosenha' class="form-control  tip" id="certificadosenha" autocomplete="new-password" type="password" > <a href="javascript:void(0)" onclick="$('#certificadosenha').attr('type', 'text')">Mostrar</a> / <a href="javascript:void(0)" onclick="$('#certificadosenha').attr('type', 'password');">Ocultar</a>
                                    <input type="hidden" name="certificado_senha" value="<?=$settings->certificado_senha;?>">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                <label>Logo na Nota Fiscal</label><?php echo " (formato: .jpg, .jpeg)"; echo ($settings->logo=="")? " (<b style='color:orange'>Logo não enviado</b>)" : " (<a href='../logo/".$settings->logo."' target='_blank' style='color:green'>Ver logo</a>)"; ?> 
                                    <input type="file" name="logonota" id="logo" style="width:300px;">
                                    <input type="hidden" name="logo" value="<?=$settings->logo;?>">
                                </div>
                            </div>

                              <div class="col-md-12">
                                <div class="form-group">
                                   <hr>
                                </div>
                              </div>

                            </div> 
                            
                            <div class="row">
                                <div class="col-lg-12" style="margin:20px 10px">
                                <button name='update' type="submit" name="submit"  class="form-control btn btn-primary">Guardar</buttopn>
                                </div>
                            </div>

                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
$(document).ready(function(){

    var behavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    options = {
        onKeyPress: function (val, e, field, options) {
            field.mask(behavior.apply({}, arguments), options);
        }
    };

    $('.cscid').mask('000000', {placeholder: '6 dígitos. Ex: 000001'});
    $('.justnum').mask('0000000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.mobphone_with_ddd').mask('(00) 00000-0000', {placeholder: '(00) 00000-0000'});
    $(".phone_or_movil_with_ddd").mask(behavior, options);
    $('.cpf').mask('000.000.000-00', {reverse: true, placeholder:'000.000.000-00'});
    $('#vat_no').mask('00.000.000/0000-00', {reverse: true, placeholder:'00.000.000/0000-00'});
    $('.money').mask('000.000.000.000.000,00', {reverse: true});
    $('.money2').mask("#.##0,00", {reverse: true});
    $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', { translation: { 'Z': { pattern: /[0-9]/, optional: true } } });
    $('.ip_address').mask('099.099.099.099');
    $('.percent').mask('##0,00%', {reverse: true});
    $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
    $('.placeholder').mask("00000-000", {placeholder: "_____-___"});
    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
});

$(document).ready(function() {
    $('#certificadosenha').attr('type','password');
});
</script>
</body>
                                           