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

// Add action on admin_pre_render hook
Action::add('admin_pre_render','EventsAdmin::_getEvent');

/**
 * Events class
 * 
 */
class EventsAdmin extends Backend
{
    /**
     * Ajax: get Event by ID
     */
    public static function _getEvent()
    {
        // Ajax Request: add event
        if (Request::post('edit_event_id')) {
            $events = new Table('events');
            echo $events->select('[id=' . Request::post('edit_event_id') . ']')[0]['title'];
            Request::shutdown();
        }
    }
    
    /**
     * main events admin function
     */
    public static function main()
    {
        $events = new Table('events');
        
        // Request: add event
        if (Request::post('add_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->insert(
                    array(
                        'title' => (string) Request::post('events_title'),
                        'timestamp' => strtotime(Request::post('events_timestamp')),
                        'category' => (int) Request::post('events_category'),
                        'date' => (string) Request::post('events_date'),
                        'time' => (string) Request::post('events_time'),
                        'location' => (string) Request::post('events_location'),
                        'short' => (string) Request::post('events_short'),
                        'description' => (string) Request::post('events_description'),
                        'image' => (string) Request::post('events_image'),
                        'audio' => (string) Request::post('events_audio'),
                        'color' => (string) Request::post('events_color'),
                    )
                );
                Notification::setNow('success', __('Event has been added with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete event
        if (Request::post('delete_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->delete(Request::post('delete_event'));
                Notification::setNow('success', __('Event has been deleted with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        
        // Display view
        View::factory('events/views/backend/index')->display();
    }

}
