<?php

// // Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
// $ch = curl_init();

// curl_setopt($ch, CURLOPT_URL, 'https://apinew.socialhub.pro/api/sendGroupMessage');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
// curl_setopt($ch, CURLOPT_POST, 1);
// $post = array(
//     'api_token' => '\"SEU API_TOKEN AQUI\"',
//     //'group' => '\"ID DO GRUPO DESTINO\"',
//     //'file' => '@' .realpath('\"DIRETÓRIO DO ARQUIVO\"'),
//     'message' => '\"Enviando a mensagem para validação da API de teste conforme as especificações.\"'
// );
// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// $result = curl_exec($ch);
// if (curl_errno($ch)) {
//     echo 'Error:' . curl_error($ch);
// }
// curl_close($ch);



// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://apinew.socialhub.pro/api/sendMessage');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL Verification
curl_setopt($ch, CURLOPT_POST, 1);
$post = array(
    'api_token' => "cehsUsTLkMgHyOg079WCrSmLiuopNbjx",
    'phone' => "+201116680996",
    // 'file' => '@' .realpath('\"DIRETÓRIO DO ARQUIVO\"'),
    'message' => "Enviando a mensagem para validação da API de teste conforme as especificações - fase 3."
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

echo $result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);