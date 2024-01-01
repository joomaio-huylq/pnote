<?php

namespace App\pnote\share_note\models;

use SPT\Container\Client as Base;

class ShareGroupModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function search($search)
    {
        $where = [];

        if( !empty($search) )
        {
            $where[] = "(`name` LIKE '%".$search."%' )";
        }

        $data = $this->GroupEntity->list(0,100, $where);

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

            $data = implode('),(', $data);
            $data = $data ? '('. $data .')' : '';
            return $data;
        }

        if (!is_string($data))
        {
            return [];
        }

        $data = str_replace(['(', ')'], '', $data);
        $data = explode(',', $data);
        return $data;
    }
}
