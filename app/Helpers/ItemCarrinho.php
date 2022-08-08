<?php

use App\Models\Carrinho;

if (!function_exists('count_item')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function count_item($id)
    {
        $count_item = Carrinho::with('carItens')->where('user_id', $id)->where('status', 'Aberto')->first();
     
        return $count_item ? $count_item->carItens()->count() : '0';
    }
}
