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
require_once 'repositories/repository.locations.php';
require_once 'repositories/repository.categories.php';

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
     * list events
     * 
     * @param string $style     ['extended', 'minimal', 'archiv']
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
        if (in_array($style, array('extended', 'minimal', 'archiv'))) {
            $template = 'list-' . $style;
            $groupby = $style == 'archiv' ? 'year' : '';
            $is_archive = $style == 'archiv';
        } else {
            $template = 'list-minimal';
        }

        return View::factory('events/views/frontend/' . $template)
            ->assign('eventlist', Events::_getEvents($time, $count, $order, $groupby, $is_archive))
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


    /**
     * get list of events
     *
     * @param string time
     * @param string count
     * @param string order
     * @param string groupby
     * @param bool   is_archive
     *
     * @return array
     *
     */
    private static function _getEvents($time, $count, $order, $groupby = '', $is_archive = false)
    {
        // get db table object
        $events = new Table('events');

        // handle order
        $roworder = '';
        if (in_array(trim($order), array('ASC', 'DESC'))) {
            $roworder = trim($order);
        } else {
            $roworder = 'ASC';
        }

        // handle time
        $now = time();

        switch ($time) {
            case 'future':
                $eventlist = $events->select('[timestamp_end>=' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', $roworder);
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
        
        // handle archive (remove events of category with flag (hidden_in_archive))
        if ($is_archive) {
            $categories = new Table('categories');
            $category_ids = array();
            foreach ($categories->select('[hidden_in_archive=1]', 'all', null, array('id')) as $category) {
                $category_ids[] = $category['id'];
            }
            foreach ($eventlist as $key => $event) {
                if (in_array($event['category'], $category_ids)) {
                    unset($eventlist[$key]);
                }
            }
        }

        // handle group by
        if ($groupby == 'year') {
            $eventlistyears = array();
            foreach ($eventlist as $event) {
                $date = getdate($event['timestamp']);
                $eventlistyears[$date['year']][] = $event;
            }
            return $eventlistyears;
        }
        

        return $eventlist;
    }

}
