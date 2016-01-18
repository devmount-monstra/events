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

// Add plugin styles and scripts
Stylesheet::add('plugins/events/css/events.admin.css', 'backend', 11);
Javascript::add('plugins/events/js/events.admin.js', 'backend', 11);

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
                        'deleted' => 0,
                        'category' => (int) Request::post('event_category'),
                        'date' => (string) Request::post('event_date'),
                        'time' => (string) Request::post('event_time'),
                        'location' => (string) Request::post('event_location'),
                        'short' => (string) Request::post('event_short'),
                        'description' => (string) Request::post('event_description'),
                        'image' => (string) Request::post('event_image'),
                        'imagesection' => (string) Request::post('event_imagesection'),
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
                        'deleted' => 0,
                        'category' => (int) Request::post('event_category'),
                        'date' => (string) Request::post('event_date'),
                        'time' => (string) Request::post('event_time'),
                        'location' => (string) Request::post('event_location'),
                        'short' => (string) Request::post('event_short'),
                        'description' => (string) Request::post('event_description'),
                        'image' => (string) Request::post('event_image'),
                        'imagesection' => (string) Request::post('event_imagesection'),
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
        // Request: restore event
        if (Request::post('restore_trash_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->update((int) Request::post('restore_trash_event'), array('deleted' => 0));
                Notification::setNow('success', __('Event has been restored from trash with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete event
        if (Request::post('delete_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->update((int) Request::post('delete_event'), array('deleted' => 1));
                Notification::setNow('success', __('Event has been moved to trash with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete trash event
        if (Request::post('delete_trash_event')) {
            if (Security::check(Request::post('csrf'))) {
                $events->delete(Request::post('delete_trash_event'));
                Notification::setNow('success', __('Event has been deleted permanently with success!', 'events'));
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
                        'deleted' => 0,
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
                        'deleted' => 0,
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
        // Request: restore category
        if (Request::post('restore_trash_category')) {
            if (Security::check(Request::post('csrf'))) {
                $categories->update((int) Request::post('restore_trash_category'), array('deleted' => 0));
                Notification::setNow('success', __('Category has been restored from trash with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete category
        if (Request::post('delete_category')) {
            if (Security::check(Request::post('csrf'))) {
                $categories->update((int) Request::post('delete_category'), array('deleted' => 1));
                Notification::setNow('success', __('Category has been moved to trash with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete category
        if (Request::post('delete_trash_category')) {
            if (Security::check(Request::post('csrf'))) {
                $categories->delete(Request::post('delete_trash_category'));
                Notification::setNow('success', __('Category has been deleted with success!', 'events'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        
        // Request: options
        if (Request::post('events_options')) {
            if (Security::check(Request::post('csrf'))) {
                Option::update('events_image_directory', Request::post('events_image_directory'));
                Notification::setNow('success', __('Configuration has been saved with success!', 'toggle'));
            }
            else {
                Notification::setNow('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'toggle'));
                die();
            }
        }

        // get current time
        $now = time();
        // get all existing categories from db
        $allcategories = $categories->select(Null, 'all');
        $activecategories = $categories->select('[deleted=0]');
        $categories_title = array();
        $categories_color = array();
        $categories_count = array();
        foreach ($allcategories as $c) {
            $categories_title[$c['id']] = $c['title'];
            $categories_color[$c['id']] = $c['color'];
            $categories_count[$c['id']] = sizeof($events->select('[category=' . $c['id'] . ' and deleted=0]'));
        }
        // get all existing events from db
        $upcomingevents = $events->select('[timestamp>=' . $now . ' and deleted=0]');
        $pastevents = $events->select('[timestamp<' . $now . ' and deleted=0]');
        $draftevents = $events->select('[timestamp="" and deleted=0]');

        // get all deleted records from db
        $deletedevents = $events->select('[deleted=1]');
        $deletedcategories = $categories->select('[deleted=1]');

        // get upload directories
        $path = ROOT . DS . 'public' . DS . 'uploads/';
        $_list = EventsAdmin::fdir($path);
        $directories = array(DS => DS);
        if (isset($_list['dirs'])) {
            foreach ($_list['dirs'] as $dirs) {
                if (strpos($dirs, '.') === false && strpos($dirs, '..') === false){ 
                    $directories[$dirs] = DS . $dirs;
                }
            }
        }
        
        // Get information about current path
        $_list = EventsAdmin::fdir($path . Option::get('events_image_directory'));
        $files = array('' => '');
        // Get files
        if (isset($_list['files'])) {
            foreach ($_list['files'] as $fls) {
                $files[Site::url() . DS . 'public' . DS . 'uploads' . DS . Option::get('events_image_directory') . DS . $fls] = $fls;
            }
        }
        

        // Display view
        View::factory('events/views/backend/index')
            ->assign('categories', $activecategories)
            ->assign('deletedcategories', $deletedcategories)
            ->assign('categories_title', $categories_title)
            ->assign('categories_color', $categories_color)
            ->assign('categories_count', $categories_count)
            ->assign('upcomingevents', $upcomingevents)
            ->assign('pastevents', $pastevents)
            ->assign('deletedevents', $deletedevents)
            ->assign('draftevents', $draftevents)
            ->assign('directories', $directories)
            ->assign('files', $files)
            ->assign('path', $path)
            ->display();
    }

    /**
     * Get directories and files in current path
     */
    protected static function fdir($dir, $type = null)
    {
        $files = array();
        $c = 0;
        $_dir = $dir;
        if (is_dir($dir)) {
        $dir = opendir ($dir);
            while (false !== ($file = readdir($dir))) {
                if (($file !=".") && ($file !="..")) {
                    $c++;
                    if (is_dir($_dir.$file)) {
                        $files['dirs'][$c] = $file;
                    } else {
                        $files['files'][$c] = $file;
                    }
                }
            }
            closedir($dir);
            return $files;
        } else {
            return false;
        }
    }


}
