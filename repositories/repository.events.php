<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

/**
 * Events repository class
 * 
 * Handles all events table database requests
 *
 */
class EventsRepository
{
    /**
     * Get events table object
     * 
     * @return object
     * 
     */
    public static function getTable()
    {
        return new Table('events');
    }


    /**
     * Get event by ID
     * 
     * @param  int  $id  Event ID to return
     * 
     * @return object
     * 
     */
    public static function getById($id)
    {
        $objects = self::getTable();
        return reset($objects->select('[id=' . $id . ']'));
    }


    /**
     * Insert new event
     * 
     * @param  array  $data  Data of event to insert
     * 
     * @return bool
     * 
     */
    public static function insert($data)
    {
        $objects = self::getTable();
        return $objects->insert($data);
    }


    /**
     * Update existing event
     * 
     * @param  int    $id    Event ID to update
     * @param  array  $data  Data of event to insert
     * 
     * @return bool
     * 
     */
    public static function update($id, $data)
    {
        $objects = self::getTable();
        return $objects->update($id, $data);
    }


    /**
     * Delete event
     * 
     * @param  int  $id  Event ID to delete
     * 
     * @return bool
     * 
     */
    public static function delete($id)
    {
        $objects = self::getTable();
        return $objects->delete($id);
    }


    /**
     * Get all event objects
     *
     * @return array
     *
     */
    public static function getAll()
    {
        $objects = self::getTable();
        $objects_all = $objects->select(Null, 'all');
        $objects_objects = array();
        foreach ($objects_all as $o) {
            $objects_objects[$o['id']] = $o;
        }
        return $objects_objects;
    }


    /**
     * Get all active (not deleted) event objects
     *
     * @return array
     *
     */
    public static function getActive()
    {
        $objects = self::getTable();
        return $objects->select('[deleted=0]', 'all', null, null, 'timestamp', 'ASC');
    }


    /**
     * Get all upcoming event objects
     *
     * @return array
     *
     */
    public static function getUpcoming()
    {
        $objects = self::getTable();
        return $objects->select('[number(translate(timestamp,"-: ",""))>=' . self::_getTime() . ' and deleted=0]', 'all', null, null, 'timestamp', 'ASC');
    }


    /**
     * Get all past event objects
     *
     * @return array
     *
     */
    public static function getPast()
    {
        $objects = self::getTable();
        return $objects->select('[number(translate(timestamp,"-: ",""))<' . self::_getTime() . ' and deleted=0]', 'all', null, null, 'timestamp', 'DESC');
    }


    /**
     * Get all draft event objects
     *
     * @return array
     *
     */
    public static function getDraft()
    {
        $objects = self::getTable();
        return $objects->select('[timestamp="" and deleted=0]', 'all', null, null, 'timestamp', 'ASC');
    }


    /**
     * Get all active event objects ready to use with HTML::select()
     *
     * @return array
     *
     */
    public static function getActiveForSelect()
    {
        $objects_active = self::getActive();
        $objects_select  = array(0 => '');
        foreach ($objects_active as $l) {
            $objects_select[$l['id']] = $l['title'];
        }
        return $objects_select;
    }


    /**
     * Get all deleted event objects
     *
     * @return array
     *
     */
    public static function getDeleted()
    {
        $objects = self::getTable();
        return $objects->select('[deleted=1]', 'all', null, null, 'title', 'ASC');
    }


    /**
     * Returns status for a given date
     *
     * @param  string  $date  Starting date of event
     * @param  string  $time  Starting time of event
     *
     * @return string  ['upcoming', 'past', 'draft']
     *
     */
    public static function getStatus($date, $time)
    {
        $timestamp = str_replace(array('-', ' ', ':'), '', $date . $time . ':00');
        if ($timestamp == '') {
            return 'draft';
        } else
        if ($timestamp >= self::_getTime()) {
            return 'upcoming';
        } else {
            return 'past';
        }
    }


    /**
     * Get configured list of events
     *
     * @param string  $time
     * @param string  $count
     * @param string  $order
     * @param string  $groupby
     * @param bool    $is_archive
     *
     * @return array
     *
     */
    public static function getList($time, $count, $order, $groupby = '', $is_archive = false)
    {
        // get db table object
        $objects = self::getTable();

        // handle order
        $roworder = '';
        if (in_array(trim($order), array('ASC', 'DESC'))) {
            $roworder = trim($order);
        } else {
            $roworder = 'ASC';
        }

        // handle time
        $now = self::_getTime();

        switch ($time) {
            case 'future':
                $eventlist = $objects->select('[number(translate(timestamp_end,"-: ",""))>=' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', $roworder);
                break;
            case 'past':
                $eventlist = $objects->select('[number(translate(timestamp,"-: ",""))<' . $now . ' and deleted=0]', 'all', null, null, 'timestamp', $roworder);
                break;
            case 'all':
            default:
                $eventlist = $objects->select('[deleted=0 and timestamp!=""]', 'all', null, null, 'timestamp', $roworder);
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
                $year = date('Y', strtotime($event['timestamp']));
                $eventlistyears[$year][] = $event;
            }
            return $eventlistyears;
        }
        

        return $eventlist;
    }


    /**
     * Get current timestamp
     *
     * @return int  timestamp
     *
     */
    private static function _getTime()
    {
        // return datetime in xpath compareable format
        return date('YmdHis');
    }

}