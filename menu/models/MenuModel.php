<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace App\pnote\menu\models;

use SPT\Container\Client as Base;

class MenuModel extends Base
{ 
    use \SPT\Traits\ErrorString;
    private $menu;

    public function getItems()
    {
        if ($this->menu) return $this->menu;

        $menu = [];
        $this->app->plgLoad('menu', 'registerItem', function($items) use (&$menu){
            if (is_array($items) && $items)
            {
                $order = 1;
                if (isset($items['order']))
                {
                    $order = $items['order'];
                    unset($items['order']);
                }
                foreach($items as $key => $item)
                {
                    $menu[$key][$order] = array_merge($menu[$key][$order] ?? [], $item);
                }
                
            }
        });

        $menu_type = $this->app->get('menu_type', 'menu');
        $menu = isset($menu[$menu_type]) ? $menu[$menu_type] : [];
        ksort($menu);

        $menu_sidebar = [];
        foreach($menu as $item)
        {
            $menu_sidebar = array_merge($menu_sidebar, $item);
        }

        return  $menu_sidebar;
    }

    public function getShortcuts()
    {
        if ($this->menu) return $this->menu;

        $menu = [];
        $this->app->plgLoad('menu', 'registerShortcut', function($items) use (&$menu){
            if (is_array($items) && $items)
            {
                $order = 1;
                if (isset($items['order']))
                {
                    $order = $items['order'];
                    unset($items['order']);
                }
                foreach($items as $key => $item)
                {
                    $menu[$key][$order] = array_merge($menu[$key][$order] ?? [], $item);
                }
                
            }
        });

        $menu_type = 'menu';
        $menu = isset($menu[$menu_type]) ? $menu[$menu_type] : [];
        ksort($menu);

        $menu_sidebar = [];
        foreach($menu as $item)
        {
            $menu_sidebar = array_merge($menu_sidebar, $item);
        }

        return  $menu_sidebar;
    }
}
