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
    __('Event management for Monstra.'),
    '0.1.2016-01-02',
    'devmount',
    'http://devmount.de'
);

// Include plugin admin
if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {
    Plugin::Admin('events');
}

/**
 * Add shortcode
 */
Shortcode::add('events', 'Events::_shortcode');

/**
 * Add CSS and JavaScript
 */
Action::add('theme_footer', 'Events::_insertJS');
Action::add('theme_header', 'Events::_insertCSS');

// register repository classes
require_once 'repositories/repository.events.php';
require_once 'repositories/repository.categories.php';
require_once 'repositories/repository.locations.php';

/**
 * Events class
 *
 * <code>
 *      <?php Events::listEvents('list', 'minimal', 'future', 5, 'ASC'); ?>
 * </code>
 *
 */
class Events
{
    /**
     * Creates shortcodes for content pages
     *
     * <code>
     *      {events type="list" style="minimal" time="future" count="5" order="ASC"}
     * </code>
     *
     * @param  array $attributes given
     * @return void generated content
     *
     */
    public static function _shortcode($attributes)
    {
        switch ($attributes['type']) {
            case 'list':
                return Events::listEvents($attributes['style'], $attributes['time'], $attributes['count'], $attributes['order']);
                break;

            default:
                return Events::error();
                break;
        }
        return Events::error();
    }


    /**
     * _insertJS function
     *
     * @return JavaScript to insert
     *
     */
    public static function _insertJS()
    {
        echo '';
    }


    /**
     * _insertCSS function
     *
     * @return JavaScript to insert
     *
     */
    public static function _insertCSS()
    {
        echo '<link rel="stylesheet" type="text/css" href="' . Option::get('siteurl') . '/plugins/events/css/events.plugin.css" />';
    }


    /**
     * list events
     * 
     * @param string $style     ['extended', 'minimal', 'archive']
     * @param string $time      ['future', 'past', 'all']
     * @param string $count     number of records to show [number or 'all']
     * @param string $order     ['ASC', 'DESC']
     *
     * @return array
     *
     */
    public function listEvents($style, $time = 'all', $count = 'all', $order = 'ASC')
    {
        // load template according to given style
        $template = '';
        $style = trim($style);
        if (in_array($style, array('extended', 'minimal', 'archive'))) {
            $template = 'list-' . $style;
            $groupby = $style == 'archive' ? 'year' : '';
            $is_archive = $style == 'archive';
        } else {
            $template = 'list-minimal';
        }

        return View::factory('events/views/frontend/' . $template)
            ->assign('eventlist', EventsRepository::getList($time, $count, $order, $groupby, $is_archive))
            ->assign('categories', CategoriesRepository::getAll())
            ->assign('locations', LocationsRepository::getAll())
            ->render();
    }


    /**
     * error occurance
     */
    public function error()
    {
        return 'error occured';
    }


}
