<?php
namespace App\pnote\note_spec\models;

use SPT\Container\Client as Base;

class NoteSpecModel extends Base
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
            'type' => 'spec',
            'note_ids' => '',
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

    public function remove($id, $hard_delete = false)
    {
        if (!$id)
        {
            $this->error = 'Invalid note!';
            return false;
        }

        // remove note relate
        $ids = $this->NoteEntity->list(0, 0, ["note_ids LIKE '%($id)%'"]);
        foreach($ids as $item)
        {
            if ($item['type'] != 'spec')
            {
                $try = $this->NoteModel->remove($item['id'], $hard_delete);
                if (!$try)
                {
                    $this->error = $this->NoteModel->getError();
                    return false;
                }
            }
            else
            {
                $try = $this->NoteEntity->remove($item['id']);
                if (!$try)
                {
                    $this->error = 'Can`t remove note id '. $item['id'];
                    return false;
                }
            }
        }

        if($hard_delete)
        {
            // remove tree note
            $ids = $this->TreeNoteEntity->list(0, 0, ['root_id ='. $id]);
            foreach($ids as $item)
            {
                $this->TreeNoteEntity->remove($item['id']);
            }
        }

        return true;
    }

    public function restore($id)
    {
        if (!$id)
        {
            $this->error = 'Invalid note!';
            return false;
        }

        // remove note relate
        $ids = $this->NoteEntity->list(0, 0, ["note_ids LIKE '%($id)%'"]);
        foreach($ids as $item)
        {
            if ($item['type'] != 'spec')
            {
                $try = $this->NoteModel->restore($item['id']);
                if (!$try)
                {
                    $this->error = $this->NoteModel->getError();
                    return false;
                }
            }
            else
            {
                $item['status'] = $item['type'] != 'alias' ? 0 : -1;
                $item['deleted_at'] = null;
                $try = $this->NoteEntity->update($item);
                if (!$try)
                {
                    $this->error = 'Can`t restore note id '. $item['id'];
                    return false;
                }
            }
        }

        return true;
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

    // Write your code here
    public function getTree($id)
    {
        $list = $this->TreeNoteEntity->list(0, 0, ['root_id ='.$id], 'tree_left asc');
        $removes = [];
        $index = [];
        foreach($list as &$item)
        {
            if (in_array($item['id'], $removes))
            {
                $this->TreeNoteEntity->remove($item['id']);
                continue;
            }

            $note = $this->NoteEntity->findByPK($item['note_id']);
            if ($note && $note['type'] == 'alias' && $note['status'] != -2)
            {
                $note_id = $note['data'];
                $note = $this->NoteEntity->findByPK($note_id);
            }

            if (!$note || $note['status'] == -2)
            {
                $removes[] = $item['id'];
                $this->TreeNoteEntity->remove($item['id']);
            }
            else
            {
                $item['title'] = $note['title'];
                $note['data'] = $this->replaceContent($note['data'], false);
                $item['note'] = $note;
                if(!$item['parent_id'])
                {
                    $index[$item['note_id']] = $item['tree_position'];
                }
                else
                {
                    $index[$item['note_id']] = isset($index[$item['parent_id']]) ? $index[$item['parent_id']] . '.' . $item['tree_position'] : $item['tree_position'];
                }
                $item['index'] = $index[$item['note_id']];
            }
        }

        if ($removes)
        {
            $this->TreeNoteEntity->rebuild($id);
            $list = $this->getTree($id);
        }
        
        return $list;
    }

    public function updateStructure($data)
    {
        if(!$data || !is_array($data) || !isset($data['structure']) || !is_array($data['structure']) || !isset($data['root_id']))
        {
            return false;
        }

        if (isset($data['removes']) && $data['removes'])
        {
            foreach($data['removes'] as $item)
            {
                $find = $this->TreeNoteEntity->findOne(['note_id = '. $item, 'root_id = '. $data['root_id'] ]);
                if ($find)
                {
                    $this->TreeNoteEntity->remove($find['id']);
                }

                // remove note
                $this->NoteModel->remove($item);
            }
        }

        foreach($data['structure'] as $item)
        {
            $find = $this->TreeNoteEntity->findOne(['note_id = '. $item['id'], 'root_id = '. $data['root_id'] ]);
            if ($find)
            {
                $try = $this->TreeNoteEntity->update([
                    'id' => $find['id'],
                    'tree_position' => $item['id'] ? $item['position'] : 0,
                    'tree_level' => $item['id'] ? $item['level'] : 0,
                    'parent_id' => $item['id'] ? $item['parent'] : 0,
                ]);
            }else{
                $try = $this->TreeNoteEntity->add([
                    'root_id' => $data['root_id'],
                    'note_id' => $item['id'],
                    'tree_position' => $item['id'] ? $item['position'] : 0,
                    'tree_level' => $item['id'] ? $item['level'] : 0,
                    'parent_id' => $item['id'] ? $item['parent'] : 0,
                    'tree_left' => 0,
                    'tree_right' => 0,
                ]);
            }

            // save note_ids
            $note = $this->NoteEntity->findbyPK($item['id']);
            if ($note)
            {
                $note['note_ids'] = '('. $data['root_id']. ')';
                $this->NoteEntity->update($note);
            }

            if (!$try)
            {
                $this->error = 'Structure save failed';
                return false;
            }
        }

        $try = $this->TreeNoteEntity->rebuild($data['root_id']);
        if (!$try)
        {
            $this->error = 'Structure rebuild failed';
            return false;
        }

        return true;
    }

    public function getDocument($structure, $id)
    {
        $structure = !$structure ? $this->getTree($id) : $structure;
        if (!$structure || !is_array($structure))
        {
            return false;
        }

        $index = [];
        foreach($structure as &$item)
        {
            $tmp = null;
            
            if ($item['id'])
            {
                $tmp = $this->NoteHtmlModel->getDetail($item['id']);
                if(!$item['parent'])
                {
                    $index[$item['id']] = $item['position'];
                }
                else
                {
                    $index[$item['id']] = isset($index[$item['parent']]) ? $index[$item['parent']] . '.' . $item['position'] : $item['position'];
                }
                $item['index'] = $index[$item['id']];
            }
            $item['note'] = $tmp;
        }
        
        return $structure;
    }

    public function getIgnore($id)
    {
        if(!$id)
        {
            return [];
        }

        $ignore = [];
        $where = [
            '(note_ids LIKE "('. $id .')" OR `type` = "spec")'
        ];
        $list = $this->NoteEntity->list(0, 0, $where);
        foreach($list as $item)
        {
            $ignore[] = $item['id'];
            if ($item['type'] == 'alias' && $item['data'])
            {
                $ignore[] = $item['data'];
            }
        }

        return $ignore;
    }
}
