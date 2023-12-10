<?php

class mottu {

    public $ambiente = 'producao'; //homologacao ou producao

    public function Ambiente($opc){
        if($opc == 'homologacao'){
            return 'https://integrations.mottu.io/delivery';
        }else{
            return 'https://integrations.mottu.cloud/delivery';
        }
    }

    public function apiKey($opc, $loja){
        if($opc == 'homologacao'){
            return 'F74C23D9DF05489E9A5185EB5F7DEE28';
        }else{
            $Lojas = [
                '813416' => '8C0CC3BEBE314FD1830520A2A09AC8F8', //Humberto Calderaro
                '813383' => 'A150C55FB8434331BE8EE44BAB9A7BA7', //Djalma Batista
            ];
            return $Lojas[$loja];
        }
    }

    public function integradora($opc, $loja){
        if($opc == 'homologacao'){
            return '';
        }else{
            $Lojas = [
                '813416' => '87D8D360FFB64F64A88CEBD055EFD656', //Humberto Calderaro
                '813383' => '87D8D360FFB64F64A88CEBD055EFD656', //Djalma Batista
            ];
            return $Lojas[$loja];
        }
    }

    public function NovoPedido($json, $loja = false){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente).'/orders',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente, $loja),
            'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";

    }


    public function ConsultarPedido($pedido, $loja = false){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente).'/orders/'.$pedido,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente, $loja),
            'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";

    }


    public function cancelarPedido($json, $loja = false){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente).'/orders/cancel',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente, $loja),
            'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";
    }

    public function calculaFrete($json, $loja = false){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente)."/orders/preview",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente, $loja),
            'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente, $a)."/orders/preview"."\n".$this->apiKey($this->ambiente, $loja, $a)."\n";

    }

    public function webhook($json, $loja = false){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->Ambiente($this->ambiente)."/webhooks/handle",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>$json,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'x-api-token: '.$this->apiKey($this->ambiente, $loja),
            'x-integrator-token: '.$this->integradora($this->ambiente, $loja),
            'accept: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response; //."\n".$this->Ambiente($this->ambiente)."\n".$this->apiKey($this->ambiente, $loja)."\n";

    }


}