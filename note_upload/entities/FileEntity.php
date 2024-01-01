<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace App\pnote\note_upload\entities;

use SPT\Storage\DB\Entity;

class FileEntity extends Entity
{
    protected $table = '#__note_upload';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'int',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'note_id' => [
                    'type' => 'int',
                    'option' => 'unsigned'
                ],
                'path' => [
                    'type' => 'text'
                ],
                'file_type' => [
                    'type' => 'varchar',
                    'limit' => 45,
                ]
        ];
    }

    public function list( $start, $limit, array $where = [], $order = '', $select = '*')
    {
        $list = $this->db->select( 'notes.*, note_uploads.note_id as note_id, note_uploads.path as path, note_uploads.file_type as file_type' )
                            ->table( $this->table . ' as note_uploads' )
                            ->join('INNER JOIN #__notes as notes ON notes.id = note_uploads.note_id');
        if( count($where) )
        {
            $list->where( $where );
        }

        if($order)
        {
            $list->orderby($order);
        }

        return $list->countTotal(true)->list( $start, $limit);
    }

    public function validate( $data )
    {
        if (!is_array($data))
        {
            $this->error = 'Invalid data format! ';
            return false;
        }

        if (!isset($data['note_id']) || !$data['note_id'] || !$data)
        {
            $this->error = 'Invalid note';
            return false;
        }
        
        if (!isset($data['path']) || !$data['path'] || !$data)
        {
            $this->error = 'Path is required! ';
            return false;
        }

        unset($data['readyUpdate']);
        unset($data['readyNew']);
        
        return $data;
    }

    public function bind($data = [], $returnObject = false)
    {
        $row = [];
        $data = (array) $data;
        $fields = $this->getFields();
        $skips = isset($data['id']) && $data['id'] ? [] : ['id'];
        foreach ($fields as $key => $field)
        {
            if (!in_array($key, $skips))
            {
                $default = isset($field['default']) ? $field['default'] : '';
                $row[$key] = isset($data[$key]) ? $data[$key] : $default;
            }
        }

        if (isset($data['id']) && $data['id'])
        {
            $row['readyUpdate'] = true;
        }
        else{
            $row['readyNew'] = true;
        }

        return $returnObject ? (object)$row : $row;
    }
}