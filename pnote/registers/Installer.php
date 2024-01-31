<?php
namespace App\pnote\pnote\registers;

use SPT\Application\IApp;

class Installer
{
    public static function info()
    {
        return ['tags'=>['sdm']];
    }
    
    public static function name()
    {
        return 'Plugin PNote';
    }

    public static function detail()
    {
        return [
            'author' => 'Pham Minh',
            'created_at' => '2023-01-03',
            'description' => 'Plugin used to demo how the SPT works'
        ];
    }

    public static function version()
    {
        return '0.0.1';
    }

    public static function createSuperUser($is_cli=false)
    {
        if ($is_cli) {
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
        }
        return [
            'widget' => 'pnote::createSuperUser'
        ];
    }
    
    public static function install( IApp $app)
    {
        // run sth to prepare the install
    }
    public static function uninstall( IApp $app)
    {
        // run sth to uninstall
    }
    public static function active( IApp $app)
    {
        // run sth to prepare the install
    }
    public static function deactive( IApp $app)
    {
        // run sth to uninstall
    }
}