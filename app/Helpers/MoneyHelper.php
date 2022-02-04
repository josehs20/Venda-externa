<?php

if (!function_exists('reais')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function reais($valor)
    {
        return number_format($valor, 2, ',', '.');
    }
}
