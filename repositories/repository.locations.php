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
     * return locations table
     */
    public static function getTable()
    {
        return new Table('locations');
    }


    /**
     * getById
     */
    public static function getById($id)
    {
        $locations = self::getTable();
        return reset($locations->select('[id=' . $id . ']'));
    }


    /**
     * insert
     */
    public static function insert($data)
    {
        $locations = self::getTable();
        return $locations->insert($data);
    }


    /**
     * update
     */
    public static function update($id, $data)
    {
        $locations = self::getTable();
        return $locations->update($id, $data);
    }


    /**
     * update
     */
    public static function delete($id)
    {
        $locations = self::getTable();
        return $locations->delete($id);
    }


    /**
     * get list of all locations
     *
     * @return array
     *
     */
    public static function getAll()
    {
        $locations = self::getTable();
        $events = new Table('events');
        $locations_all = $locations->select(Null, 'all');
        $locations_objects = array();
        foreach ($locations_all as $l) {
            $l['count'] = sizeof($events->select('[location=' . $l['id'] . ' and deleted=0]'));
            $locations_objects[$l['id']] = $l;
        }
        return $locations_objects;
    }


    /**
     * getAll
     */
    public static function getActive()
    {
        $locations = self::getTable();
        return $locations->select('[deleted=0]', 'all', null, null, 'title', 'ASC');
    }


    /**
     * getAll
     */
    public static function getActiveForSelect()
    {
        $locations_active = self::getActive();
        $locations_select  = array(0 => '');
        foreach ($locations_active as $l) {
            $locations_select[$l['id']] = $l['title'];
        }
        return $locations_select;
    }


    /**
     * getDeleted
     */
    public static function getDeleted()
    {
        $locations = self::getTable();
        return $locations->select('[deleted=1]', 'all', null, null, 'title', 'ASC');
    }


}