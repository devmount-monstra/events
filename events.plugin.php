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
 * Shortcode: {events type="list" time="future" order="ASC"}
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
        switch ($attributes['type']) {
            case 'list':
                return Events::listEvents($attributes['style'], $attributes['time'], $attributes['count'], $attributes['order']);
                // return 'test';
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
     * Assign to view
     */
    public function listEvents($style, $time = 'all', $count = 'all', $order = 'ASC')
    {
        // get db table objects
        $events = new Table('events');
        $categories = new Table('categories');
        $allcategories = $categories->select(Null, 'all');
        $categories_title = array();
        foreach ($allcategories as $c) {
            $categories_title[$c['id']] = $c['title'];
        }

        // handle style
        $template = '';
        if (in_array(trim($style), array('extended', 'minimal'))) {
            $template = 'list-' . trim($style);
        } else {
            $template = 'list-minimal';
        }

        // handle order
        $roworder = '';
        if (in_array(trim($order), array('ASC', 'DESC'))) {
            $roworder = trim($order);
        } else {
            $template = 'ASC';
        }

        // handle time
        $now = time();

        switch ($time) {
            case 'future':
                $eventlist = $events->select('[timestamp>=' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', $roworder);
                break;
            case 'past':
                $eventlist = $events->select('[timestamp<' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', $roworder);
                break;
            case 'all':
            default:
                $eventlist = $events->select('[deleted=0 and timestamp>0]', 'all', null, null, 'timestamp', $roworder);
                break;
        }

        // handle count
        if (trim($count) != 'all') {
            if($roworder == 'ASC') {
                $eventlist = array_slice($eventlist, 0, (int) $count);
            } else {
                $offset = count($eventlist)-((int) $count);
                $offset = $offset < 0 ? : $offset;
                $eventlist = array_slice($eventlist, $offset);
            }
        }

        return View::factory('events/views/frontend/' . $template)
            ->assign('eventlist', $eventlist)
            ->assign('categories', $categories_title)
            ->render();
    }

    /**
     * Assign to view
     */
    public function error()
    {
        return 'error occured';
    }

}
