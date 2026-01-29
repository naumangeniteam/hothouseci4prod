<?php

namespace App\Libraries;

/**
 * Backward compatibility wrapper for encryption
 */
class Encrypt
{
    /**
     * Encrypts a message using CI_Encrypt
     */
    public static function encode($message, $key, $encode = true)
    {
        return CI_Encrypt::encode($message, $key, $encode);
    }
    
    /**
     * Decrypts a message using CI_Encrypt
     */
    public static function decode($message, $key, $encoded = true)
    {
        return CI_Encrypt::decode($message, $key, $encoded);
    }
    
    /**
     * Simple encryption using Safecrypto
     */
    public static function simple_encode($message, $key, $encode = true)
    {
        return Safecrypto::encode($message, $key, $encode);
    }
    
    /**
     * Simple decryption using Safecrypto
     */
    public static function simple_decode($message, $key, $encoded = true)
    {
        return Safecrypto::decode($message, $key, $encoded);
    }
}