<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\pnote\note_upload\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class FileWidget extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'preview',
            ]
        ];
    }
    
    public function preview($layoutData, $viewData)
    {
        $id = isset($viewData['currentId']) ? $viewData['currentId'] : 0;
        $data = $this->NoteFileModel->getDetail($id);
        $isImage = $this->NoteFileModel->isImage(PUBLIC_PATH . $data['path']);
        
        return [
            'data' => $data,
            'isImage' => $isImage,
        ];
        
    }
}
