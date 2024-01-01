<?php
namespace App\pnote\note_upload\registers;

use SPT\Application\IApp;
use SPT\File;

class Bootstrap
{
    public static function initialize( IApp $app)
    {
        $container = $app->getContainer();
        if (!$container->exists('file')) 
        {
            $container->set('file', new File());
        }
    }
}