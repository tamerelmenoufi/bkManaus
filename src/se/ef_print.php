<?php
    include("{$_SERVER['DOCUMENT_ROOT']}/app/projectSocioEconomico/lib/includes.php");

?>
<style>
    body{
        font-size:11px;
    }
    div[textarea]{
        height:50px !important;
    }
    .form-control-sm{
        height:35px !important;
    }
    .form-floating label{
        font-size:9px !important;
    }
</style>

<?php
for($i=0;$i<2;$i++){
?>

<div class="container">
        <div class="row">

            <div class="col">
                <div class="form-floating mb-3">
                    <div type="text" class="form-control form-control-sm" id="ef_nome" name="ef_nome" placeholder="Nome completo" value="<?=$d->ef_nome?>"></div>
                    <label for="ef_nome">Nome</label>
                </div>

                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'Qual o grau de parentesco?',
                        'campo' => 'ef_grau_parentesco',
                        'vetor' => [
                            'Mãe',
                            'Pai',
                            'Esposo',
                            'Esposa',
                            'Filho',
                            'Filha',
                            'Entiado',
                            'Entiada',
                            'Neto',
                            'Neta',
                            'Tia',
                            'Tio',
                            'Sobrinho',
                            'Sobrinha',
                            'Primo',
                            'Prima',
                            'Avó',
                            'Avô',
                            'Outros',
                        ],
                        'dados' => $d->ef_grau_parentesco
                    ])?>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <div type="text" name="ef_data_nascimento" id="ef_data_nascimento" class="form-control form-control-sm" placeholder="Data de Nascimento" value="<?=dataBr($d->ef_data_nascimento)?>"></div>
                            <label for="ef_data_nascimento">Data de Nascimento</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating mb-3">
                            <div type="text" name="ef_telefone" id="ef_telefone" class="form-control form-control-sm" placeholder="Telefone" value="<?=$d->ef_telefone?>"></div>
                            <label for="ef_telefone">Telefone*</label>
                        </div>
                    </div>
                </div>

<div class="row">
    <div class="col-6">
                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'Qual a renda mensal?',
                        'campo' => 'ef_renda_mensal',
                        'vetor' => [
                            'Nenhuma',
                            '1 salário mínimo',
                            '2 salários mínimos',
                            '3 salários mínimos',
                            '4 salários mínimos',
                            'Acima de 4 Salários mínimos',
                        ],
                        'dados' => $d->ef_renda_mensal
                    ])?>
                </div>
                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'Precisa de tratamento de saúde?',
                        'campo' => 'ef_tratamento_saude',
                        'vetor' => [
                            'Não',
                            'Sim',
                        ],
                        'dados' => $d->ef_tratamento_saude
                    ])?>
                </div>
                <div class="form-floating mb-3">
                    <div textarea type="text" name="ef_tratamento_saude_descricao" id="ef_tratamento_saude_descricao" class="form-control form-control-sm" placeholder="Descreva o Tratamento de Saúde" value="<?=$d->ef_tratamento_saude_descricao?>"></div>
                    <label for="ef_tratamento_saude_descricao">Descreva o Tratamento de Saúde</label>
                </div>



                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'Possui alguma doença crônica?',
                        'campo' => 'ef_doencas_cronicas	',
                        'vetor' => [
                            'Não',
                            'Sim',
                        ],
                        'dados' => $d->ef_doencas_cronicas
                    ])?>
                </div>
                <div class="form-floating mb-3">
                    <div textarea type="text" name="ef_doencas_cronicas_descricao" id="ef_doencas_cronicas_descricao" class="form-control form-control-sm" placeholder="Descreva o Tratamento de Saúde" value="<?=$d->ef_doencas_cronicas_descricao?>"></div>
                    <label for="ef_doencas_cronicas_descricao">Descreva a(s) Doença(s) Crônica(s)</label>
                </div>
</div>
<div class="col-6">
                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'É portador de alguma deficiência?',
                        'campo' => 'ef_portador_deficiencia',
                        'vetor' => [
                            'Não',
                            'Sim',
                        ],
                        'dados' => $d->ef_portador_deficiencia
                    ])?>
                </div>





                <div class="form-floating mb-3">
                    <div textarea type="text" name="ef_portador_deficiencia_descricao" id="ef_portador_deficiencia_descricao" class="form-control form-control-sm" placeholder="Descreva a Definiência" value="<?=$d->ef_portador_deficiencia_descricao?>"></div>
                    <label for="ef_portador_deficiencia_descricao">Descreva a Deficiência</label>
                </div>


                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'Informe o custo mensal, se possui gastos fixos com saúde?',
                        'campo' => 'ef_gastos_saude',
                        'vetor' => [
                            'Não Possuo',
                            'Até R$ 100,00',
                            'Acima R$ 100,00 até R$ 200,00',
                            'Acima R$ 200,00 até R$ 400,00',
                            'Acima R$ 400,00 até R$ 600,00',
                            'Acima R$ 600,00',
                        ],
                        'dados' => $d->ef_gastos_saude
                    ])?>
                </div>

                <div class="form-floating mb-3">
                    <?=montaOpcPrint([
                        'rotulo' => 'Necessita de Documentos?',
                        'campo' => 'ef_necessita_documentos',
                        'vetor' => [
                            'Não',
                            'RCN', // - Registro Civil de Nascimento
                            'RCC', // -
                            'RG', // - Registro Geral
                            'CPF', //- Cadastro de Pessoa Física
                            'CTPS', // - Carteira de Trabalho e Previdencia Social
                            'TE', //- Título Eleitoral
                        ],
                        'dados' => $d->ef_necessita_documentos
                    ])?>
                </div>

</div>
</div>


            </div>
        </div>
</div>

<?php
}
?>