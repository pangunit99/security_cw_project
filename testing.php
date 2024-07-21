<?php

$plaintext = "Confidential";

/* Use AES256 CBC mode to encrypt and decrypt
For the supporting cipher, please check the function openssl_get_cipher_methods() */
$cipher = "aes-256-cbc";
            
// The key must be 256 bits
$key = "gepiOMjbWnQSGgp9VDgimccjXR7FeiSz";

if (in_array($cipher, openssl_get_cipher_methods()))
{
    // Declare the length of IV
    $ivlen = openssl_cipher_iv_length($cipher);
    
    // Generate random IV
    $iv = openssl_random_pseudo_bytes($ivlen);
    
    // Encrypt plaintext to ciphertext
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
    echo "Ciphetext: " . $ciphertext . "<br>";
    echo "iv: " . $iv . "<br>";


    $ivHex = bin2hex($iv);
    echo "IV (Hexadecimal): " . $ivHex . "<br>";
    $ivbin = hex2bin($ivHex);
    echo "IV (bin): " . $ivbin . "<br>";
    // Decrypt ciphertext to plaintext
    $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
    echo "PlainText: " . $original_plaintext."\n";
    echo "tag: " . $tag."\n";
}
?>