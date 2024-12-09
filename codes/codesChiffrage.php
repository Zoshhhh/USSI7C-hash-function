<?php
const CIPHERAGE = "AES-128-CTR";
define("LONGUEURIV", openssl_cipher_iv_length(CIPHERAGE));
const OPTIONS = 0;
const IV_CHIFFREMENT = "1234567891011121";
const CLE_CHIFFREMENT = "GeeksforGeeks";
?>
