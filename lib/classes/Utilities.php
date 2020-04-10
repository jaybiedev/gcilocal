<?php

class Utilities {

    public static function replaceTags($content) {

        $content = str_replace("[menu]", self::getMenuHtml(), $content);

        return $content;
    }

    private static function getMenuHtml() {
        ob_start();
        wp_nav_menu( array(
                'menu'  => 'Menu',
                'menu_id'=> 'menu_tree',
                'container' => false
            )
        );
        $menu = ob_get_contents();
        ob_end_clean();

        return $menu;
    }
    
}
