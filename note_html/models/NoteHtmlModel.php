<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace App\pnote\note_html\models;

use SPT\Container\Client as Base;

class NoteHtmlModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function replaceContent($content, $encode = true)
    {
        $replace = $encode ? '_sdm_app_domain_' : $this->router->url();
        $search = $encode ? $this->router->url() : '_sdm_app_domain_';
        
        $content = str_replace($search, $replace, $content);

        return $content;
    }

    public function add($data)
    {
        $data['data'] = $this->replaceContent($data['data']);
        $data['tags'] = isset($data['tags']) ? $this->TagModel->convert($data['tags']) : '';
        $convert = isset($data['assignee']) ? $this->ShareUserModel->convert($data['assignee']) : [];
        $data['assignee'] = isset($convert['users']) ? $convert['users'] : '';
        $data['assign_user_group'] = isset($convert['groups']) ? $convert['groups'] : '';
        $data = [
            'title' => $data['title'],
            'public_id' => '',
            'alias' => '',
            'data' => $data['data'],
            'tags' => $data['tags'],
            'assignee' => $data['assignee'],
            'assign_user_group' => $data['assign_user_group'],
            'type' => 'html',
            'note_ids' => isset($data['note_ids']) ? $data['note_ids'] : '',
            'notice' => isset($data['notice']) ? $data['notice'] : '',
            'status' => isset($data['status']) ? $data['status'] : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'locked_at' => date('Y-m-d H:i:s'),
            'locked_by' => $this->user->get('id'),
        ];

        $note = $this->NoteEntity->bind($data);
        
        if (!$note)
        {
            $this->error = $this->NoteEntity->getError();
            return false;
        }


        $newId =  $this->NoteEntity->add($note);
        if (!$newId)
        {
            $this->error = $this->NoteEntity->getError();
            return false;
        }

        return $newId;
    }

    public function update($data)
    {
        $data['data'] = $this->replaceContent($data['data']);
        $data['tags'] = isset($data['tags']) ? $this->TagModel->convert($data['tags']) : '';
        $convert = isset($data['assignee']) ? $this->ShareUserModel->convert($data['assignee']) : [];
        $data['assignee'] = isset($convert['users']) ? $convert['users'] : '';
        $data['assign_user_group'] = isset($convert['groups']) ? $convert['groups'] : '';
        
        $data = [
            'title' => $data['title'],
            'data' => $data['data'],
            'tags' => $data['tags'],
            'assignee' => $data['assignee'],
            'assign_user_group' => $data['assign_user_group'],
            'note_ids' => isset($data['note_ids']) ? $data['note_ids'] : '',
            'type' => 'html',
            'notice' => isset($data['notice']) ? $data['notice'] : '',
            'status' => isset($data['status']) ? $data['status'] : 0,
            'id' => $data['id'],
        ];

        $note = $this->NoteEntity->bind($data);
        
        if (!$note)
        {
            $this->error = $this->NoteEntity->getError();
            return false;
        }

        $try =  $this->NoteEntity->update($note);
        if (!$try)
        {
            $this->error = $this->NoteEntity->getError();
            return false;
        }

        return $try;
    }

    public function remove($id)
    {
        if (!$id)
        {
            $this->error = 'Invalid note!';
            return false;
        }

        $try = $this->NoteEntity->remove($id);
        return $try;
    }

    public function getDetail($id)
    {
        if (!$id)
        {
            $find = $this->NoteEntity->findOne(['status' => '-1', 'created_by' => $this->user->get('id'), 'type' => 'html']);
            if (!$find)
            {
                $find = [
                    'title' => 'html',
                    'public_id' => '',
                    'alias' => '',
                    'data' => '',
                    'tags' => '',
                    'type' => 'html',
                    'note_ids' => '',
                    'status' => -1,
                    'notice' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user->get('id'),
                    'locked_at' => date('Y-m-d H:i:s'),
                    'locked_by' => $this->user->get('id'),
                ];
                
                $try = $this->NoteEntity->add($find);

                if (!$try)
                {
                    $this->error = 'Can`t create default note';
                    return false;
                }

                $find['id'] = $try;
            }

            $find['title'] = '';
            return $find;
        }

        $note = $this->NoteEntity->findByPK($id);
        if (!$note)
        {
            return [];
        }

        $note['data'] = $this->replaceContent($note['data'], false);
        return $note;
    }

    public function rollback($id)
    {
        $history = $this->HistoryModel->detail($id);
        if (!$history)
        {
            return false;
        }
        
        $find_note = $this->NoteEntity->findOne(['id' => $history['object_id']]);
        if (!$find_note)
        {
            return false;
        }

        $find_note['data'] = $history['data'];

        $try = $this->NoteEntity->update($find_note);

        if ($try)
        {
            $remove_list = $this->HistoryEntity->list(0, 0, ['id > '. $id, 'object_id = '. $history['object_id'], 'object' => 'note']);
            if ($remove_list)
            {
                foreach($remove_list as $item)
                {
                    $this->HistoryEntity->remove($item['id']);
                } 
            }
        }
        
        return $try ? $find_note['id'] : false;
    }
}
