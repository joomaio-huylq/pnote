<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace App\pnote\share_note\controllers;

use SPT\Web\ControllerMVVM;

class ajax extends ControllerMVVM
{
    public function search()
    {
        $search = trim($this->request->get->get('search', '', 'string'));

        $data = $this->ShareUserModel->search($search);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $data);
        $this->set('message' , '');
        return;
    }
}