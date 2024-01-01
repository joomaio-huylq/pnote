<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace App\pnote\share_note\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class ShareNote extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'backend.javascript',
                'backend.share_note',
            ],
        ];
    }
    
    public function javascript()
    {
        return [
            'link_assignee' => $this->router->url('user/search'),
        ];
    }

    public function share_note($layoutData, $viewData)
    {
        $data = isset($viewData['data']) ? $viewData['data'] : [];
        $assign_user = isset($data['assignee']) ? $data['assignee'] : '';

        $assign_user = $this->ShareUserModel->convert($assign_user, false);
        if (!$assign_user)
        {
            $assign_user = [];
        }

        $users = $this->UserEntity->list(0, 0, []);
        $user_groups = $this->GroupEntity->list(0,0, []);
        
        $assign_user_group = isset($data['assign_user_group']) ? $data['assign_user_group'] : '';
        $assign_user_group = $this->ShareGroupModel->convert($assign_user_group, false);
        if (!$assign_user_group)
        {
            $assign_user_group = [];
        }

        $filter_name = $this->request->get->get('filter', '');
        if($filter_name)
        {
            $filter = $this->CollectionModel->checkFilterName($filter_name);
            $assignment = $this->CollectionModel->convertArray($filter['assignment'], false);
            $user_tmp = $group_tmp = [];
            foreach($assignment as $item)
            {
                $tmp = explode('-', $item);
                $id = end($tmp);
                if(strpos($item, 'user') !== false)
                {
                    $user_tmp[] = $id;
                }
                else
                {
                    $group_tmp[] = $id;
                }
            }

            $assign_user = $assign_user ? $assign_user : $user_tmp;
            $assign_user_group = $assign_user_group ? $assign_user_group : $group_tmp;
        }

        return [
            'assign_user' => $assign_user,
            'assign_user_group' => $assign_user_group,
            'users' => $users,
            'user_groups' => $user_groups,
        ];
    }
}
