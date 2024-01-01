<?php
namespace App\pnote\note_spec\registers;

use SPT\Application\IApp;
use App\pnote\note_spec\entities\TreeNoteEntity;
use SPT\Support\Loader;

class Installer
{
    public static function info()
    {
        return ['tags'=>['pnote']];
    }
    
    public static function name()
    {
        return 'Plugin note spec';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin note spec'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function assets()
    {
        return [
            'specs' => 'assets/specs',
        ];
    }

    public static function install( IApp $app)
    {
        $container = $app->getContainer();
        Loader::findClass( 
            SPT_PLUGIN_PATH. 'pnote/note_spec/entities', 
            'App\pnote\note_spec\entities', 
            function($classname, $fullname) use (&$container)
            {
                $x = new $fullname($container->get('query'));
                $x->checkAvailability();
            });

        return true;
    }
    public static function uninstall( IApp $app)
    {
        $container = $app->getContainer();
        Loader::findClass( 
            SPT_PLUGIN_PATH. 'pnote/note_spec/entities', 
            'App\pnote\note_spec\entities', 
            function($classname, $fullname) use (&$container)
            {
                $x = new $fullname($container->get('query'));
                $x->dropTable();
            });

        return true;
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