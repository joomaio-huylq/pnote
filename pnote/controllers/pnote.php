<?php
namespace App\pnote\pnote\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class pnote extends ControllerMVVM
{
    public function dashboard()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'pnote.dashboard');
    }
}