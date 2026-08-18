<?php

if (!function_exists('getGUID')) {

    function getGUID() {
        if (function_exists('com_create_guid')) {
            $uuid = com_create_guid();
            $uuid = str_replace('{', '', $uuid);
            $uuid = str_replace('}', '', $uuid);
            $uuid = str_replace('-', '', $uuid);
            return $uuid;
        } else {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
            return strtoupper(bin2hex($data));
        }
    }

}
?>