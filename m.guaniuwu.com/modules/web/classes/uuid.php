<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 生成UUID
 *
 * @filesource UUID.php
 * @package Misc.
 * @version $id: 0.1, utf8, Tue Apr 13 11:44:26 CST 2010
 * @author LD
 * @example
 *
 */
class Uuid
{
    public static function v1 ($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }
    public static function v3 ($namespace, $name)
    {
        if (! self::is_valid($namespace))
            return false;
             // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);
        // Binary Value
        $nstr = '';
        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }
        // Calculate hash value
        $hash = md5($nstr . $name);
        return sprintf('%08s-%04s-%04x-%04x-%12s', // 32 bits for "time_low"
        substr($hash, 0, 8), 
        // 16 bits for "time_mid"
        substr($hash, 8, 4), 
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 3
        (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000, 
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000, 
        // 48 bits for "node"
        substr($hash, 20, 12));
    }
    public static function v4 ()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', 
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 16 bits for "time_mid"
        mt_rand(0, 
        0xffff), // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000, 
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000, // 48 bits for "node"
        mt_rand(0, 
        0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }
    public static function v5 ($namespace, $name)
    {
        if (! self::is_valid($namespace))
            return false;
             // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);
        // Binary Value
        $nstr = '';
        // Convert Namespace UUID to bits
        for ($i = 0; $i < strlen($nhex); $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }
        // Calculate hash value
        $hash = sha1($nstr . $name);
        return sprintf('%08s-%04s-%04x-%04x-%12s', // 32 bits for "time_low"
        substr($hash, 0, 8), 
        // 16 bits for "time_mid"
        substr($hash, 8, 4), 
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 5
        (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000, 
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000, 
        // 48 bits for "node"
        substr($hash, 20, 12));
    }
    public static function is_valid ($uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
	    '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
	  }
}//END class UUID
