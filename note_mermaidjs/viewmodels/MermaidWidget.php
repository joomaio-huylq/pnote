<?php

namespace App\pnote\note_mermaidjs\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class MermaidWidget extends ViewModel
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
        $data = $this->NoteMermaidModel->getDetail($id);
        $form = new Form($this->getFormFields($id), $data);

        return [
            'data' => $data,
            'form' => $form,
        ];
        
    }

    public function getFormFields($id)
    {
        $fields = [
            'mermaid_'.$id => [
                'type' => 'mermaidjs',
                'layout' => 'note_mermaidjs::fields.mermaidjs',
                'showLabel' => false,
            ],
        ];

        return $fields;
    }
}
