<?php namespace CI4Xpander_Dashboard\Helpers\Database\Table;

use Config\Database;

class Menu
{
    public static function add($data = [])
    {
        $builder = Database::connect();
        $builder->table('menu')->insert($data);
    }
}