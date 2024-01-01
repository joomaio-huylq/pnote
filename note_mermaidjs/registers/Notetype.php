<?php

namespace App\pnote\note_mermaidjs\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'mermaidjs' => [
                'namespace' => 'App\pnote\note_mermaidjs\\',
                'title' => 'Mermaid Js'
            ]
        ];
    }
}
