<?php
namespace App\pnote\note_spec\controllers;

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
        $this->app->set('format', 'html');
    }

    public function update()
    {
        $id = $this->validateID();

        // TODO valid the request input

        if(is_numeric($id) && $id)
        {
            $structure = $this->request->post->get('structure', '', 'string');
            $removes = $this->request->post->get('removes', '', 'string');
    
            $data = [
                'title' => $this->request->post->get('title', '', 'string'),
                'data' => $this->request->post->get('data', '', 'string'),
                'structure' => $structure ? json_decode($structure, true) : [],
                'removes' => $removes ? json_decode($removes, true) : [],
                'tags' => $this->request->post->get('tags', [], 'array'),
                'assignee' => $this->request->post->get('assignee', [], 'array'),
                'notice' => $this->request->post->get('notice', '', 'string'),
                'id' => $id,
            ];

            $save_close = $this->request->post->get('save_close', '', 'string');
            $try = $this->NoteSpecModel->update($data);
            
            if(!$try)
            {
                $this->session->set('flashMsg', 'Save failed.'. $this->NoteSpecModel->getError()); 
                return $this->app->redirect(
                    $this->router->url('note/edit/'. $id)
                );
            }

            $try = $this->HistoryModel->add([
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
                if( $this->NoteSpecModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NoteSpecModel->remove($ids ) )
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