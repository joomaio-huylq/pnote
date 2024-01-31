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

    public function createSuperUser($data)
    {
        $result = array(
            'success' => false,
            'message' => '',
        );

        $super_user_groups = [];
        $user_groups = $this->GroupEntity->list(0, 0, []);
        foreach($user_groups as $group)
        {
            if (str_contains($group['access'], 'user_manager'))
            {
                $super_user_groups[] = $group['id'];
            }
        }

        if (count($super_user_groups) == 0) {
            $access = $this->PermissionModel->getAccess();

            // Create group
            $group = [
                'name' => 'Super',
                'description' => 'Super Group',
                'access' => json_encode($access),
                'status' => 1,
                'created_by' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => 0,
                'modified_at' => date('Y-m-d H:i:s')
            ];
    
            $created_group = $this->GroupEntity->add($group);

        
            if (!$created_group)
            {
                $result['message'] = 'Create Group Failed';
                return $result;
            }
        }

        $super_users = $this->UserGroupEntity->list(0, 0, ['group_id IN (' . implode(',', $super_user_groups) . ')']);

        if (count($super_user_groups) == 0 || count($super_users) == 0)
        {
            $id = isset($data['id']) ? $data['id'] : 0;
            $password = isset($data['password']) ? $data['password'] : '';
            $confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';
            $username = $data['username'];
            $email = $data['email'];
            $name = $data['name'];

            $id = isset($data['id']) ? $data['id'] : 0;
            if(!empty($password)) 
            {
                if (strlen($password) < '6') 
                {
                    $result['message'] = "Your Password Must Contain At Least 6 Characters!";
                    return $result;
                }

                if ($password != $confirm_password)
                {
                    $result['message'] = 'Confirm Password Invalid';
                    return $result;
                }
            } 
            else 
            {
                $result['message'] = "Passwords can't empty";
                return $result;
            }

            // validate user name
            if(!empty($username)) 
            {
                $find = $this->UserEntity->findOne(['username' => $username]);
                if ($find && $find['id'] != $id)
                {
                    $result['message'] = "Username already exists";
                    return $result;
                }
            } 
            else 
            {
                $result['message'] = "UserName cant't empty";
                return $result;
            }

            //validate email
            if(!empty($email)) {
                $findEmail = $this->UserEntity->findOne(['email' => $email]);
                if ($findEmail && $findEmail['id'] != $id)
                {
                    $result['message'] = "Email already exists";
                    return $result;
                }
            } else {
                $result['message'] = "Email can't empty";
                return $result;
            }

            $user = [
                'username' => $username,
                'name' => $name,
                'email' => $email,
                'status' => 1,
                'password' =>  $password,
                'confirm_password' =>  $confirm_password,
                'created_by' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => 0,
                'modified_at' => date('Y-m-d H:i:s')
            ];

            $created_user = $this->UserEntity->add($user);
            if (!$created_user)
            {
                $result['message'] = 'Create User Failed';
                return $result;
            }

            if (count($super_user_groups) == 0) {
                $group_id = $created_group;
            } else {
                $group_id = $super_user_groups[0];
            }
    
            $created_user_group = $this->UserGroupEntity->add([
                'group_id' => $group_id,
                'user_id' => $created_user,
            ]); 
    
            if (!$created_user_group)
            {
                $result['message'] = 'Create User Group Failed';
                return $result;
            }
        }

        $result['success'] = true;
        $result['message'] = 'Create Super User Successfully';
        return $result;
    }

    public function createSuperUserCli()
    {
        $super_user_groups = [];
        $user_groups = $this->GroupEntity->list(0, 0, []);
        foreach($user_groups as $group)
        {
            if (str_contains($group['access'], 'user_manager'))
            {
                $super_user_groups[] = $group['id'];
            }
        }

        if (count($super_user_groups) == 0) {
            $access = $this->PermissionModel->getAccess();

            // Create group
            $group = [
                'name' => 'Super',
                'description' => 'Super Group',
                'access' => json_encode($access),
                'status' => 1,
                'created_by' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => 0,
                'modified_at' => date('Y-m-d H:i:s')
            ];
    
            $created_group = $this->GroupEntity->add($group);
        
            if (!$created_group)
            {
                $this->error = 'Create Group Failed';
                return false;
            }
        }

        $super_users = $this->UserGroupEntity->list(0, 0, ['group_id IN (' . implode(',', $super_user_groups) . ')']);

        if (count($super_user_groups) == 0 || count($super_users) == 0)
        {
            echo "Plugin Pnote requires to create super admin. \n";
            $enter_info = true;
            while ($enter_info) 
            {
                $name = readline("Enter your name:\n");
                $username = readline("Enter your username:\n");
                $email = readline("Enter your email:\n");
                $password = readline("Enter your password (At least 6 characters):\n");

                $user = [
                    'username' => $username,
                    'name' => $name,
                    'email' => $email,
                    'status' => 1,
                    'password' => $password,
                    'confirm_password' => $password,
                    'created_by' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'modified_by' => 0,
                    'modified_at' => date('Y-m-d H:i:s')
                ];

                $validate = $this->UserEntity->validate($user);
                if (!$validate) {
                    echo "Information you entered is invalid. Please enter again! \n";
                } else {
                    $enter_info = false;
                }
            }

            $created_user = $this->UserEntity->add($user);
            if (!$created_user)
            {
                $this->error = 'Create User Failed';
                return false;
            }

            if (count($super_user_groups) == 0) {
                $group_id = $created_group;
            } else {
                $group_id = $super_user_groups[0];
            }
    
            $created_user_group = $this->UserGroupEntity->add([
                'group_id' => $group_id,
                'user_id' => $created_user,
            ]); 
    
            if (!$created_user_group)
            {
                $this->error = 'Create User Group Failed';
                return false;
            }
            
        }
        return true;
    }
}
