<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

/**
 * Locations repository class
 * 
 * Provides all locations table database requests
 *
 */
class LocationsRepository
{
    /**
     * Get locations table object
     * 
     * @return object
     * 
     */
    public static function getTable()
    {
        return new Table('locations');
    }


    /**
     * Get location by ID
     * 
     * @param  int  $id  Location ID to return
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
     * Insert new location
     * 
     * @param  array  $data  Data of location to insert
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
     * Update existing location
     * 
     * @param  int    $id    Location ID to update
     * @param  array  $data  Data of location to insert
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
     * Delete location
     * 
     * @param  int  $id  Location ID to delete
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
     * Get all location objects
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
        foreach ($objects_all as $l) {
            $l['count'] = sizeof($events->select('[location=' . $l['id'] . ' and deleted=0]'));
            $objects_objects[$l['id']] = $l;
        }
        return $objects_objects;
    }


    /**
     * Get all active (not deleted) location objects
     *
     * @return array
     *
     */
    public static function getActive()
    {
        $objects = self::getTable();
        return $objects->select('[deleted=0]', 'all', null, null, 'title', 'ASC');
    }


    /**
     * Get all active location objects ready to use with HTML::select()
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
     * Get all deleted location objects
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
     * Returns true if location has events assigned
     *
     * @param  int  $id  Location ID to check
     *
     * @return bool
     *
     */
    public static function hasEvents($id)
    {
        $events = new Table('events');
        return sizeof($events->select('[location=' . $id . ' and deleted=0]', 'all'))>0;
    }

}