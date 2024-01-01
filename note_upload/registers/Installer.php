<?php
namespace App\pnote\note_upload\registers;

use SPT\Application\IApp;
use App\pnote\note_upload\entities\FileEntity;
use SPT\Support\Loader;

class Installer
{
    public static function info()
    {
        return ['tags'=>['pnote']];
    }
    
    public static function name()
    {
        return 'Plugin note upload';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin note upload'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function install( IApp $app)
    {
        $container = $app->getContainer();
        Loader::findClass( 
            SPT_PLUGIN_PATH. 'pnote/note_upload/entities', 
            'App\pnote\note_upload\entities', 
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
            SPT_PLUGIN_PATH. 'pnote/note_upload/entities', 
            'App\pnote\note_upload\entities', 
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