<?php

    if($_POST['amount']) $amount = str_replace(array('.',','),false, trim($_POST['amount']));
    if($_POST['cardNumber']) $cardNumber = str_replace(array(' '),false,trim($_POST['cardNumber']));

    $rede = new Rede;
    $retorno = $rede->Transacao('{
        "capture": '.(($_POST['capture'])?:'true').',
        "kind": "'.(($_POST['kind'])?:'credit').'", 
        "reference": "'.$_POST['reference'].'",
        "amount": '.$amount.',
        '.(($_POST['installments'])?'"installments": '.$_POST['installments'].',':false).'
        "cardholderName": "'.$_POST['cardholderName'].'",
        "cardNumber": "'.$cardNumber.'",
        "expirationMonth": "'.$_POST['expirationMonth'].'",
        "expirationYear": "'.$_POST['expirationYear'].'",
        "securityCode": "'.$_POST['securityCode'].'",
        "softDescriptor": "'.$_POST['softDescriptor'].'",
        "subscription": '.(($_POST['subscription'])?:'false').',
        "origin": 1,
        "distributorAffiliation": '.(($_POST['distributorAffiliation'])?:'0').'
        '.(($_POST['brandTid'])?'", brandTid": '.$_POST['brandTid'].',':false).'
    }');