<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

/**
 *	Events plugin
 *
 *  Provides CRUD for events and different output possibilities for event lists
 *
 *	@package    Monstra
 *  @subpackage Plugins
 *	@author     Andreas Müller | devmount <mail@devmount.de>
 *	@license    MIT
 *	@version    0.1.2016-01-02
 *  @link       https://github.com/devmount-monstra/events
 *
 */


// Register plugin
Plugin::register(
    __FILE__,                    
    __('Events'),
    __('Events manager for Monstra.'),  
    '0.1.2016-01-02',
    'devmount',                 
    'http://devmount.de'
);

// Include plugin admin
if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {
    Plugin::Admin('events');
}


/**
 * Shortcode: {events click="some link text" toggle="some toggle content"}
 */
Shortcode::add('events', 'Events::_shortcode');


/**
 * Add CSS and JavaScript
 */
Action::add('theme_footer', 'Events::_insertJS');
Action::add('theme_header', 'Events::_insertCSS');


/**
 * Events class
 * 
 * Usage: <?php Events::show('What is life, the universe and everything?', '42'); ?>
 * 
 */
class Events
{
    /**
     * _shortcode function
     * 
     * @param  array $attributes given
     * @return void generated content
     * 
     */
    public static function _shortcode($attributes)
    {
        return Events::show($attributes['click'], $attributes['toggle']);
    }

    /**
     * _insertJS function
     * 
     * @return JavaScript to insert
     * 
     */
    public static function _insertJS()
    {
        echo '<script type="text/javascript">
            $(document).ready(function(){
                $(".click").click(function(event){
                    /* set configuration */
                    $(this).children(".toggle").slideToggle(
                        ' . Option::get('toggle_duration') . ',
                        "' . Option::get('toggle_easing') . '"
                    );
                });
            });
        </script>';
    }


    /**
     * _insertCSS function
     * 
     * @return JavaScript to insert
     * 
     */
    public static function _insertCSS()
    {
        echo '<link rel="stylesheet" type="text/css" href="' . Option::get('siteurl') . '/plugins/toggle/css/toggle.css" />';
    }
     
    /**
     * Assign to view
     */
    public function show($click, $toggle)
    {
        View::factory('toggle/views/frontend/index')
            ->assign('click', $click)
            ->assign('toggle', $toggle)
            ->display();
    }

}
