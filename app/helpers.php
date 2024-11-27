<?php
if (!function_exists('myGlobalFunction')) {
    function myGlobalFunction($value)
    {
        return "Hello, " . $value;
    }
}