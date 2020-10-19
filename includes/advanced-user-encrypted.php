<?php

function save_to_file($privKey, $user_id, $key){
  $filename = sprintf("%s%s_%s.txt", ADVANCED_USER_DIR .'private-key/', $key, $user_id);
  $fp = fopen($filename, "w+"); // Открываем файл в режиме записи
  $test = fwrite($fp, $privKey); // Запись в файл
  // if ($test) echo 'Данные в файл успешно занесены.';
  // else echo 'Ошибка при записи в файл.';
  fclose($fp); //Закрытие файла
}
function encrypted_decrypted($value, $user_id, $key){
  $res = openssl_pkey_new();

  if ($res === false) die('Failed to generate key pair.'."\n");

  if (!openssl_pkey_export($res, $privKey, "phrase")) die('Failed to retrieve private key.'."\n");

  // Extract the private key from $res to $privKey
  openssl_pkey_export($res, $privKey, "phrase");
  $privKey2 = openssl_pkey_get_private($privKey, "phrase");

  save_to_file($privKey, $user_id, $key);

  // Extract the public key from $res to $pubKey
  $pubKey = openssl_pkey_get_details($res);
  $pubKey = $pubKey["key"];

  // Encrypt the data to $encrypted using the public key
  openssl_public_encrypt($value, $encrypted, $pubKey);

  // Decrypt the data using the private key and store the results in $decrypted
  openssl_private_decrypt($encrypted, $decrypted, $privKey2);

  $encrypted = base64_encode($encrypted);

  return $encrypted;
}
