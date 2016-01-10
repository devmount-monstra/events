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
Action::add('admin_pre_render','EventsAdmin::_getAjaxData');

/**
 * Events class
 * 
 */
class EventsAdmin extends Backend
{
    /**
     * Ajax: get Event by ID
     */
    public static function _getAjaxData()
    {
        // Ajax Request: add event
        if (Request::post('edit_event_id')) {
            $events = new Table('events');
            echo json_encode($events->select('[id=' . Request::post('edit_event_id') . ']')[0]);
            Request::shutdown();
        }
        // Ajax Request: add category
        if (Request::post('edit_category_id')) {
            $categories = new Table('categories');
            echo json_encode($categories->select('[id=' . Request::post('edit_category_id') . ']')[0]);
            Request::shutdown();
        }
    }
    
    /**
     * main events admin function
     */
    public static function main()
    {
        // get db table objects
        $events = new Table('events');
        $categories = new Table('categories');
        
        // Request: add event
        if (Request::post('add_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->insert(
                    array(
                        'title' => (string) Request::post('event_title'),
                        'timestamp' => strtotime(Request::post('event_timestamp')),
                        'category' => (int) Request::post('event_category'),
                        'date' => (string) Request::post('event_date'),
                        'time' => (string) Request::post('event_time'),
                        'location' => (string) Request::post('event_location'),
                        'short' => (string) Request::post('event_short'),
                        'description' => (string) Request::post('event_description'),
                        'image' => (string) Request::post('event_image'),
                        'audio' => (string) Request::post('event_audio'),
                        'color' => (string) Request::post('event_color'),
                    )
                );
                Notification::setNow('success', __('Event was added with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: edit event
        if (Request::post('edit_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->update(
                    (int) Request::post('edit_event'),
                    array(
                        'title' => (string) Request::post('event_title'),
                        'timestamp' => strtotime(Request::post('event_timestamp')),
                        'category' => (int) Request::post('event_category'),
                        'date' => (string) Request::post('event_date'),
                        'time' => (string) Request::post('event_time'),
                        'location' => (string) Request::post('event_location'),
                        'short' => (string) Request::post('event_short'),
                        'description' => (string) Request::post('event_description'),
                        'image' => (string) Request::post('event_image'),
                        'audio' => (string) Request::post('event_audio'),
                        'color' => (string) Request::post('event_color'),
                    )
                );
                Notification::setNow('success', __('Event was updated with success!', 'events'));
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
        
        // Request: add category
        if (Request::post('add_category')) {
            if (Security::check(Request::post('csrf'))) {
                $categories->insert(
                    array(
                        'title' => (string) Request::post('category_title'),
                        'color' => (string) Request::post('category_color'),
                    )
                );
                Notification::setNow('success', __('Category was added with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: edit category
        if (Request::post('edit_category')) {
            if (Security::check(Request::post('csrf'))) {
                $categories->update(
                    (int) Request::post('edit_category'),
                    array(
                        'title' => (string) Request::post('category_title'),
                        'color' => (string) Request::post('category_color'),
                    )
                );
                Notification::setNow('success', __('Category was updated with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete category
        if (Request::post('delete_category')) {
            if (Security::check(Request::post('csrf'))) {
                $categories->delete(Request::post('delete_category'));
                Notification::setNow('success', __('Category has been deleted with success!', 'events'));
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
