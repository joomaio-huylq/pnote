<?php
namespace App\pnote\note_spec\controllers;

use SPT\Web\ControllerMVVM;

class child extends ControllerMVVM
{
    public function save()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
        $parent_id = $this->request->post->get('parent_id', '', 'string');

        $data = [
            'title' => $this->request->post->get('note_title', '', 'string'),
            'data' => $this->request->post->get('note_data', '', 'string'),
            'tags' => $this->request->post->get('tags', [], 'array'),
            'notice' => $this->request->post->get('note_notice', '', 'string'),
            'status' => 1,
            'note_ids' => $parent_id ? "($parent_id)" : '',
            'id' => $id,
            'locked_at' => date('Y-m-d H:i:s'),
            'locked_by' => $this->user->get('id'),
        ];

        $try = $this->NoteHtmlModel->update($data);
        if(!$try)
        {
            $this->app->set('format', 'json');
            $this->set('status' , 'failed');
            $this->set('note' , $data);
            $this->set('message' , 'Create failed.'. $this->NoteHtmlModel->getError());
            return;
        }

        $this->HistoryModel->add([
            'object' => 'note',
            'object_id' => $id,
            'data' => $data['data'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),    
        ]);
       
        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('note' , $data);
        $this->set('message' , '');
        return;
    }

    public function detail()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $note = $this->NoteHtmlModel->getDetail($id);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('note' , $note);
        $this->set('message' , '');
        return;
    }

    public function delete()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $try = $this->NoteHtmlModel->remove($id);
        $status = $try ? 'success' : 'failed';
        $message = $this->NoteHtmlModel->getError();

        $this->app->set('format', 'json');
        $this->set('status' , $status);
        $this->set('message' , $message);
        return;
    }

    public function loadPosition()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $structure = $this->request->post->get('structure', '', 'string');
        $removes = $this->request->post->get('removes', '', 'string');
        $data = [
            'structure' => $structure ? json_decode($structure, true) : [],
            'removes' => $removes ? json_decode($removes, true) : [],
            'root_id' => $id,
        ];

        $try =  $this->NoteSpecModel->updateStructure($data);
        
        if( !$try )
        {
            $message = $this->NoteSpecModel->getError();
            $status = 'failed';
        }
        else
        {
            $status = 'success';
            $message = '';
        }

        $this->app->set('format', 'json');
        $this->set('status' , $status);
        $this->set('message' , $message);

        return;
    }

    public function document()
    {
        $this->app->set('layout', 'backend.quick_view');
        $this->app->set('page', 'backend-full');
        $this->app->set('format', 'html');
        return;
    }

    public function search()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
        $search = trim($this->request->post->get('search', '', 'string'));
        $ignore = $this->NoteSpecModel->getIgnore($id);

        $ignore = implode(',', $ignore);

        $list = $this->NoteModel->searchAjax($search, $ignore, '');

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }
}