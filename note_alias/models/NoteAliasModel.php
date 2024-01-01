<?php
namespace App\pnote\note_alias\models;

use SPT\Container\Client as Base;

class NoteAliasModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function add($note_id)
    {
        // check note exits
        if (!$note_id)
        {
            $this->error = 'Invalid note';
            return false;
        }

        $note = $this->NoteEntity->findByPK($note_id);
        if(!$note)
        {
            $this->error = 'Note not founded';
            return false;
        }

        $data = [
            'title' => $note['title'],
            'public_id' => '',
            'alias' => '',
            'data' => $note_id,
            'tags' => '',
            'assignee' => '',
            'assign_user_group' => '',
            'type' => 'alias',
            'notice' => '',
            'status' => -1,
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

        $newId = $this->NoteEntity->add($note);

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
        $note = [
            'title' => $data['title'],
            'data' => $data['data'],
            'tags' => $data['tags'],
            'assignee' => $data['assignee'],
            'assign_user_group' => $data['assign_user_group'],
            'type' => 'spec',
            'notice' => isset($data['notice']) ? $data['notice'] : '',
            'status' => isset($data['status']) ? $data['status'] : 0,
            'id' => $data['id'],
        ];

        $note = $this->NoteEntity->bind($note);
        
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

        // save structure
        $data['root_id'] = $data['id'];
        $try = $this->updateStructure($data);
        if (!$try)
        {
            $this->error = 'Save Structure Failed';
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

        // remove tree note
        $ids = $this->TreeNoteEntity->list(0, 0, ['root_id ='. $id]);
        foreach($ids as $item)
        {
            $this->TreeNoteEntity->remove($item['id']);
        }

        // remove note relate
        $ids = $this->NoteEntity->list(0, 0, ["note_ids LIKE '%($id)%'"]);
        foreach($ids as $item)
        {
            $this->NoteEntity->remove($item['id']);
        }

        $try = $this->NoteEntity->remove($id);
        return $try;
    }

    public function getDetail($id)
    {
        if (!$id)
        {
            $find = $this->NoteEntity->findOne(['status' => '-1', 'created_by' => $this->user->get('id'), 'type' => 'spec']);
            if (!$find)
            {
                $find = [
                    'title' => 'Spec',
                    'public_id' => '',
                    'alias' => '',
                    'data' => '',
                    'tags' => '',
                    'type' => 'spec',
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

            $list_tree = $this->getTree($find['id']);
            $find['list_tree'] = $list_tree ? $list_tree : [];
            $find['title'] = '';
            return $find;
        }

        $note = $this->NoteEntity->findByPK($id);
        if (!$note)
        {
            return [];
        }

        $list_tree = $this->getTree($note['id']);
        $note['list_tree'] = $list_tree ? $list_tree : [];
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
