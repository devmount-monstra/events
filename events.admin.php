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
// lib: Image Picker http://rvera.github.io/image-picker/
Stylesheet::add('plugins/events/lib/image-picker/image-picker.css', 'backend', 12);
Javascript::add('plugins/events/lib/image-picker/image-picker.min.js', 'backend', 12);

// Admin Navigation: add new item
Navigation::add(__('Events', 'events'), 'content', 'events', 10);

// Add action on admin_pre_render hook
Action::add('admin_pre_render','EventsAdmin::_getAjaxData');

// register repository classes
require_once 'repositories/repository.events.php';
require_once 'repositories/repository.categories.php';
require_once 'repositories/repository.locations.php';

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
            echo json_encode(EventsRepository::getById((int) Request::post('edit_event_id')));
            Request::shutdown();
        }
        // Ajax Request: add category
        if (Request::post('edit_category_id')) {
            echo json_encode(CategoriesRepository::getById((int) Request::post('edit_category_id')));
            Request::shutdown();
        }
        // Ajax Request: add location
        if (Request::post('edit_location_id')) {
            echo json_encode(LocationsRepository::getById((int) Request::post('edit_location_id')));
            Request::shutdown();
        }
    }

    /**
     * main events admin function
     */
    public static function main()
    {
        $path = ROOT . DS . 'public' . DS . 'uploads' . DS;

        // Request: add event
        if (Request::post('add_event')) {
            if (Security::check(Request::post('csrf'))) {
                if (EventsRepository::insert(EventsAdmin::_getEventData())) {
                    Notification::set('success', __('Event was added with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->insert() returned an error. Event could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#events/' . EventsRepository::getStatus(EventsRepository::getLastId()) . '-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: edit event
        if (Request::post('edit_event')) {
            if (Security::check(Request::post('csrf'))) {
                $id = (int) Request::post('edit_event');
                if (EventsRepository::update($id, EventsAdmin::_getEventData())) {
                    Notification::set('success', __('Event was updated with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Event could not be saved.', 'events'));
                }
                Request::redirect('index.php?id=events#events/' . EventsRepository::getStatus($id) . '-events');
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
                if (EventsRepository::update($id, array('deleted' => 0))) {
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
                if (EventsRepository::update($id, array('deleted' => 1))) {
                    Notification::set('success', __('Event has been moved to trash with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Event could not be deleted.', 'events'));
                }
                $record = EventsRepository::getById($id);
                Request::redirect('index.php?id=events#events/' . EventsRepository::getStatus($id) . '-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }
        // Request: delete trash event
        if (Request::post('delete_trash_event')) {
            if (Security::check(Request::post('csrf'))) {
                $id = (int) Request::post('delete_trash_event');
                if (EventsRepository::delete($id)) {
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
        // Request: update event status ['published','draft']
        if (Request::get('eventaction') and Request::get('eventaction') == 'update_status') {
            if (Security::check(Request::get('token'))) {
                $id = (int) Request::get('event_id');
                if (EventsRepository::update($id, array('status' => Request::get('status')))) {
                    Notification::set('success', __('Event status has been updated with success!', 'events'));
                } else {
                    Notification::set('error', __('Table->update() returned an error. Event status could not be updated.', 'events'));
                }
                Request::redirect('index.php?id=events#events/' . EventsRepository::getStatus($id) . '-events');
            }
            else {
                Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                die();
            }
        }

        // Request: add category
        if (Request::post('add_category')) {
            if (Security::check(Request::post('csrf'))) {
                if (CategoriesRepository::insert(EventsAdmin::_getCategoryData())) {
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
                $id = (int) Request::post('edit_category');
                if (CategoriesRepository::update($id, EventsAdmin::_getCategoryData())) {
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
                $id = (int) Request::post('restore_trash_category');
                if (CategoriesRepository::update($id, array('deleted' => 0))) {
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
                if (!CategoriesRepository::hasEvents($id)) {
                    if (CategoriesRepository::update($id, array('deleted' => 1))) {
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
                $id = (int) Request::post('delete_trash_category');
                if (CategoriesRepository::delete($id)) {
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
                if (LocationsRepository::insert(EventsAdmin::_getLocationData())) {
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
                $id = (int) Request::post('edit_location');
                if (LocationsRepository::update($id, EventsAdmin::_getLocationData())) {
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
                $id = (int) Request::post('restore_trash_location');
                if (LocationsRepository::update($id, array('deleted' => 0))) {
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
                if (!LocationsRepository::hasEvents($id)) {
                    if (LocationsRepository::update($id, array('deleted' => 1))) {
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
                $id = (int) Request::post('delete_trash_location');
                if (LocationsRepository::delete($id)) {
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
        
        // get upload directories
        $directory_list = Dir::scan($path);
        $directories = array(DS => DS);
        if (!empty($directory_list)) {
            foreach ($directory_list as $directory_name) {
                $directories[$directory_name] = DS . $directory_name;
            }
            ksort($directories);
        }

        // Get files
        $file_list = File::scan($path . Option::get('events_image_directory'));
        $files = array('' => '');
        if (!empty($file_list)) {
            foreach ($file_list as $file_name) {
                $files[$file_name] = $file_name;
            }
            ksort($files);
        }
        
        if (Request::get('action')) {
            switch (Request::get('action')) {
                // Request: configuration
                case "configuration":
                    // Request: options
                    if (Request::post('events_options_update') or Request::post('events_options_update_and_exit')) {
                        if (Security::check(Request::post('csrf'))) {
                            Option::update('events_image_directory', (string) Request::post('events_image_directory'));
                            Option::update('events_placeholder_archive', (string) Request::post('events_placeholder_archive'));
                            Notification::set('success', __('Configuration has been saved with success!', 'events'));
                            Request::redirect('index.php?id=events' . (Request::post('events_options_update') ? '&action=configuration' : ''));
                        }
                        else {
                            Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                            die();
                        }
                    }
                    // Request: action: resize images
                    if (Request::post('events_action_resize_images') or Request::post('events_action_resize_images_and_exit')) {
                        if (Security::check(Request::post('csrf'))) {
                            $n = 0;
                            $size = (int) Request::post('events_action_resize_size');
                            $image_dir = $path . Option::get('events_image_directory');
                            $image_dir_res = $path . Option::get('events_image_directory') . DS . 'resized';
                            $images = File::scan($image_dir);
                            if (!empty($images)) {
                                // create 'resized' directory if not exists
                                if (!Dir::exists($image_dir_res)) {
                                    Dir::create($image_dir_res);
                                }
                                foreach ($images as $file_name) {
                                    if (File::exists($image_dir_res . DS . $file_name)) {
                                        if (Request::post('events_action_resize_overwrite')) {
                                            File::delete($image_dir_res . DS . $file_name);
                                        } else {
                                            continue;
                                        }
                                    }
                                    list($width, $height) = getimagesize($image_dir . DS . $file_name);
                                    $image_orientation = $width > $height ? Image::HEIGHT : Image::WIDTH;
                                    Image::factory($image_dir . DS . $file_name)->resize($size, $size, $image_orientation)->save($image_dir_res . DS . $file_name);
                                    $n++;
                                }
                                Notification::set('success', __($n . ' images have been resized and saved with success!', 'events'));
                            } else {
                                Notification::set('error', __('There are no images to resize in configured image directory.', 'events'));
                            }
                            Request::redirect('index.php?id=events' . (Request::post('events_action_resize_images') ? '&action=configuration' : ''));
                        }
                        else {
                            Notification::set('error', __('Request was denied. Invalid security token. Please refresh the page and try again.', 'events'));
                            die();
                        }
                    }
                    // Display configuration view
                    View::factory('events/views/backend/configuration')
                        ->assign('directories', $directories)
                        ->display();
                break;
            }
        } else {
            // Display index view
            View::factory('events/views/backend/index')
                ->assign('categories', CategoriesRepository::getAll())
                ->assign('categories_active', CategoriesRepository::getActive())
                ->assign('categories_select', CategoriesRepository::getActiveForSelect())
                ->assign('categories_deleted', CategoriesRepository::getDeleted())
                ->assign('locations', LocationsRepository::getAll())
                ->assign('locations_active', LocationsRepository::getActive())
                ->assign('locations_select', LocationsRepository::getActiveForSelect())
                ->assign('locations_deleted', LocationsRepository::getDeleted())
                ->assign('events_active', EventsRepository::getActive())
                ->assign('events_upcoming', EventsRepository::getUpcoming())
                ->assign('events_past', EventsRepository::getPast())
                ->assign('events_draft', EventsRepository::getDraft())
                ->assign('events_deleted', EventsRepository::getDeleted())
                ->assign('imagepath', DS . 'public' . DS . 'uploads' . DS . Option::get('events_image_directory') . DS)
                // ->assign('directories', $directories)
                ->assign('files', $files)
                ->display();
        }
    }


    /**
     * _getEventData
     *
     * @return array  with event data from Request::post
     *
     */
    private static function _getEventData()
    {
        return array(
            'deleted' => 0,
            'title' => htmlspecialchars((string) Request::post('event_title')),
            'status' => (string) Request::post('event_status'),
            'timestamp' => Request::post('event_timestamp_date') ? (string) Request::post('event_timestamp_date') . ' ' . (string) Request::post('event_timestamp_time') . ':00' : '', // format: YY-MM-DD HH:II:SS
            'timestamp_end' => Request::post('event_timestamp_end_date') ? (string) Request::post('event_timestamp_end_date') . ' ' . (string) Request::post('event_timestamp_end_time') . ':00' : '', // format: YY-MM-DD HH:II:SS
            'category' => (int) Request::post('event_category'),
            'date' => (string) Request::post('event_date'),
            'openat' => (string) Request::post('event_openat'),
            'time' => (string) Request::post('event_time'),
            'location' => (int) Request::post('event_location'),
            'short' => htmlspecialchars((string) Request::post('event_short')),
            'description' => htmlspecialchars((string) Request::post('event_description')),
            'archive' => htmlspecialchars((string) Request::post('event_archive')),
            'hashtag' => (string) Request::post('event_hashtag'),
            'facebook' => (string) Request::post('event_facebook'),
            'image' => (string) Request::post('event_image'),
            'imagesection' => (string) Request::post('event_imagesection'),
            'gallery' => (string) Request::post('event_gallery'),
            'audio' => (string) Request::post('event_audio'),
            'color' => (string) Request::post('event_color'),
            'number_staff' => (string) Request::post('event_number_staff'),
            'number_visitors' => (string) Request::post('event_number_visitors'),
        );
    }


    /**
     * _getCategoryData
     *
     * @return array  with category data from Request::post
     *
     */
    private static function _getCategoryData()
    {
        return array(
            'deleted' => 0,
            'title' => (string) htmlspecialchars(Request::post('category_title')),
            'color' => (string) Request::post('category_color'),
            'hidden_in_archive' => (int) Request::post('category_hidden_in_archive'),
        );
    }


    /**
     * _getLocationData
     *
     * @return array  with location data from Request::post
     *
     */
    private static function _getLocationData()
    {
        return array(
            'deleted' => 0,
            'title' => (string) htmlspecialchars(Request::post('location_title')),
            'website' => (string) Request::post('location_website'),
            'address' => (string) Request::post('location_address'),
        );
    }


}
