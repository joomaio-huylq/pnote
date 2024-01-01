<?php

namespace App\pnote\note_spec\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'note-spec/child' => [
                'fnc' => [
                    'get' => 'note_spec.child.detail',
                    'post' => 'note_spec.child.save',
                    'delete' => 'note_spec.child.delete',
                ],
                'parameters' => ['id'],
            ],
            'note-spec/update-position' => [
                'fnc' => [
                    'post' => 'note_spec.child.loadPosition',
                ],
                'parameters' => ['id'],
            ],
            'note-spec/load-document' => [
                'fnc' => [
                    'get' => 'note_spec.child.document',
                ],
                'parameters' => ['id'],
            ],
            'note-spec/search' => [
                'fnc' => [
                    'post' => 'note_spec.child.search',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
