<?php

namespace App\pnote\note_attachment\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'note/attachment/delete' => [
                'fnc' => [
                    'delete' => 'note_attachment.ajax.delete',
                ],
                'parameters' => ['id'],
            ],
            'note/attachment' => [
                'fnc' => [
                    'get' => 'note_attachment.ajax.list',
                    'post' => 'note_attachment.ajax.add',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
