<?php
namespace App\pnote\pnote\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class PNoteModel extends Base
{ 
    use ErrorString; 
    public function countMyNote()
    {
        $list = $this->NoteEntity->list(0,0, ['created_by' => $this->user->get('id'), 'status > -1']);
        return $this->NoteEntity->getListTotal();
    }

    public function countMyFilter()
    {
        $list = $this->CollectionEntity->list(0, 0, ['user_id' => $this->user->get('id')]);
        return $this->CollectionEntity->getListTotal();
    }

    public function countMyShared()
    {
        $where = ['created_by Not LIKE '. $this->user->get('id'), 'status > -1', '(assign_user <> "" OR assign_user_group <> "")'];

        $list = $this->NoteEntity->list(0,0, $where);
        return $this->NoteEntity->getListTotal();
    }

    public function countShare()
    {
        $where = ['created_by Not LIKE '. $this->user->get('id'), 'status > -1'];
        $where_permission = [];
        $where_permission[] = "(`assign_user` LIKE '%(" . $this->user->get('id') . ")%')";

        $groups = $this->UserEntity->getGroups($this->user->get('id'));
        foreach($groups as $group)
        {
            $where_permission[] = "(`assign_user_group` LIKE '%(" . $group['group_id'] . ")%')";
        }

        $where[] = '('. implode(" OR ", $where_permission) . ')';

        $list = $this->NoteEntity->list(0, 0, $where);
        return $this->NoteEntity->getListTotal();

    }
}
