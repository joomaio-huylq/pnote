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

class AdminNote extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.form',
                'backend.preview'
            ]
        ];
    }
    
    private function getItem()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars && isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

        $data = $id ? $this->NoteFileModel->getDetail($id) : [];
        $data_form = $this->session->getform('note_upload', []);
        $this->session->setform('note_upload', []);
        $data = $data_form ? $data_form : $data;

        return $data;
    }

    public function form()
    {
        $data = $this->getItem();
        $id = isset($data['id']) ? $data['id'] : 0;

        $form = new Form($this->getFormFields(), $data);
        $isImage = isset($data['path']) && $data['path'] ? $this->NoteFileModel->isImage(PUBLIC_PATH . $data['path']) : false;;
        
        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'isImage' => $isImage,
            'title_page_edit' => $data && $data['title'] ? $data['title'] : 'New Note',
            'url' => $this->router->url(),
            'link_list' => $this->router->url($this->session->get('link_back_note', '')),
            'link_form' => $id ? $this->router->url('note/edit') : $this->router->url('new-note/upload'),
            'link_preview' => $id ? $this->router->url('note/preview/'. $id) : '',
        ];
        
    }

    public function getFormFields()
    {
        $fields = [
            'notice' => [
                'textarea',
                'label' => 'Notice',
                'placeholder' => 'Notice',
                'formClass' => 'form-control',
            ],
            'file' => [
                'file',
                'label' => 'File',
                'required' => 'required',
                'formClass' => 'form-control',
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
        if (in_array('note_manager', $asset) || $data['created_by'] == $this->user->get('id'))
        {
            $button_header[] = [
                'link' => $this->router->url('note/edit/'. $id),
                'class' => 'btn ms-2 btn-outline-success',
                'title' => 'Edit',
            ];
        }

        $isImage = $this->NoteFileModel->isImage(PUBLIC_PATH . $data['path']);
        return [
            'id' => $id,
            'data' => $data,
            'isImage' => $isImage,
            'button_header' => $button_header,
            'title_page' => $data && $data['title'] ? $data['title'] : '',
            'url' => $this->router->url(),
        ];
        
    }
}
