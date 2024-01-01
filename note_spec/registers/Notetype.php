<?php

namespace App\pnote\note_spec\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'spec' => [
                'namespace' => 'App\pnote\note_spec\\',
                'model' => 'NoteSpecModel',
                'title' => 'Spec'
            ]
        ];
    }
}
