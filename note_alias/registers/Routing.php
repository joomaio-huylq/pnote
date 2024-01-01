<?php

namespace App\pnote\note_alias\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'new-alias' => [
                'fnc' => [
                    'post' => 'note_alias.note.add',
                ]
            ],
        ];
    }
}
