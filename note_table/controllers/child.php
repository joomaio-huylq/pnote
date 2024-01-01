<?php

namespace App\pnote\note_table\controllers;

use SPT\Web\ControllerMVVM;

class child extends ControllerMVVM
{
    public function search()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
        $search = trim($this->request->post->get('search', '', 'string'));
        $ignore = trim($this->request->post->get('ignore', '', 'string'));

        $list = $this->NoteTableModel->search($search, $ignore);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }
}