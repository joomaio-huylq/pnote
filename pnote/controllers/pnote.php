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

    public function createSuperUser() 
    {
        // get input data
        $data = [
            'name' => $this->request->post->get('name', '', 'string'),
            'username' => $this->request->post->get('username', '', 'string'),
            'email' => $this->request->post->get('email', '', 'string'),
            'password' => $this->request->post->get('password', '', 'string'),
            'confirm_password' => $this->request->post->get('confirm_password', '', 'string'),
        ];
        
        $try = $this->PNoteModel->createSuperUser($data);
        $status = $try['success'] ? 'success' : 'failed';

        $this->set('status', $status);
        $this->set('data', '');
        $this->set('message', $try['message']);
        return;
    }
}