<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\pnote\note_html\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class HtmlWidget extends ViewModel
{
    public static function register()
    {
        return [
            'widget' => [
                'preview',
            ]
        ];
    }
    
    public function preview($layoutData, $viewData)
    {
        $id = isset($viewData['currentId']) ? $viewData['currentId'] : 0;
        $data = $this->NoteHtmlModel->getDetail($id);
        
        return [
            'data' => $data,
        ];
        
    }
}
