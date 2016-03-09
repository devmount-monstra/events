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
        // Ajax Request: add location
        if (Request::post('edit_location_id')) {
            $locations = new Table('locations');
            echo json_encode($locations->select('[id=' . Request::post('edit_location_id') . ']')[0]);
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
        $locations = new Table('locations');
        
        // Request: add event
        if (Request::post('add_event')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $events->insert(
                    array(
                        'title' => (string) htmlspecialchars(Request::post('event_title')),
                        'timestamp' => strtotime(Request::post('event_timestamp')),
                        'deleted' => 0,
                        'category' => (int) Request::post('event_category'),
                        'date' => (string) Request::post('event_date'),
                        'openat' => (string) Request::post('event_openat'),
                        'time' => (string) Request::post('event_time'),
                        'location' => (int) Request::post('event_location'),
                        'short' => (string) htmlspecialchars(Request::post('event_short')),
                        'description' => (string) htmlspecialchars(Request::post('event_description')),
                        'archiv' => (string) htmlspecialchars(Request::post('event_archiv')),
                        'hashtag' => (string) Request::post('event_hashtag'),
                        'facebook' => (string) Request::post('event_facebook'),
                        'image' => (string) Request::post('event_image'),
                        'imagesection' => (string) Request::post('event_imagesection'),
                        'gallery' => (string) Request::post('event_gallery'),
                        'audio' => (string) Request::post('event_audio'),
                        'color' => (string) Request::post('event_color'),
                    )
                );
                if ($success) {
                    Notification::set('success', __('Event was added with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->insert() returned an error. Event could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#events/' . EventsAdmin::_eventStatus(strtotime(Request::post('event_timestamp'))) . '-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: edit event
        if (Request::post('edit_event')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $events->update(
                    (int) Request::post('edit_event'),
                    array(
                        'title' => (string) htmlspecialchars(Request::post('event_title')),
                        'timestamp' => strtotime(Request::post('event_timestamp')),
                        'deleted' => 0,
                        'category' => (int) Request::post('event_category'),
                        'date' => (string) Request::post('event_date'),
                        'openat' => (string) Request::post('event_openat'),
                        'time' => (string) Request::post('event_time'),
                        'location' => (int) Request::post('event_location'),
                        'short' => (string) htmlspecialchars(Request::post('event_short')),
                        'description' => (string) htmlspecialchars(Request::post('event_description')),
                        'archiv' => (string) htmlspecialchars(Request::post('event_archiv')),
                        'hashtag' => (string) Request::post('event_hashtag'),
                        'facebook' => (string) Request::post('event_facebook'),
                        'image' => (string) Request::post('event_image'),
                        'imagesection' => (string) Request::post('event_imagesection'),
                        'gallery' => (string) Request::post('event_gallery'),
                        'audio' => (string) Request::post('event_audio'),
                        'color' => (string) Request::post('event_color'),
                    )
                );
                if ($success) {
                    Notification::set('success', __('Event was updated with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Event could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#events/' . EventsAdmin::_eventStatus(strtotime(Request::post('event_timestamp'))) . '-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: restore event
        if (Request::post('restore_trash_event')) {
            if (Security::check(Request::post('csrf'))) {
                $id = (int) Request::post('restore_trash_event');
                $success = $events->update($id, array('deleted' => 0));
                if ($success) {
                    Notification::set('success', __('Event has been restored from trash with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Event could not be restored.', 'events'));
                }
                Request::redirect('index.php?id=events#trash/trash-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete event
        if (Request::post('delete_event')) {
            if (Security::check(Request::post('csrf'))) {
                $id = (int) Request::post('delete_event');
                $success = $events->update($id, array('deleted' => 1));
                if ($success) {
                    Notification::set('success', __('Event has been moved to trash with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Event could not be deleted.', 'events'));
                }
                $record = $events->select('[id=' . $id . ']');
                Request::redirect('index.php?id=events#events/' . EventsAdmin::_eventStatus($record[0]['timestamp']) . '-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete trash event
        if (Request::post('delete_trash_event')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $events->delete((int) Request::post('delete_trash_event'));
                if ($success) {
                    Notification::set('success', __('Event has been deleted permanently with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->delete() returned an error. Event could not be deleted.', 'events'));
                }
                Request::redirect('index.php?id=events#trash/trash-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }

        // Request: add category
        if (Request::post('add_category')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $categories->insert(
                    array(
                        'title' => (string) htmlspecialchars(Request::post('category_title')),
                        'deleted' => 0,
                        'color' => (string) Request::post('category_color'),
                        'hidden_in_archive' => (int) Request::post('category_hidden_in_archive'),
                    )
                );
                if ($success) {
                    Notification::set('success', __('Category was added with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->insert() returned an error. Category could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#categories');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: edit category
        if (Request::post('edit_category')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $categories->update(
                    (int) Request::post('edit_category'),
                    array(
                        'title' => (string) htmlspecialchars(Request::post('category_title')),
                        'deleted' => 0,
                        'color' => (string) Request::post('category_color'),
                        'hidden_in_archive' => (int) Request::post('category_hidden_in_archive'),
                    )
                );
                if ($success) {
                    Notification::set('success', __('Category was updated with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Category could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#categories');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: restore category
        if (Request::post('restore_trash_category')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $categories->update((int) Request::post('restore_trash_category'), array('deleted' => 0));
                if ($success) {
                    Notification::set('success', __('Category has been restored from trash with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Category could not be restored.', 'events'));
                }
                Request::redirect('index.php?id=events#trash/trash-categories');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete category
        if (Request::post('delete_category')) {
            if (Security::check(Request::post('csrf'))) {
                $id = (int) Request::post('delete_category');
                if (!EventsAdmin::_hasEvents('category', $id)) {
                    $success = $categories->update($id, array('deleted' => 1));
                    if ($success) {
                        Notification::set('success', __('Category has been moved to trash with success!', 'events'));
                    } else {
                        Notification::set('error', __('Table->update() returned an error. Category could not be deleted.', 'events'));
                    }
                } else {
                    Notification::set('error', __('Deletion failed. This category is assigned to at least one event. Remove this category from every event to delete it.', 'events'));
                }
                Request::redirect('index.php?id=events#categories');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete trash category
        if (Request::post('delete_trash_category')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $categories->delete(Request::post('delete_trash_category'));
                if ($success) {
                    Notification::set('success', __('Category has been deleted permanently with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->delete() returned an error. Category could not be deleted.', 'events'));
                }
                Request::redirect('index.php?id=events#trash/trash-categories');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }

        // Request: add location
        if (Request::post('add_location')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $locations->insert(
                    array(
                        'title' => (string) htmlspecialchars(Request::post('location_title')),
                        'website' => (string) Request::post('location_website'),
                        'address' => (string) Request::post('location_address'),
                        'deleted' => 0,
                    )
                );
                if ($success) {
                    Notification::set('success', __('Location was added with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->insert() returned an error. Location could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#locations');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: edit location
        if (Request::post('edit_location')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $locations->update(
                    (int) Request::post('edit_location'),
                    array(
                        'title' => (string) htmlspecialchars(Request::post('location_title')),
                        'website' => (string) Request::post('location_website'),
                        'address' => (string) Request::post('location_address'),
                        'deleted' => 0,
                    )
                );
                if ($success) {
                    Notification::set('success', __('Location was updated with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Location could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#locations');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: restore location
        if (Request::post('restore_trash_location')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $locations->update((int) Request::post('restore_trash_location'), array('deleted' => 0));
                if ($success) {
                    Notification::set('success', __('Location has been restored from trash with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Location could not be restored.', 'events'));
                }
                Request::redirect('index.php?id=events#trash/trash-locations');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete location
        if (Request::post('delete_location')) {
            if (Security::check(Request::post('csrf'))) {
                $id = (int) Request::post('delete_location');
                if (!EventsAdmin::_hasEvents('location', $id)) {
                    $success = $locations->update($id, array('deleted' => 1));
                    if ($success) {
                        Notification::set('success', __('Location has been moved to trash with success!', 'events'));
                    } else {
                        Notification::set('error', __('Table->update() returned an error. Location could not be deleted.', 'events'));
                    }
                } else {
                    Notification::set('error', __('Deletion failed. This location is assigned to at least one event. Remove this location from every event to delete it.', 'events'));
                }
                Request::redirect('index.php?id=events#locations');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete trash location
        if (Request::post('delete_trash_location')) {
            if (Security::check(Request::post('csrf'))) {
                $success = $locations->delete(Request::post('delete_trash_location'));
                if ($success) {
                    Notification::set('success', __('Location has been deleted permanently with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->delete() returned an error. Location could not be deleted.', 'events'));
                }
                Request::redirect('index.php?id=events#trash/trash-locations');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }

        // Request: options
        if (Request::post('events_options')) {
            if (Security::check(Request::post('csrf'))) {
                Option::update('events_image_directory', Request::post('events_image_directory'));
                Option::update('events_placeholder_archiv', Request::post('events_placeholder_archiv'));
                Notification::set('success', __('Configuration has been saved with success!', 'events'));
                Request::redirect('index.php?id=events#configuration');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }

        // get current time
        $now = time();
        
        // get all existing categories from db
        $categories_all     = $categories->select(Null, 'all', null, null, 'title', 'ASC');
        $categories_active  = $categories->select('[deleted=0]', 'all', null, null, 'title', 'ASC');
        $categories_deleted = $categories->select('[deleted=1]');
        $categories_objects = array();
        $categories_select  = array();
        foreach ($categories_all as $c) {
            $c['count'] = sizeof($events->select('[category=' . $c['id'] . ' and deleted=0]'));
            $categories_objects[$c['id']] = $c;
        }
        foreach ($categories_active as $c) {
            $categories_select[$c['id']] = $c['title'];
        }

        // get all existing locations from db
        $locations_all     = $locations->select(Null, 'all');
        $locations_active  = $locations->select('[deleted=0]', 'all', null, null, 'title', 'ASC');
        $locations_deleted = $locations->select('[deleted=1]', 'all', null, null, 'title', 'ASC');
        $locations_objects = array();
        $locations_select  = array(0 => '');
        foreach ($locations_all as $l) {
            $l['count'] = sizeof($events->select('[location=' . $l['id'] . ' and deleted=0]'));
            $locations_objects[$l['id']] = $l;
        }
        foreach ($locations_active as $l) {
            $locations_select[$l['id']] = $l['title'];
        }

        // get all existing events from db
        $events_upcoming = $events->select('[timestamp>=' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', 'ASC');
        $events_past     = $events->select('[timestamp<' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', 'DESC');
        $events_draft    = $events->select('[timestamp="" and deleted=0]', 'all', null, null, 'timestamp', 'ASC');
        $events_deleted  = $events->select('[deleted=1]');

        // get upload directories
        $path = ROOT . DS . 'public' . DS . 'uploads/';
        $_list = EventsAdmin::_fdir($path);
        $directories = array(DS => DS);
        if (isset($_list['dirs'])) {
            foreach ($_list['dirs'] as $dirs) {
                if (strpos($dirs, '.') === false && strpos($dirs, '..') === false){
                    $directories[$dirs] = DS . $dirs;
                }
            }
        }

        // Get information about current path
        $_list = EventsAdmin::_fdir($path . Option::get('events_image_directory'));
        $files = array('' => '');
        // Get files
        if (isset($_list['files'])) {
            foreach ($_list['files'] as $fls) {
                $files[Site::url() . DS . 'public' . DS . 'uploads' . DS . Option::get('events_image_directory') . DS . $fls] = $fls;
                ksort($files);
            }
        }


        // Display view
        View::factory('events/views/backend/index')
            ->assign('categories', $categories_objects)
            ->assign('categories_active', $categories_active)
            ->assign('categories_select', $categories_select)
            ->assign('categories_deleted', $categories_deleted)
            ->assign('locations', $locations_objects)
            ->assign('locations_active', $locations_active)
            ->assign('locations_select', $locations_select)
            ->assign('locations_deleted', $locations_deleted)
            ->assign('events_upcoming', $events_upcoming)
            ->assign('events_past', $events_past)
            ->assign('events_draft', $events_draft)
            ->assign('events_deleted', $events_deleted)
            ->assign('directories', $directories)
            ->assign('files', $files)
            ->assign('path', $path)
            ->display();
    }

    /**
     * returns status for a given timestamp: 'upcoming', 'past', 'draft'
     *
     * @param: $timestamp   int     event time
     */
    private static function _eventStatus($timestamp)
    {
        $now = time();
        if ($timestamp == 0) {
            return 'draft';
        } else
        if ($timestamp >= $now) {
            return 'upcoming';
        } else {
            return 'past';
        }
    }

    /**
     * returns true if object has events assigned
     *
     * @param: $attribute   string  attribute of events where event is assigned to (e.g. category or location)
     * @param: $id          int     id of object to check
     */
    private static function _hasEvents($attribute, $id)
    {
        $events = new Table('events');
        return sizeof($events->select('[' . $attribute . '=' . $id . ' and deleted=0]', 'all'))>0;
    }

    /**
     * Get directories and files in current path
     */
    private static function _fdir($dir, $type = null)
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
