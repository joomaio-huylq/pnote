<?php

namespace App\pnote\note_table\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'note-table/search' => [
                'fnc' => [
                    'post' => 'note_table.child.search',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
