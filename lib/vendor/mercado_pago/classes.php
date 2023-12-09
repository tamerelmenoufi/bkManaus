<?php

    class MercadoPago {

        public $Ambiente = 'producao'; //homologacao ou producao

        public function Autenticacao(){
            global $cBk;
            return $cBk['mercado_pago'][$this->Ambiente]['TOKEN'];
        }

        public function Ambiente($opc){
            if($opc == 'homologacao'){
                return 'https://api.mercadopago.com/v1/payments/';
            }else{
                return 'https://api.mercadopago.com/v1/payments/';
            }
        }

        public function Transacao($d){

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Content-Type: application/json",
              "Authorization: Bearer ".$this->Autenticacao()
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $d);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }

        public function ObterPagamento($Id){


            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente).$Id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              //"Content-Type: application/json",
              "Authorization: Bearer ".$this->Autenticacao()
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $d);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }

        public function CancelarPagamento($Id){

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->Ambiente($this->Ambiente).$Id);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Content-Type: application/json",
              "Authorization: Bearer ".$this->Autenticacao()
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{"status": "cancelled"}');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        }


    }