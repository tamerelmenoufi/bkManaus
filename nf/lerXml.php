<?php
    include('config.php');

    function DataFormat($dt){
        // 2022-09-09T21:59:52-04:00
        $dt = substr($dt, 0, -6);
        list($d, $h) = explode("T",$dt);
        list($a, $m, $d) = explode("-",$d);
        return "{$d}/{$m}/{$a} {$h}";
    }

    $query = "select * from vendas where codigo = ?";
    $stmt = $PDO->prepare($query);
    $stmt->execute([10834]);
    $nota = $stmt->fetch(PDO::FETCH_ASSOC);

    $dados = json_decode($nota['nf_json']);

    echo "<pre>";
    //  print_r($dados);

    echo "urlChave: ".$dados->NFe->infNFeSupl->urlChave;
    echo "<br>";
    echo "chNFe: ".$dados->protNFe->infProt->chNFe;
    echo "<br>";


    echo "nNF: ".str_pad($dados->NFe->infNFe->ide->nNF, 9, '0', STR_PAD_LEFT);
    echo "<br>";
    echo "serie: ".str_pad($dados->NFe->infNFe->ide->serie, 3, '0', STR_PAD_LEFT);
    echo "<br>";
    echo "dhEmi: ".DataFormat($dados->NFe->infNFe->ide->dhEmi);
    echo "<br>";


    echo "nProt: ".$dados->protNFe->infProt->nProt;
    echo "<br>";
    echo "dhRecbto: ".DataFormat($dados->protNFe->infProt->dhRecbto);
    echo "<br>";
    echo "qrCode: ".$dados->NFe->infNFeSupl->qrCode;

    echo "</pre>";
