<?php

namespace App\pnote\share_note\registers;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'user/search' => [
                'fnc' => [
                    'get' => 'share_note.ajax.search',
                ],
            ],
        ];
    }
}
