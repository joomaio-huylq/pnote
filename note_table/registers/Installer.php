<?php
namespace App\pnote\note_table\registers;

use SPT\Application\IApp;

class Installer
{
    public static function info()
    {
        return ['tags'=>['pnote']];
    }
    
    public static function name()
    {
        return 'Plugin note table';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin note table'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function assets()
    {
        return [
            'handsontable' => 'assets/handsontable',
        ];
    }

    public static function install( IApp $app)
    {
        return true;
    }
    public static function uninstall( IApp $app)
    {
        // run sth to uninstall
    }
    public static function active( IApp $app)
    {
        // run sth to prepare the install
    }
    public static function deactive( IApp $app)
    {
        // run sth to uninstall
    }
}