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
        $objects = self::getTable();
        return reset($objects->select('[id=' . $id . ']'));
    }


    /**
     * insert
     */
    public static function insert($data)
    {
        $objects = self::getTable();
        return $objects->insert($data);
    }


    /**
     * update
     */
    public static function update($id, $data)
    {
        $objects = self::getTable();
        return $objects->update($id, $data);
    }


    /**
     * update
     */
    public static function delete($id)
    {
        $objects = self::getTable();
        return $objects->delete($id);
    }


    /**
     * get list of all locations
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
     * getAll
     */
    public static function getActive()
    {
        $objects = self::getTable();
        return $objects->select('[deleted=0]', 'all', null, null, 'title', 'ASC');
    }


    /**
     * getAll
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
     * getDeleted
     */
    public static function getDeleted()
    {
        $objects = self::getTable();
        return $objects->select('[deleted=1]', 'all', null, null, 'title', 'ASC');
    }


}