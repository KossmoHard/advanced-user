<?php

function decryptor($id){
  $user_meta = array_map( function( $a ){ return $a[0]; }, get_user_meta( $id ) );
  $user_meta = array_intersect_key($user_meta, array(
    'address' => '',
    'phone' => '',
    'gender' => '',
    'family-status' => ''
  ));
  //var_dump($user_meta);
  foreach ($user_meta as $key => $value) {
    $filename = sprintf("%s%s_%s.txt", ADVANCED_USER_DIR . 'private-key/', $key, $id);
    $encrypted = base64_decode($value);

    $private_key = file_get_contents($filename);
    $private_key = openssl_pkey_get_private($private_key, "phrase");

    openssl_private_decrypt($encrypted, $decrypted, $private_key);
    $user_meta[$key] = $decrypted;
  }
  return $user_meta;
}
