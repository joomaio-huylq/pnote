<?php

namespace App\pnote\note_mermaidjs\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class AdminNote extends ViewModel
{
    public static function register()
    {
        return [
            'layout' => [
                'backend.view',
                'backend.form',
                'backend.history',
                'backend.preview'
            ]
        ];
    }
    
    private function getItem()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars && isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

        $data = $this->NoteMermaidModel->getDetail($id);
        $data_form = $this->session->getform('note_mermaidjs', []);
        $this->session->setform('note_mermaidjs', []);
        $data = $data_form ? $data_form : $data;

        return $data;
    }

    public function form()
    {
        $data = $this->getItem();
        $id = isset($data['id']) ? $data['id'] : 0;

        $form = new Form($this->getFormFields(), $data);

        $history = $this->HistoryModel->list(0, 0, ['object' => 'note', 'object_id' => $id]);
        
        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'history' => $history,
            'title_page_edit' => $data && $data['title'] ? $data['title'] : 'New Note',
            'url' => $this->router->url(),
            'link_list' => $this->router->url($this->session->get('link_back_note', '')),
            'link_history' => $this->router->url('history/note-mermaid'),
            'link_preview' => $data['status'] != '-1' ? $this->router->url('note/preview/'. $id) : '',
            'link_form' => $id ? $this->router->url('note/edit') : $this->router->url('new-note/html'),
        ];
        
    }

    public function history()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars && isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

        $history = $this->HistoryModel->detail($id);

        $data = $this->NoteMermaidModel->getDetail($history['object_id']);
        $data['data'] = $history['data'];

        $form = new Form($this->getFormFields(), $data);

        $button_header = [
            [
                'link' =>  $this->router->url(isset($data['id']) ? 'note/edit/'.$data['id'] : $this->session->get('link_back_note', 'notes')),
                'class' => 'btn btn-outline-secondary',
                'title' => 'Cancel',
            ],
            [
                'link' => '',
                'class' => 'btn ms-2 btn-outline-success button-rollback',
                'title' => 'Rollback',
            ],
        ];

        return [
            'id' => $id,
            'form' => $form,
            'button_header' => $button_header,
            'data' => $data,
            'title_page' => isset($data['id']) ? $data['title'] . ' - Modified at: '. $history['created_at'] : 'Modified at: '. $history['created_at']  ,
            'url' => $this->router->url(),
            'link_history' => $this->router->url('history/note-mermaid'),
            'link_form' => $this->router->url('history/note-mermaid'),
        ];
    }

    public function getFormFields()
    {
        $fields = [
            'notice' => [
                'textarea',
                'label' => '',
                'placeholder' => 'Notice',
                'formClass' => 'form-control',
            ],
            'data' => [
                'showLabel' => false,
                'textarea',
                'label' => '',
                'formClass' => 'form-control',
            ],
            'mermaid' => [
                'type' => 'mermaidjs',
                'layout' => 'note_mermaidjs::fields.mermaidjs',
                'showLabel' => false,
            ],
            'title' => [
                'text',
                'showLabel' => false,
                'placeholder' => 'New Title',
                'formClass' => 'form-control border-0 border-bottom fs-2 py-0',
                'required' => 'required',
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }

    public function preview()
    {
        $data = $this->getItem();
        $id = isset($data['id']) ? $data['id'] : 0;

        $button_header = [
            [
                'link' => $this->router->url($this->session->get('link_back_note', 'notes')),
                'class' => 'btn btn-outline-secondary',
                'title' => 'Cancel',
            ],
        ];

        $asset = $this->PermissionModel->getAccessByUser();
        if (in_array('note_manager', $asset) ||  $data['created_by'] == $this->user->get('id'))
        {
            $button_header[] = [
                'link' => $this->router->url('note/edit/'. $id),
                'class' => 'btn ms-2 btn-outline-success',
                'title' => 'Edit',
            ];
        }
        
        $form = new Form($this->getFormFields(), $data);

        return [
            'id' => $id,
            'data' => $data,
            'form' => $form,
            'button_header' => $button_header,
            'title_page' => $data && $data['title'] ? $data['title'] : '',
            'url' => $this->router->url(),
        ];
    }
}
