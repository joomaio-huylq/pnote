<?php
namespace App\pnote\pnote\registers;

use SPT\Application\IApp;
use App\pnote\pnote\libraries\NoteDispatch;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        if(!$app->get('restApi', ''))
        {
            $app->plgLoad('permission', 'CheckSession');
        }

        $cName = $app->get('controller');
        $fName = $app->get('function');
        // prepare note

        $controller = 'App\pnote\pnote\controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($app->getContainer());
        $controller->{$fName}();
        
        $app->set('theme', $app->cf('adminTheme'));

        $fName = 'to'. ucfirst($app->get('format', 'html'));

        $app->finalize(
            $controller->{$fName}()
        );
    }
}