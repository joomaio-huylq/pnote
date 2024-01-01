<?php

namespace App\pnote\note_table\controllers;

use SPT\Response;
use DTM\note\libraries\NoteController;

class note extends NoteController
{
    public function newform()
    {
        $this->app->set('layout', 'backend.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function detail()
    {
        $this->app->set('layout', 'backend.preview');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function form()
    {
        $this->app->set('layout', 'backend.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function preview()
    {
        $this->app->set('layout', 'backend.preview');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }


    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.note.list');
    }

    public function add()
    {
        //check title sprint
        $data = [
            'title' => $this->request->post->get('title', '', 'string'),
            'data' => $this->request->post->get('data', '', 'string'),
            'tags' => $this->request->post->get('tags', [], 'array'),
            'assignee' => $this->request->post->get('assignee', [], 'array'),
            'notice' => $this->request->post->get('notice', '', 'string'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'locked_at' => date('Y-m-d H:i:s'),
            'locked_by' => $this->user->get('id'),
        ];
        
        $save_close = $this->request->post->get('save_close', '', 'string');

        $newId = $this->NoteTableModel->add($data);
        if (!$newId)
        {
            $this->session->setform('note_table', $data);
            $this->session->set('flashMsg', 'Create failed.'. $this->NoteTableModel->getError()); 
            return $this->app->redirect(
                $this->router->url('new-note/table')
            );
        }

        $try = $this->HistoryModel->add([
            'object' => 'note',
            'object_id' => $newId,
            'data' => $data['data'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
        ]);

        $this->session->set('flashMsg', 'Create Successfully'); 
        $link_back_note = $this->session->get('link_back_note', 'my-filter/my-notes');
         
        $link = $this->router->url( $save_close ? $link_back_note : 'note/edit/'. $newId);
        return $this->app->redirect(
            $link
        );
    }

    public function update()
    {
        $id = $this->validateID();

        // TODO valid the request input

        if(is_numeric($id) && $id)
        {
            $data = [
                'title' => $this->request->post->get('title', '', 'string'),
                'data' => $this->request->post->get('data', '', 'string'),
                'tags' => $this->request->post->get('tags', [], 'array'),
                'structure' => $this->request->post->get('structure', '', 'string'),
                'assignee' => $this->request->post->get('assignee', [], 'array'),
                'notice' => $this->request->post->get('notice', '', 'string'),
                'id' => $id,
                'locked_at' => date('Y-m-d H:i:s'),
                'locked_by' => $this->user->get('id'),
            ];

            $save_close = $this->request->post->get('save_close', '', 'string');

            $try = $this->NoteTableModel->update($data);
            
            if(!$try)
            {
                $this->session->set('flashMsg', 'Save failed.'. $this->NoteTableModel->getError()); 
                return $this->app->redirect(
                    $this->router->url('note/edit/'. $id)
                );
            }

            $this->HistoryModel->add([
                'object' => 'note',
                'object_id' => $id,
                'data' => $data['data'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->user->get('id'),    
            ]);

            $this->session->set('flashMsg', 'Save successfully');
            $link_back_note = $this->session->get('link_back_note', 'my-filter/my-notes');
            $link = $save_close ? $link_back_note : 'note/edit/'. $id;

            return $this->app->redirect(
                $this->router->url($link)
            );
        }

        $this->session->set('flashMsg', 'Invalid Note');

        return $this->app->redirect(
            $this->router->url($this->session->get('link_back_note', 'notes'))
        );
    }

    public function delete()
    {
        $ids = $this->validateID();

        $count = 0;
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->NoteTableModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NoteTableModel->remove($ids ) )
            {
                $count++;
            }
        }


        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url($this->session->get('link_back_note', 'notes')),
        );
    }

    public function validateID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid note');
            return $this->app->redirect(
                $this->router->url($this->session->get('link_back_note', 'notes')),
            );
        }

        return $id;
    }
}