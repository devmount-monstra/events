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
        $events = new Table('events');
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
        return $objects->select('[timestamp>=' . self::_getTime() . ' and deleted=0]', 'all', null, null, 'timestamp', 'ASC');
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
        return $objects->select('[timestamp<' . self::_getTime() . ' and deleted=0]', 'all', null, null, 'timestamp', 'DESC');
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
     * Get current timestamp
     *
     * @return int  timestamp
     *
     */
    private static function _getTime()
    {
        return time();
    }

}