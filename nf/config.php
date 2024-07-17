<?php

// CONEXAO PDO MySQL
$PDO = new PDO("mysql:host=yobom.com.br;dbname=bk_estoque;charset=utf8", "root", "SenhaDoBanco");


// ENDEREÇO DA API
$endpoint = "https://nf.bkmanaus.com.br/API-NFE/api-nfe/"; // COM BARRA NO FINAL


ini_set('display_errors', 'On');
