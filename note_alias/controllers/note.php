<?php
namespace App\pnote\note_alias\controllers;

use SPT\Response;
use DTM\note\libraries\NoteController;

class note extends NoteController
{
    public function newform()
    {
    }

    public function detail()
    {
    }

    public function form()
    {
    }

    public function preview()
    {
    }

    public function add()
    {
        $note_id = $this->request->post->get('note_id', '', 'string');
        $try = $this->NoteAliasModel->add($note_id);

        $msg = $try ? 'Create Successfully' : $this->NoteAliasModel->getError();
        $status = $try ? 'success' : 'failed';

        $this->app->set('format', 'json');
        $this->set('status' , $status);
        $this->set('message' , $msg);
        $this->set('note_id' , $try);
        return;
    }
}