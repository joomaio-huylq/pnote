<?php
namespace App\pnote\share_note\models;

use SPT\Container\Client as Base;

class PermissionShareModel extends Base
{
    private $groups;

    public function checkPermission($assign_user_group)
    {
        if (!$this->groups)
        {
            $this->groups = $this->UserEntity->getGroups($this->user->get('id'));
        }

        if(!is_array($assign_user_group))
        {
            $assign_user_group = $this->ShareGroupModel->convert($assign_user_group, false);
        }

        foreach($this->groups as $group)
        {
            if(in_array($group['group_id'], $assign_user_group))
            {
                return true;
            }
        }

        return false;
    }
}