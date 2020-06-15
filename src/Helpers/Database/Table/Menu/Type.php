<?php namespace CI4Xpander_Dashboard\Helpers\Database\Table\Menu;

use Config\Database;

class Type
{
    public static function get($code = null)
    {
        $builder = Database::connect();

        $typeTable = $builder->table('menu_type');

        if (!is_null($code)) {
            $typeTable->where('code', $code);
        }

        if (!is_null($code)) {
            return $typeTable->get()->getRow();
        } else {
            return $typeTable->get()->getResult();
        }
    }
}