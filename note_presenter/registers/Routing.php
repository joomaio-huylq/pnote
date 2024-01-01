<?php

namespace App\pnote\note_presenter\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'history/note-presenter' => [
                'fnc' => [
                    'get' => 'note_presenter.history.detail',
                    'post' => 'note_presenter.history.rollback',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
