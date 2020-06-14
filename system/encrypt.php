<?php
  //hexdec($encIv);
  // hexdec($encPass);
  // openssl_encrypt ($source, $method, $pass, true, $iv));

  $encriptar = function($valor) {
    $encIv = "kZE93pbmuzxK34yw";
    $encPass = "459sPghBxrazjUMh";
    $encMethod = 'aes-128-cbc';
    return openssl_encrypt($valor, "aes-256-cbc", $encPass, false, $encIv);
  };

  $desencriptar = function($valor) {
    $encIv = "kZE93pbmuzxK34yw";
    $encPass = "459sPghBxrazjUMh";
    $encMethod = 'aes-128-cbc';

    $encrypted_data = base64_decode($valor);
    return openssl_decrypt($valor, "aes-256-cbc", $encPass, false, $encIv);
  };
?>
