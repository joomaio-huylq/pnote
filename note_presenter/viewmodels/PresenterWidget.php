<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\pnote\note_presenter\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class PresenterWidget extends ViewModel
{
    public static function register()
    {
        return [
            'widget' => [
                'preview',
            ],
        ];
    }
    
    public function preview($layoutData, $viewData)
    {
        $id = isset($viewData['currentId']) ? $viewData['currentId'] : 0;
        $data = $this->NotePresenterModel->getDetail($id);
        if($data)
        {
            $data['data_'. $id] = $data['data'];
        }
        
        $form = new Form($this->getFormFields($id), $data);

        $history = $this->HistoryModel->list(0, 0, ['object' => 'note', 'object_id' => $id]);
        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'url' => $this->router->url(),
        ];
        
    }

    public function getFormFields($id)
    {
        $fields = [
            'data_'. $id => [
                'presenter',
                'label' => 'Presenter',
                'formClass' => 'form-control',
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }
}
