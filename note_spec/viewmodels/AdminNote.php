<?php
namespace App\pnote\note_spec\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class AdminNote extends ViewModel
{
    public static function register()
    {
        return [
            'layout' => [
                'backend.form',
                'backend.form.popup_note',
                'backend.form.popup_type',
                'backend.preview',
                'backend.quick_view',
            ]
        ];
    }
    
    private function getItem()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars && isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

        $data = $this->NoteSpecModel->getDetail($id);
        $data_form = $this->session->getform('note_html', []);
        $this->session->setform('note_html', []);
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
            'link_preview' => isset($data['status']) && $data['status'] != -1 ? $this->router->url('note/detail/' . $id): '',
            'form' => $form,
            'data' => $data,
            'history' => $history,
            'title_page_edit' => $data && $data['title'] ? $data['title'] : 'New Note',
            'url' => $this->router->url(),
            'link_list' => $this->router->url($this->session->get('link_back_note', '')),
            'link_history' => $this->router->url('history/note-html'),
            'link_form' => $id ? $this->router->url('note/edit') : $this->router->url('new-note/html'),
            'link_form_note' => $this->router->url('new-note'),
            'link_note_alias' => $this->router->url('new-alias'),
            'link_detail_note' => $this->router->url('note/edit'),
            'link_note_search' => $this->router->url('note-spec/search/' . $id),
            'link_update_position' => $this->router->url('note-spec/update-position/'. $id),
            'link_load_document' => $this->router->url('note-spec/load-document/'. $id),
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
            'data' => [
                'tinymce',
                'label' => 'Html',
                'formClass' => 'form-control',
            ],
            'title' => [
                'text',
                'showLabel' => false,
                'placeholder' => 'New Title',
                'formClass' => 'form-control border-0 border-bottom fs-2 py-0',
                'required' => 'required',
            ],
            'structure' => ['hidden'],
            'removes' => ['hidden'],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }

    public function getFormNoteFields()
    {
        $fields = [
            'note_data' => [
                'tinymce',
                'label' => '',
                'formClass' => 'form-control',
            ],
            'note_notice' => [
                'textarea',
                'label' => 'Notice',
                'placeholder' => 'Notice',
                'formClass' => 'form-control',
            ],
            'note_title' => [
                'text',
                'showLabel' => false,
                'placeholder' => 'New Title',
                'formClass' => 'form-control border-0 border-bottom fs-2 py-0',
                'required' => 'required',
            ],
        ];

        return $fields;
    }

    public function popup_note()
    {
        $form = new Form($this->getFormNoteFields(), []);

        return [
            'form' => $form,
            'link_note' => $this->router->url('note-spec/child'),
        ];
    }

    public function preview()
    {
        $data = $this->getItem();

        $id = isset($data['id']) ? $data['id'] : 0;
        $button_header = [
            [
                'link' => $this->router->url($this->session->get('link_back_note', 'notes')),
                'class' => 'btn btn-secondary',
                'title' => 'Cancel',
            ],
        ];

        $asset = $this->PermissionModel->getAccessByUser();
        if (in_array('note_manager', $asset) || $data['created_by'] == $this->user->get('id'))
        {
            $button_header[] = [
                'link' => $this->router->url('note/edit/'. $id),
                'class' => 'btn btn-success',
                'title' => 'Edit',
            ];
        }

        return [
            'url' => $this->router->url(),
            'link_list' => $this->router->url($this->session->get('link_back_note', '')),
            'link_form' => $this->router->url('note/edit'),
            'url' => $this->router->url(),
            'button_header' => $button_header,
            'id' => $id,
            'data' => $data,
        ];
    }

    public function popup_type()
    {
        $note_types = $this->NoteModel->getTypes();
        
        return [
            'note_types' => $note_types,
            'link_form_note' => $this->router->url('new-note'),
        ];
    }

    public function quick_view()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $detail = $this->NoteSpecModel->getDetail($id);

        return [
            'detail' => $detail,
            'url' => $this->router->url(),
        ];
    }
}
