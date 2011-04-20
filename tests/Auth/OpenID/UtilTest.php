<?php

/**
 * Tests for utility functions used by the OpenID library.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: See the COPYING file included in this distribution.
 *
 * @package OpenID
 * @author JanRain, Inc. <openid@janrain.com>
 * @copyright 2005-2008 Janrain, Inc.
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache
 */

require_once 'Auth/OpenID.php';

class Tests_Auth_OpenID_Util extends PHPUnit_Framework_TestCase {
    function test_base64()
    {
        // This is not good for international use, but PHP doesn't
        // appear to provide access to the local alphabet.
        $letters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $digits = "0123456789";
        $extra = "+/=";
        $allowed_s = $letters . $digits . $extra;
        $allowed_d = array();

        for ($i = 0; $i < strlen($allowed_s); $i++) {
            $c = $allowed_s[$i];
            $allowed_d[$c] = null;
        }

        function checkEncoded($obj, $str, $allowed_array)
            {
                for ($i = 0; $i < strlen($str); $i++) {
                    $obj->assertTrue(array_key_exists($str[$i],
                                                      $allowed_array));
                }
            }

        $cases = array(
                       "",
                       "x",
                       "\x00",
                       "\x01",
                       str_repeat("\x00", 100),
                       implode("", array_map('chr', range(0, 255)))
                       );

        foreach ($cases as $s) {
    