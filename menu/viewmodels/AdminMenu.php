<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\pnote\menu\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class AdminMenu extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'backend.sidebar',
            ]
        ];
    }

    public function sidebar()
    {
        return [
            'menu' => $this->MenuModel->getItems(),
            'menu_shortcut' => $this->MenuModel->getShortcuts(),
        ];
    }
}
