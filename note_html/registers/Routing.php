<?php

namespace App\pnote\note_html\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'history/note-html' => [
                'fnc' => [
                    'get' => 'note_html.history.detail',
                    'post' => 'note_html.history.rollback',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
