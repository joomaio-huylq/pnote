<?php

namespace App\pnote\note_mermaidjs\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'history/note-mermaid' => [
                'fnc' => [
                    'get' => 'note_mermaidjs.history.detail',
                    'post' => 'note_mermaidjs.history.rollback',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
