<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace App\pnote\note_upload\controllers;

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

    public function add()
    {
        //check title sprint
        $data = [
            'title' => $this->request->post->get('title', '', 'string'),
            'tags' => $this->request->post->get('tags', [], 'array'),
            'assignee' => $this->request->post->get('assignee', [], 'array'),
            'file' => $this->request->file->get('file', [], 'array'),
            'notice' => $this->request->post->get('notice', '', 'string'),
        ];
        
        $save_close = $this->request->post->get('save_close', '', 'string');
        
        $newId = $this->NoteFileModel->add($data);
        if (!$newId)
        {
            $this->session->setform('note_upload', $data);
            $this->session->set('flashMsg', 'Error: '. $this->NoteFileModel->getError()); 
            return $this->app->redirect(
                $this->router->url('new-note/upload')
            );
        }

        $this->session->set('flashMsg', 'Create Successfully'); 
        $link = $save_close ? $this->router->url($this->session->get('link_back_note', 'notes')) : $this->router->url('note/edit/'. $newId);
        return $this->app->redirect(
            $link
        );
    }

    public function update()
    {
        $id = $this->NoteFileModel->getCurrentId();

        // TODO valid the request input

        if(is_numeric($id) && $id)
        {
            $data = [
                'title' => $this->request->post->get('title', '', 'string'),
                'tags' => $this->request->post->get('tags', [], 'array'),
                'assignee' => $this->request->post->get('assignee', [], 'array'),
                'file' => $this->request->file->get('file', [], 'array'),
                'notice' => $this->request->post->get('notice', '', 'string'),
                'id' => $id,
            ];

            $save_close = $this->request->post->get('save_close', '', 'string');

            $try = $this->NoteFileModel->update($data);
            
            if(!$try)
            {
                $this->session->set('flashMsg', 'Error: '. $this->NoteFileModel->getError()); 
                return $this->app->redirect(
                    $this->router->url('note/edit/'. $id)
                );
                
            }
            $this->session->set('flashMsg', 'Updated successfully');
            $link = $save_close ? $this->session->get('link_back_note', 'notes') : 'note/edit/'. $id;

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
                if( $this->NoteModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NoteModel->remove($ids ) )
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
        $id = $urlVars ? (int) $urlVars['id'] : 0;

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