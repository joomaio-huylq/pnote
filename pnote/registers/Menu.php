<?php
namespace App\pnote\pnote\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        return [
            'menu' => [],
            'order' => 0,
        ];
    }
}