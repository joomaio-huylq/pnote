<?php

namespace App\pnote\share_note\models;

use SPT\Container\Client as Base;

class ShareUserModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function search($search)
    {
        $where = [];

        if( !empty($search) )
        {
            $where[] = "(`name` LIKE '%".$search."%' )";
            $where[] = "(`username` LIKE '%".$search."%' )";
            $where[] = "(`email` LIKE '%".$search."%' )";
        }

        $data = $this->UserEntity->list(0,100, $where);

        return $data;
    }

    public function convert($data, $check = true)
    {
        if ($check)
        {
            if (!is_array($data))
            {
                $this->error = 'Invalid data format';
                return false;
            }

            $users = [];
            $groups = [];
            foreach($data as $item)
            {
                if(strpos($item, 'user_') !== false)
                {
                    $users[] = str_replace('user_', '', $item);
                }
                if(strpos($item, 'group_') !== false)
                {
                    $groups[] = str_replace('group_', '', $item);
                }
            }
            $users = implode('),(', $users);
            $users = $users ? '('. $users .')' : '';
            $groups = implode('),(', $groups);
            $groups = $groups ? '('. $groups .')' : '';

            return [
                'users' => $users,
                'groups' => $groups
            ];
        }

        if (!is_string($data))
        {
            return [];
        }

        if($data)
        {
            $data = str_replace(['(', ')'], '', $data);
            $data = explode(',', $data);
            return $data;
        }
        
        return [];
    }
}
