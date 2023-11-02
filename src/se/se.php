<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");


    if($_POST['acao'] == 'salvar'){


        $data = $_POST;
        $attr = [];

        unset($data['codigo']);
        unset($data['acao']);
        unset($data['data_nascimento']);
        unset($data['data']);
        // unset($data['monitor_social']);
        unset($data['percentual']);
        unset($data['situacao']);

        $CamposObrigatorios = [
            'nome',
            'cpf',
            'rg',
            'rg_orgao',
            'data_nascimento',
            'telefone',
            'municipio',
            'local',
            'bairro_comunidade',
            'endereco',
            'cep',
            'genero',
            'estado_civil',
            'redes_sociais',
            'meio_transporte',
            'tipo_imovel',
            'tipo_moradia',
            'quantidade_comodos',
            'grau_escolaridade',
            'curos_profissionais',
            'intereese_curso',
            'renda_mensal',
            'renda_familiar',
            'beneficio_social',
            'servico_saude',
            'condicoes_saude',
            'vacina_covid',
            'necessita_documentos',
            'avaliacao_beneficios',
            'atende_necessidades',
            'opiniao_saude',
            'opiniao_educacao',
            'opiniao_cidadania',
            'opiniao_infraestrutura',
            'opiniao_assistencia_social',
            'opiniao_direitos_humanos',
            'opiniao_seguranca',
            'opiniao_esporte_lazer'
        ];

        $tot = count($CamposObrigatorios);
        $qt = 0;
        $remov = ['[""]', 'null', '0', '0.00', ' ', 0, null];
        $log = false;
        $Lixo = false;
        foreach ($data as $name => $value) {
            if(is_array($value)) {
                $value = json_encode($value,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            }
            // $qt = (($value)?($qt+1):$qt);
            if(in_array($name, $CamposObrigatorios) and (trim(str_replace($remov, false,$value))) ){
            $qt = ($qt+1);
            $Lixo = $Lixo.$name."\n";
            }
            $attr[] = "{$name} = '" . mysqli_real_escape_string($con, $value) . "'";
        }
        if(dataMysql($_POST['data_nascimento'])){
            $qt = ($qt+1);
        }

            file_put_contents('lixo.txt', $Lixo);

            $pct = (100*$qt/$tot);
            $attr[] = "percentual = '" . $pct . "'";
            $attr[] = "data_nascimento = '" . dataMysql($_POST['data_nascimento']) . "'";
            if($_POST['data']) { $attr[] = "data = '" . dataMysql($_POST['data']) . "'"; }
            // $attr[] = "data = NOW()";
            // $attr[] = "monitor_social = '{$_SESSION['ProjectSeLogin']->codigo}'";
            $attr[] = "coordenador = '{$_SESSION['ProjectSeLogin']->coordenador}'";
            $attr[] = "acao = '0'";
            $attr[] = "acao_relatorio = '0'";

            if($_POST['beneficiario_encontrado'] == 'Não'){
                $situacao = "situacao = 'n'";
            }else if($_POST['beneficiario_encontrado'] == 'Sim' and $_POST['situacao'] == 'n'){
                $situacao = "situacao = 'p'";
            }else{
                $situacao = "situacao = '{$_POST['situacao']}'";
            }

            // if($_POST['situacao'] == 'n' and $_POST['beneficiario_encontrado'] == 'Não'){
            //     $attr[] = "beneficiario_encontrado = 'Não'";
            // }

            // if($_POST['situacao'] == 'f'){
            //     $situacao = "situacao = 'f'";
            // }

            if($situacao){
                $attr[] = $situacao;
            }

            // if($pct == 100){
            //     $attr[] = "pesquisa_realizada = 'Sim'";
            //     $attr[] = "situacao = 'c'";
            // }elseif($pct > 0){
            //     $attr[] = "pesquisa_realizada = 'Pendente'";
            //     $attr[] = "situacao = 'i'";
            // }elseif($_POST['beneficiario_encontrado'] == 'Não'){
            //     $attr[] = "pesquisa_realizada = ''";
            //     $attr[] = "situacao = 'n'";
            // }else{
            //     $attr[] = "pesquisa_realizada = ''";
            //     $attr[] = "situacao = 'p'";
            // }


        $attr = implode(', ', $attr);

        if($_POST['codigo']){
            $query = "update se set {$attr} where codigo = '{$_POST['codigo']}'";
            mysqli_query($con, $query);
            $cod = $_POST['codigo'];
            $sisLogo = sisLog(
                [
                    'query' => $query,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => $cod
                ]
            );
        }else{
            $query = "insert into se set data_cadastro = NOW(), {$attr}";
            mysqli_query($con, $query);
            $cod = mysqli_insert_id($con);
            $sisLogo = sisLog(
                [
                    'query' => $query,
                    'file' => $_SERVER["PHP_SELF"],
                    'sessao' => $_SESSION,
                    'registro' => $cod
                ]
            );
        }

        mysqli_query($con, "update municipios set acao = '0' where codigo = '{$data['municipio']}'");
        mysqli_query($con, "update bairros_comunidades set acao = '0' where codigo = '{$data['bairro_comunidade']}'");

        $retorno = [
            'status' => true,
            'codigo' => $cod,
            'mensagem' => "Pesquisa registrada com sucesso!",
            'query' => $sisLogo,
        ];

        echo json_encode($retorno);

        exit();
    }


    $query = "select * from se where codigo = '{$_POST['cod']}'";
    $result = mysqli_query($con, $query);
    $d = mysqli_fetch_object($result);
?>
<style>
    .Titulo<?=$md5?>{
        position:absolute;
        left:60px;
        top:8px;
        z-index:0;
    }
    textarea{
        height:200px !important;
    }
    .oculto{
        display:none;
    }
</style>
<h4 class="Titulo<?=$md5?>">Pesquisa Socioeconomico</h4>
    <form id="form-<?= $md5 ?>">
        <div class="row" style="margin-bottom:20px;">


            <div class="form-floating mb-3">
                <?=montaRadio([
                    'rotulo' => 'Beneficiário encontrado?',
                    'campo' => 'beneficiario_encontrado',
                    'vetor' => [
                        'Sim',
                        'Não',
                    ],
                    'dados' => $d->beneficiario_encontrado,
                    'exibir' => [
                        'Sim' => true,
                        'Não' => false,
                    ],
                    'campo_destino' => 'beneficiario_encontrado_campos'
                ])?>
            </div>


            <div class="oculto" beneficiario_encontrado_campos >
                <div class="col">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo" value="<?=$d->nome?>">
                        <label for="nome">Nome*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="cpf" id="cpf" class="form-control" placeholder="CPF" value="<?=$d->cpf?>">
                        <label for="cpf">CPF*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="rg" id="rg" class="form-control" placeholder="RG" value="<?=$d->rg?>">
                        <label for="rg">RG*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="rg_orgao" id="rg_orgao" class="form-control" placeholder="RG - Orgão Emissor" value="<?=$d->rg_orgao?>">
                        <label for="rg_orgao">RG (Orgão Emissor)*</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="data_nascimento" id="data_nascimento" class="form-control" placeholder="Ponto de Referência" value="<?=dataBr($d->data_nascimento)?>">
                        <label for="data_nascimento">Data de Nascimento</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="telefone" value="<?=preg_replace('/[^0-9]/', '', $d->telefone)?>">
                        <label for="telefone">Telefone*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" value="<?=$d->email?>">
                        <label for="email">E-mail*</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select name="municipio" id="municipio" class="form-control" >
                            <option value="">::Selecione o Município</option>
                            <?php
                                $q = "select * from municipios order by municipio asc";
                                $r = mysqli_query($con, $q);
                                while($s = mysqli_fetch_object($r)){
                            ?>
                            <option value="<?=$s->codigo?>" <?=(($d->municipio == $s->codigo)?'selected':false)?>><?=$s->municipio?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <label for="email">Município</label>
                    </div>


                    <div class="form-floating mb-3">
                        <select name="local" id="local" class="form-control" >
                            <option value="">::Selecione a Zona</option>
                            <option value="Urbano" <?=(($d->local == 'Urbano')?'selected':false)?>>Urbano</option>
                            <option value="Rural" <?=(($d->local == 'Rural')?'selected':false)?>>Rural</option>
                        </select>
                        <label for="email">Zona</label>
                    </div>


                    <div class="form-floating mb-3">
                        <select name="bairro_comunidade" id="bairro_comunidade" class="form-control" >
                            <option value="">::Selecione o Bairro/Comunidade</option>
                            <?php
                                $q = "select * from bairros_comunidades where municipio='{$d->municipio}' order by descricao asc";
                                $r = mysqli_query($con, $q);
                                while($s = mysqli_fetch_object($r)){
                            ?>
                            <option value="<?=$s->codigo?>" <?=(($d->bairro_comunidade == $s->codigo)?'selected':false)?>><?=$s->descricao.(($s->zona_urbana)?" ({$s->zona_urbana})":false)?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <label for="email">Bairro / Comunidade</label>
                    </div>



                    <div class="form-floating mb-3">
                        <input type="text" name="endereco" id="endereco" class="form-control" placeholder="Endereço" value="<?=$d->endereco?>">
                        <label for="endereco">Endereço</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="cep" id="cep" class="form-control" placeholder="CEP" value="<?=$d->cep?>">
                        <label for="cep">CEP</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="ponto_referencia" id="ponto_referencia" class="form-control" placeholder="Ponto de Referência" value="<?=$d->ponto_referencia?>">
                        <label for="ponto_referencia">Ponto de Referência</label>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Genero?',
                            'campo' => 'genero',
                            'vetor' => [
                                'Masculino',
                                'Feminino',
                            ],
                            'dados' => $d->genero,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Qual o seu Estado Civil?',
                            'campo' => 'estado_civil',
                            'vetor' => [
                                'Solteiro',
                                'Casado',
                                'Divorciado',
                                'Viúvo',
                                'Outros',
                            ],
                            'dados' => $d->estado_civil,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>


                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Quais Redes Sociais você possui?',
                            'campo' => 'redes_sociais',
                            'vetor' => [
                                'Não Possui',
                                'FaceBook',
                                'Twitter',
                                'Instagram',
                                'Youtube',
                                'Linkedin'
                            ],
                            'dados' => $d->redes_sociais,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Qual é o principal meio de transporte utilizado?',
                            'campo' => 'meio_transporte',
                            'vetor' => [
                                'A Pé',
                                'Bicicleta',
                                'Moto',
                                'Ônibus',
                                'Carro próprio',
                                'Fluvial',
                                'Outros'
                            ],
                            'dados' => $d->meio_transporte,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>


                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Você reside em Imóvel:',
                            'campo' => 'tipo_imovel',
                            'vetor' => [
                                'Própria',
                                'Emprestado/Cedido',
                                'Financiado',
                                'Invasão',
                                'Alugado',
                                'Outros',
                            ],
                            'dados' => $d->tipo_imovel,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Tipo de Moradia:',
                            'campo' => 'tipo_moradia',
                            'vetor' => [
                                'Madeira',
                                'Alvenaria',
                                'Palafita',
                                'Flutuante',
                                'Apartamento',
                                'Outros',
                            ],
                            'dados' => $d->tipo_moradia,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Quantos Cômodos possui sua residência?',
                            'campo' => 'quantidade_comodos',
                            'vetor' => [
                                '1 Cômodo',
                                '2 Cômodos',
                                '3 Cômodos',
                                '4 Cômodos',
                                'Mais de 4 Cômodos',
                            ],
                            'dados' => $d->quantidade_comodos,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Qual o seu Grau de escolaridade?',
                            'campo' => 'grau_escolaridade',
                            'vetor' => [
                                'Não Alfabetizado',
                                'Alfabetizado',
                                'Ensino Fundamental I Completo',
                                'Ensino Fundamental II Completo',
                                'Ensino Médio Completo',
                                'Ensino Técnico/Profissionalizante',
                                'Ensino Superior Completo',
                            ],
                            'dados' => $d->grau_escolaridade,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Você possui algum Curso Técnico/Profissionalizante?',
                            'campo' => 'curos_profissionais',
                            'vetor' => [
                                'Sim',
                                'Não',
                            ],
                            'dados' => $d->curos_profissionais,
                            'exibir' => [
                                'Sim'=>true,
                                'Não'=>false,
                            ],
                            'campo_destino' => 'curos_profissionais_descricao',
                        ])?>
                    </div>
                    <div class="oculto" curos_profissionais_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="curos_profissionais_descricao" id="curos_profissionais_descricao" class="form-control" placeholder="Cursos Técnico/Profissionalizante" ><?=$d->curos_profissionais_descricao?></textarea>
                            <label for="curos_profissionais_descricao">Descreve os Cursos Técnico/Profissionalizante</label>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Tem interesse em fazer algum curso?',
                            'campo' => 'intereese_curso',
                            'vetor' => [
                                'Sim',
                                'Não',
                            ],
                            'dados' => $d->intereese_curso,
                            'exibir' => [
                                'Sim'=>true,
                                'Não'=>false,
                            ],
                            'campo_destino' => 'interesse_curso_descricao',
                        ])?>
                    </div>
                    <div class="oculto" interesse_curso_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="interesse_curso_descricao" id="interesse_curso_descricao" class="form-control" placeholder="Descreve os Cursos de seu interesse" ><?=$d->interesse_curso_descricao?></textarea>
                            <label for="interesse_curso_descricao">Descreve os Cursos de seu interesse</label>
                        </div>
                    </div>


                    
                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Qual sua Renda Mensal?',
                            'campo' => 'renda_mensal',
                            'vetor' => [
                                'Nenhuma',
                                'abaixo de um salário mínimo',
                                '1 salário mínimo',
                                '2 salários mínimos',
                                '3 salários mínimos',
                                '4 salários mínimos',
                                'Acima de 4 Salários mínimos'
                            ],
                            'dados' => $d->renda_mensal,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Renda Familiar Mensal?',
                            'campo' => 'renda_familiar',
                            'vetor' => [
                                'Nenhuma',
                                'abaixo de um salário mínimo',
                                '1 salário mínimo',
                                '2 salários mínimos',
                                '3 salários mínimos',
                                '4 salários mínimos',
                                'Acima de 4 Salários mínimos'
                            ],
                            'dados' => $d->renda_familiar,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Você possui algum Benefício Social?',
                            'campo' => 'beneficio_social',
                            'vetor' => [
                                'Sim',
                                'Não',
                            ],
                            'dados' => $d->beneficio_social,
                            'exibir'=>[
                                'Sim'=>true,
                                'Não'=>false,
                            ],
                            'campo_destino'=>'beneficio_social_descricao',
                        ])?>
                    </div>
                    <div class="oculto" beneficio_social_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="beneficio_social_descricao" id="beneficio_social_descricao" class="form-control" placeholder="Descrição do Benefício Social" ><?=$d->beneficio_social_descricao?></textarea>
                            <label for="beneficio_social_descricao">Descrição do Benefício Social</label>
                        </div>
                    </div>


                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Qual Serviço de Saúde você utiliza?',
                            'campo' => 'servico_saude',
                            'vetor' => [
                                'SUS',
                                'Associações/PlanodeSaúde',
                                'Outros',
                            ],
                            'dados' => $d->servico_saude,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Condições de sua saúde?',
                            'campo' => 'condicoes_saude',
                            'vetor' => [
                                'Doenças Crônicas',
                                'Tratamentos Médicos',
                                'Outros',
                            ],
                            'dados' => $d->condicoes_saude,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>


                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Já tomou a vacina contra o Covid-19? Quantas doses já tomou?',
                            'campo' => 'vacina_covid',
                            'vetor' => [
                                'Não',
                                '1a Dose',
                                '2a Dose',
                                '3a Dose',
                                '4a Dose',
                                'Mais de 4 Doses',
                            ],
                            'dados' => $d->vacina_covid,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>



                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Necessita de Documentos',
                            'campo' => 'necessita_documentos',
                            'vetor' => [
                                'Não',
                                'RCN', // - Registro Civil de Nascimento
                                'RCC', // -
                                'RG', // - Registro Geral
                                'CPF', //- Cadastro de Pessoa Física
                                'CTPS', // - Carteira de Trabalho e Previdencia Social
                                'TE', //- Título Eleitoral
                            ],
                            'dados' => $d->necessita_documentos,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>




                        <!-- XXXXXXXXXXXXXXXXXXXXXXXX -->



                        <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Como você avalia o Beneficio?',
                            'campo' => 'avaliacao_beneficios',
                            'vetor' => [
                            'Ruim',
                                'Bom',
                                'Ótimo',
                            ],
                            'dados' => $d->avaliacao_beneficios,
                            'exibir' => [
                                'Ruim'=>true,
                                'Bom'=>true,
                                'Ótimo'=>true,
                            ],
                            'campo_destino'=>'avaliacao_beneficios_descricao'
                        ])?>
                    </div>
                    <div class='oculto' avaliacao_beneficios_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="avaliacao_beneficios_descricao" id="avaliacao_beneficios_descricao" class="form-control" placeholder="Descrição da Avaliação do Benefício" ><?=$d->avaliacao_beneficios_descricao?></textarea>
                            <label for="avaliacao_beneficios_descricao">Descrição de sua Avaliação do Benefício</label>
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'O beneficio tem atendido suas Necessidades?',
                            'campo' => 'atende_necessidades',
                            'vetor' => [
                            'Sim',
                                'Não',
                            ],
                            'dados' => $d->atende_necessidades,
                            'exibir' => [
                            'Sim'=>true,
                                'Não'=>true,
                            ],
                            'campo_destino'=>'atende_necessidades_descricao'
                        ])?>

                    </div>
                    <div class="oculto" atende_necessidades_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="atende_necessidades_descricao" id="atende_necessidades_descricao" class="form-control" placeholder="Descrição de Benefício que atende ou não as Necessidades" ><?=$d->atende_necessidades_descricao?></textarea>
                            <label for="atende_necessidades_descricao">Descrição de Benefício que atende ou não as Necessidades</label>
                        </div>
                    </div>
                    <!-- XXXXXXXXXXXXXXXXXXXXXXXX -->


                    <div class="form-floating mb-3">
                        <p><b>Na sua opinião dentro da estrutura do governo, em qual área abaixo descrita, necessita de melhorias para desenvolvimento na qualidade de vida da sua família e/ou da sua comunidade:</b></p>
                    </div>
                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Na Saúde?',
                            'campo' => 'opiniao_saude',
                            'vetor' => [
                                'Não Necessita',
                                'Acesso a Medicamentos',
                                'Marcação de Consultas',
                                'Realização de Exames',
                                'Realização de Procedimentos Médicos e/ou Cirurgia',
                                'Outros',
                            ],
                            'dados' => $d->opiniao_saude,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Acesso a Medicamentos'=>true,
                                'Marcação de Consultas'=>true,
                                'Realização de Exames'=>true,
                                'Realização de Procedimentos Médicos e/ou Cirurgia'=>true,
                            ],
                            'campo_destino'=>'opiniao_saude_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_saude_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_saude_descricao" id="opiniao_saude_descricao" class="form-control" placeholder="Outras Opiniões / Detalhes" ><?=$d->opiniao_saude_descricao?></textarea>
                            <label for="opiniao_saude_descricao">Desreva suas Opiniões Falhas na Saúde</label>
                        </div>
                    </div>

                    <?php
                        //*
                    ?>
                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Na Educação?',
                            'campo' => 'opiniao_educacao',
                            'vetor' => [
                                'Não Necessita',
                                'Sim Necessita'
                            ],
                            'dados' => $d->opiniao_educacao,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Sim Necessita'=>true
                            ],
                            'campo_destino'=>'opiniao_educacao_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_educacao_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_educacao_descricao" id="opiniao_educacao_descricao" class="form-control" placeholder="Outras Opiniões falhas na Educação" ><?=$d->opiniao_educacao_descricao?></textarea>
                            <label for="opiniao_educacao_descricao">Desreva suas Opiniões falhas na Educação</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Na Cidadania?',
                            'campo' => 'opiniao_cidadania',
                            'vetor' => [
                                'Não Necessita',
                                'Sim Necessita'
                            ],
                            'dados' => $d->opiniao_cidadania,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Sim Necessita'=>true
                            ],
                            'campo_destino'=>'opiniao_cidadania_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_cidadania_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_cidadania_descricao" id="opiniao_cidadania_descricao" class="form-control" placeholder="Outras Opiniões falhas na Cidadania" ><?=$d->opiniao_cidadania_descricao?></textarea>
                            <label for="opiniao_cidadania_descricao">Desreva suas Opiniões falhas na Cidadania</label>
                        </div>
                    </div>
                    <?php
                        //*/
                    ?>


                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Na Infraestrutura?',
                            'campo' => 'opiniao_infraestrutura',
                            'vetor' => [
                                'Não Necessita',
                                'Má Iluminação',
                                'Ausência de Asfalto e/ou Precariedade',
                                'Falta de Saneamento básico e/ou Melhoria',
                                'Abastecimento de Água',
                                'Abastecimento de Energia',
                                'Outros',
                            ],
                            'dados' => $d->opiniao_infraestrutura,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Má Iluminação'=>true,
                                'Ausência de Asfalto e/ou Precariedade'=>true,
                                'Falta de Saneamento básico e/ou Melhoria'=>true,
                                'Abastecimento de Água'=>true,
                                'Abastecimento de Energia'=>true,
                            ],
                            'campo_destino'=>'opiniao_infraestrutura_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_infraestrutura_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_infraestrutura_descricao" id="opiniao_infraestrutura_descricao" class="form-control" placeholder="Outras Opiniões falhas na Infraestrutura" ><?=$d->opiniao_infraestrutura_descricao?></textarea>
                            <label for="opiniao_infraestrutura_descricao">Desreva suas Opiniões falhas na Infraestrutura</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Na Assistência Social?',
                            'campo' => 'opiniao_assistencia_social',
                            'vetor' => [
                                'Não Necessita',
                                'Alimentação Básica',
                                'Auxílios Governamentais',
                                'Assistência Psicológica',
                                'Outros',
                            ],
                            'dados' => $d->opiniao_assistencia_social,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Alimentação Básica'=>true,
                                'Auxílios Governamentais'=>true,
                                'Assistência Psicológica'=>true,
                            ],
                            'campo_destino'=>'opiniao_assistencia_social_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_assistencia_social_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_assistencia_social_descricao" id="opiniao_assistencia_social_descricao" class="form-control" placeholder="Outras Opiniões falhas na Assistência Social" ><?=$d->opiniao_assistencia_social_descricao?></textarea>
                            <label for="opiniao_assistencia_social_descricao">Desreva suas Opiniões falhas na Assistência Social</label>
                        </div>
                    </div>

                    <?php
                    //*
                    ?>
                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Nos Direitos Humanos?',
                            'campo' => 'opiniao_direitos_humanos',
                            'vetor' => [
                                'Não Necessita',
                                'Sim Necessita'
                            ],
                            'dados' => $d->opiniao_direitos_humanos,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Sim Necessita'=>true
                            ],
                            'campo_destino'=>'opiniao_direitos_humanos_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_direitos_humanos_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_direitos_humanos_descricao" id="opiniao_direitos_humanos_descricao" class="form-control" placeholder="Outras Opiniões falhas na Direitos Humanos" ><?=$d->opiniao_direitos_humanos_descricao?></textarea>
                            <label for="opiniao_direitos_humanos_descricao">Desreva suas Opiniões falhas na Direitos Humanos</label>
                        </div>
                    </div>
                    <?php
                    //*/
                    ?>
                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'Na Segurança?',
                            'campo' => 'opiniao_seguranca',
                            'vetor' => [
                                'Não Necessita',
                                'Policiamento Ostensivo',
                                'Outros',
                            ],
                            'dados' => $d->opiniao_seguranca,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Policiamento Ostensivo'=>true,
                            ],
                            'campo_destino'=>'opiniao_seguranca_descricao'
                        ])?>
                    </div>
                    <div class="oculto" opiniao_seguranca_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_seguranca_descricao" id="opiniao_seguranca_descricao" class="form-control" placeholder="Outras Opiniões falhas na Segurança" ><?=$d->opiniao_seguranca_descricao?></textarea>
                            <label for="opiniao_seguranca_descricao">Desreva suas Opiniões falhas na Segurança</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <?=montaCheckbox([
                            'rotulo' => 'No Esporte e Lazer?',
                            'campo' => 'opiniao_esporte_lazer',
                            'vetor' => [
                                'Não Necessita',
                                'Areas para pratica de atividades esportivas',
                                'Outros',
                            ],
                            'dados' => $d->opiniao_esporte_lazer,
                            'exibir' => [
                                'Não Necessita'=>false,
                                'Areas para pratica de atividades esportivas'=>true,
                            ],
                            'campo_destino'=>'opiniao_esporte_lazer_descricao'

                        ])?>
                    </div>
                    <div class="oculto" opiniao_esporte_lazer_descricao>
                        <div class="form-floating mb-3">
                            <textarea name="opiniao_esporte_lazer_descricao" id="opiniao_esporte_lazer_descricao" class="form-control" placeholder="Outras Opiniões falhas no Esporte e Lazer" ><?=$d->opiniao_esporte_lazer_descricao?></textarea>
                            <label for="opiniao_esporte_lazer_descricao">Outras Opiniões falhas no Esporte e Lazer</label>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="opiniao_outros" id="opiniao_outros" class="form-control" placeholder="Outras Opiniões / Detalhes" ><?=$d->opiniao_outros?></textarea>
                        <label for="opiniao_outros">Outras Opiniões / Detalhes (na estrutura do governo)</label>
                    </div>

                    

                </div>
            </div>
        </div>

        <div style="padding-bottom:50px;">

            <div class="card border-warning mb-3">
                <h5 class="card-header">Avaliação do Técnico</h5>
                <div class="card-body">

                    <?php
                    if($_SESSION['ProjectSeLogin']->perfil == 'adm'){
                    ?>
                    <div class="form-floating mb-3">
                        <input type="text" name="data" required id="data" class="form-control" placeholder="Data da Pesquisa" value="<?=dataBr($d->data)?>">
                        <label for="data">Data da Pesquisa*</label>
                    </div>
                    <?php
                    }

                    if($_SESSION['ProjectSeLogin']->perfil != 'usr'){
                    ?>
                    <div class="form-floating mb-3">
                        <select name="monitor_social" required id="monitor_social" class="form-control" >
                            <option value="">::Selecione o Profissional</option>
                            <?php
                                $q = "select * from usuarios where situacao = '1' and perfil = 'usr' order by nome asc";
                                $r = mysqli_query($con, $q);
                                while($s = mysqli_fetch_object($r)){
                            ?>
                            <option value="<?=$s->codigo?>" <?=(($d->monitor_social == $s->codigo)?'selected':false)?>><?=$s->nome?></option>
                            <?php
                                }
                            ?>
                        </select>
                        <label for="monitor_social">Profissionais</label>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="form-floating mb-3">
                        <?=montaRadio([
                            'rotulo' => 'Situação da Pesquisa',
                            'campo' => 'recepcao_entrevistado',
                            'vetor' => [
                                'Ruim',
                                'Bom',
                                'Ótimo',
                            ],
                            'dados' => $d->recepcao_entrevistado,
                            'exibir' => false,
                            'campo_destino' => false
                        ])?>
                    </div>

                    <div class="form-floating mb-3">
                        <select name="situacao" required id="situacao" class="form-control" >
                            <option value="n" <?=(($d->situacao == 'n')?'selected':false)?>>Não Encontrado</option>
                            <option value="i" <?=(($d->situacao == 'i')?'selected':false)?>>Iniciada</option>
                            <option value="p" <?=(($d->situacao == 'p')?'selected':false)?>>Pendente</option>
                            <option value="c" <?=(($d->situacao == 'c')?'selected':false)?>>Concluida</option>
                            <?php
                            if($_SESSION['ProjectSeLogin']->perfil != 'usr'){
                            ?>
                            <option value="f" <?=(($d->situacao == 'f')?'selected':false)?>>Finalizada</option>
                            <?php
                            }
                            ?>
                        </select>
                        <label for="situacao">Situação</label>
                    </div>

                </div>
            </div>

            <div obs class="form-floating mb-3">
                <textarea name="observacoes" id="observacoes" class="form-control" placeholder="Observações" ><?=$d->observacoes?></textarea>
                <label for="observacoes">Observações</label>
            </div>
        </div>
        <div style="position:absolute; bottom:0;left:0; right:20px; height:50px; padding-right:10px; background-color:#fff;">
            <div style="display:flex; justify-content:end">
                <?php
                if($_POST['origem']){
                ?>
                <button
                    type="button"
                    voltar
                    class="btn btn-danger btn-ms"
                    style="margin-right:10px;"
                >Voltar</button>
                <?php
                }
                ?>
                <button
                    type="submit"
                    SalvarFoto
                    class="btn btn-success btn-ms"
                >Salvar</button>
                <input type="hidden" id="codigo" value="<?=$_POST['cod']?>" />
            </div>
        </div>

    </form>

    <script>
        $(function(){

            Carregando('none');

            $("#cpf").mask('999.999.999-99');
            $("#telefone").mask('(99) 99999-9999');
            $("#cep").mask('99999-999');
            $("#data_nascimento").mask('99/99/9999');
            $("#data").mask('99/99/9999');


            var filtro = (municipio, tipo) => {
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

            $("#local").change(function(){
                municipio = $("#municipio").val();
                local = $(this).val();
                filtro(municipio, local);
            });

            $("#municipio").change(function(){
                local = $("#local").val();
                municipio = $(this).val();
                filtro(municipio, local);
            });


            <?php
            if($d->situacao == 'f'){
            ?>
            $("#form-<?= $md5 ?> input, #form-<?= $md5 ?> select, #form-<?= $md5 ?> textarea, #form-<?= $md5 ?> button").attr("disabled","disabled");
            <?php
            }
            ?>



            $('#form-<?=$md5?>').submit(function (e) {

                e.preventDefault();

                // $(".oculto").remove();

                codigo = $('#codigo').val();
                campos = $(this).serializeArray();

                if (codigo) {
                    campos.push({name: 'codigo', value: codigo})
                }

                campos.push({name: 'acao', value: 'salvar'})

                if($("#beneficiario_encontrado0").prop("checked")){
                    console.log($("#cpf").val());
                    if(!validarCPF($("#cpf").val())){
                        $.alert('Confira o CPF, o informado é inválido!');
                        return;
                    }
                }

                Carregando();

                ////COORDENADAS
                local = $(`#endereco`).val() + ',' +
                        $(`#bairro_comunidade`).children(`option[value="${$(`#bairro_comunidade`).val()}"]`).text() + ', ' +
                        $(`#municipio`).children(`option[value="${$(`#municipio`).val()}"]`).html();

                address =  `${local}, Amazonas, Brasil`
                // console.log(address)
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'address':address, 'region': 'BR' }, (results, status) => {
                    // console.log(google.maps.GeocoderStatus.OK)
                    if (status == google.maps.GeocoderStatus.OK) {
                        var latitude = results[0].geometry.location.lat();
                        var longitude = results[0].geometry.location.lng();
                        coordenadas = `${latitude},${longitude}`;
                        campos.push({name: 'coordenadas', value: coordenadas})
                        // console.log(campos)

                        $.ajax({
                            url:"src/se/se.php",
                            type:"POST",
                            dataType:"JSON",
                            data: campos,
                            success:function(dados){

                                console.log(dados)
                                $.alert('Dados atualizados com sucesso!');

                                <?php
                                if(!$_POST['origem']){
                                ?>
                                let myOffCanvas = document.getElementById('offcanvasDireita');
                                let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                                openedCanvas.hide();
                                
                                <?php
                                }
                                ?>

                                $.ajax({
                                    url:"<?=(($_POST['origem'])?"src/relatorios/telas/lista_beneficiados.php":"src/se/index.php")?>",
                                    success:function(dados){
                                        <?php
                                        if(!$_POST['origem']){
                                        ?>
                                        $("#paginaHome").html(dados);
                                        <?php
                                        }else{
                                        ?>
                                        $(".popUpBeneficiados div").html('');
                                        $(".popUpBeneficiados h3").html('');
                                        $(".popUpBeneficiados").css("display","none");

                                        $(".popUpBeneficiados div").html(dados);
                                        $(".popUpBeneficiados h3").html('<?=$_SESSION['s_titulo']?>');
                                        $(".popUpBeneficiados").css("display","block");

                                        <?php
                                        }
                                        ?>

                                    },
                                    error:function(erro){
                                        $.alert('Ocorreu um erro!' + erro.toString());
                                        //dados de teste
                                    }
                                });


                            },
                            error:function(erro){

                                $.alert('Ocorreu um erro!' + erro.toString());
                                //dados de teste
                            }
                        });

                    }
                });
                ////COORDENADAS




                // function sleep(milliSeconds) {
                //     var startTime = new Date().getTime();
                //     while (new Date().getTime() < startTime + milliSeconds);
                // }

                // sleep(1000);


                // console.log(campos)



                // console.log(coordenadas)

            });


            $("input[exibir]").parent("div").parent("div").children("div").children("input[exibir]").each(function(){
                if($(this).prop("checked") == true){
                    $(`div[${$(this).attr('exibir')}]`).removeClass("oculto");
                }
            })

            $("input[exibir]").click(function(){

                if($(this).attr("type") == 'checkbox' && $(this).prop("checked") == true){

                    $(this).parent("div").parent("div").children("div").children("input[ocultar]").each(function(){
                        $(this).prop("checked",false);
                    })
                }

                if($(this).attr('exibir')){
                    if($(this).prop("checked") == true){
                        // $(`div[${$(this).attr('exibir')}]`).css("display","block");
                        $(`div[${$(this).attr('exibir')}]`).removeClass("oculto");
                    }
                }

                obj = $(`div[${$(this).attr('exibir')}]`);
                // obj.css("display","none");
                obj.addClass("oculto");

                $(this).parent("div").parent("div").children("div").children("input[exibir]").each(function(){
                    if($(this).prop("checked") == true){
                        // obj.css("display","block");
                        obj.removeClass("oculto");
                    }
                })

            })

            $("input[ocultar]").click(function(){

                if($(this).attr("type") == 'checkbox' && $(this).prop("checked") == true){
                    $(this).parent("div").parent("div").children("div").children("input[exibir]").each(function(){
                        $(this).prop("checked",false);
                    })
                }

                if($(this).prop("checked") == true){
                    // $(`div[${$(this).attr('ocultar')}]`).css("display","none");
                    if($(this).attr('ocultar')){
                        $(`div[${$(this).attr('ocultar')}]`).addClass("oculto");
                    }
                }

            })



            <?php
                if($_POST['origem']){
            ?>
            $("button[voltar]").click(function(){
                // Carregando()
                // $.ajax({
                //     url:"src/relatorios/telas/lista_beneficiados.php",
                //     success:function(dados){
                //         $(".LateralDireita").html(dados);
                //     },
                //     error:function(erro){
                //         $.alert('Ocorreu um erro!' + erro.toString());
                //     }
                // });
                let myOffCanvas = document.getElementById('offcanvasDireita');
                let openedCanvas = bootstrap.Offcanvas.getInstance(myOffCanvas);
                openedCanvas.hide();
            })
            <?php
                }
            ?>

        })
    </script>