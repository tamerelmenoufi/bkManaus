<?php
    $app = true;
    include("{$_SERVER['DOCUMENT_ROOT']}/bkManaus/lib/includes.php");
?>
<style>
    .barra_topo{
        position:absolute;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        flex-direction: column;
        top:0;
        width:100%;
        height:100px;
        background-color:#0f34d3;
        color:#f9de37;
        border-bottom-right-radius:40px;
        border-bottom-left-radius:40px;
        font-family:FlameBold;
    }
    .topo > .voltar{
        color:#f9de37!important;
    }

    .topo > .dados{
        color:#fff!important;
    }


    .home_corpo{
        position: absolute;
        top:100px;
        bottom:80px;
        overflow:auto;
        background-color:#fff;
        width:100%;
    }

    .home_rodape{
        position: absolute;
        background-color:#fff;
        width:100%;
        bottom:0;
        height:80px;
    }
    a{
        text-decoration:none;
        color:blue;
    }
    a:hover{
        text-decoration:none;
        color:orange;
    }
    

</style>


<div class="barra_topo">
    <h6>Informações</h6>
</div>

<div class="home_corpo p-2">
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                Para Regulamento, dúvidas, sugestões ou reclamações, acesse um dos links a seguir:
            </div>
        </div>
        <div class="row mb-1">
            <div class="col">
                <i class="fa-brands fa-whatsapp"></i> <a href="https://api.whatsapp.com/send?phone=5592986123301" target="_blank">+55 92 986123301</a>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col">
                <i class="fa-solid fa-at"></i> <a href="mailto:atendimento@bkmanaus.com.br" target="_blank">atendimento@bkmanaus.com.br</a>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col">
                <i class="fa-solid fa-house"></i> <a href="https://bkmanaus.com.br" target="_blank">https://bkmanaus.com.br</a>
            </div>
        </div>
        <div class="row mb-1">
            <div class="col">
                <span style="color:#a1a1a1">Aplicativo atende pedidos todos os dias das 11h às 23h.</span>
            </div>
        </div>      
    </div>


    <div class="card mt-3">
        <div class="card-header">
            <h5>Regulamento da Promoção "Entrega Grátis BK Manaus - Agosto 2024"</h5>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                

                <h6>1. Objetivo da Promoção</h6>
                <p>
                    A promoção "Entrega Grátis BK Manaus - Agosto 2024" tem como objetivo oferecer enregas grátis nas compras realizadas durante o mês de agosto de 2024 para os pedidos que utilizarem o código promocional divulgado.
                </p>
                
                <h6>2. Período da Promoção</h6>
                <p>
                    A promoção é válida das 00h00 do dia 1º de agosto de 2024 até as 23h59 do dia 31 de agosto de 2024.
                </p>
                
                <h6>3. Abrangência</h6>
                <p>
                    A promoção é válida exclusivamente para pedidos realizados na cidade de Manaus, Amazonas, Brasil.
                </p>
                
                <h6>4. Participação</h6>
                <p>Para participar da promoção, os clientes devem:</p>
                <ul>
                    <li>Acessar o aplicativo próprio da BK Manaus</li>
                    <li>Realizar a compra durante o período de vigência da promoção.</li>
                    <li>Utilizar o código promocional, divulgado pelas mídias sociais, cupons e banners da bk Manaus, no ato da compra.</li>
                </ul>
                
                <h6>5. Código Promocional</h6>
                <p>
                    O código promocional será divulgado através de banners, cupons e mídias sociais da BK Manaus. O código deve ser inserido no campo apropriado do aplicativo no momento da finalização do pedido para que o desconto da entrega gratuita sejam aplicados.
                </p>
                
                <h6>6. Desconto</h6>
                <ul>
                    <li>O desconto será aplicado automaticamente ao valor total da compra após a inserção do código promocional válido.</li>
                    <li>O valor do desconto será de do valor integral da taxa de entrega de acordo com o cálculo da distância definido pelo aplicativo.</li>
                </ul>
                
                <h6>7. Entrega Gratuita</h6>
                <ul>
                    <li>A entrega gratuita é válida somente para pedidos realizados com o uso do código promocional divulgado.</li>
                    <li>A promoção de entrega gratuita abrange apenas os endereços de entrega no raio de 8 km de distância das projas ativas para o delivery (Loja Paraíba, Loja Djalma Batista e Loja Stúdio 5) localizados na cidade de Manaus.</li>
                </ul>
                
                <h6>8. Condições Gerais</h6>
                <ul>
                    <li>A promoção não é cumulativa com outras promoções, descontos ou ofertas.</li>
                    <li>O código promocional é de uso contínuo durante o período promocional.</li>
                    <li>Pedidos que não utilizarem o código promocional no momento da compra não serão elegíveis para o desconto e a entrega gratuita.</li>
                    <li>Caso o cliente esqueça de inserir o código promocional, não será possível aplicar o desconto e a entrega gratuita após a finalização do pedido.</li>
                </ul>
                
                <h6>9. Disposições Finais</h6>
                <ul>
                    <li>A BK Manaus se reserva o direito de alterar os termos desta promoção a qualquer momento, sem aviso prévio, respeitando os direitos adquiridos pelos participantes até a data da alteração.</li>
                    <li>Em caso de dúvidas ou controvérsias sobre a promoção, os participantes poderão entrar em contato com o atendimento ao cliente da BK Manaus.</li>
                    <li>A participação nesta promoção implica na aceitação total e irrestrita de todos os termos e condições deste regulamento.</li>
                </ul>
                
                <p style="margin-top:30px;">Manaus, 01 de agosto de 2024.</p>


            </li>
        </ul>
    </div>
    
</div>   
<div class="home_rodape"></div>

<script>

$(function(){

    idUnico = localStorage.getItem("idUnico");
    codUsr = localStorage.getItem("codUsr");    

    $.ajax({
        url:"rodape/rodape.php",
        success:function(dados){
            $(".home_rodape").html(dados);
        }
    });

    $.ajax({
        url:"topo/topo.php",
        type:"POST",
        data:{
            idUnico,
            codUsr
        },  
        success:function(dados){
            $(".barra_topo").append(dados);
        }
    });


})

	

</script>