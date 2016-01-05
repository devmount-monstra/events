<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

/**
 *	Events plugin admin
 *
 *  Provides CRUD for events and different output possibilities for event lists
 *
 *	@package    Monstra
 *  @subpackage Plugins
 *	@author     Andreas Müller | devmount <mail@devmount.de>
 *	@license    MIT
 *  @link       https://github.com/devmount-monstra/events
 *
 */


// Admin Navigation: add new item
Navigation::add(__('Events', 'events'), 'content', 'events', 10);

/**
 * Events class
 * 
 */
class EventsAdmin extends Backend
{
    /**
     * main toggle admin function
     */
    public static function main()
    {
        if (Request::post('events_submitted')) {
            if (Security::check(Request::post('csrf'))) {
                Option::update('events_duration', (int) Request::post('events_duration'));
                Option::update('events_easing', Request::post('events_easing'));
                Notification::set('success', __('Configuration has been saved with success!', 'events'));
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }

        // Display view
        View::factory('events/views/backend/index')->display();
    }

}