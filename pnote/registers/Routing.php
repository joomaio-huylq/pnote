<?php

namespace App\pnote\pnote\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'pnote/create-super-user' => [
                'fnc' => [
                    'post' => 'pnote.pnote.createSuperUser',
                ],
                'restApi' => true,
                'format' => 'json',
            ],
        ];
    }
}
