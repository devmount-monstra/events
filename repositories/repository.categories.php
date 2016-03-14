<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

/**
 * Categories repository class
 * 
 * Handles all categories table database requests
 *
 */
class CategoriesRepository
{
    /**
     * Get categories table object
     * 
     * @return object
     * 
     */
    public static function getTable()
    {
        return new Table('categories');
    }


    /**
     * Get category by ID
     * 
     * @param  int  $id  Category ID to return
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
     * Insert new category
     * 
     * @param  array  $data  Data of category to insert
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
     * Update existing category
     * 
     * @param  int    $id    Category ID to update
     * @param  array  $data  Data of category to insert
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
     * Delete category
     * 
     * @param  int  $id  Category ID to delete
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
     * Get all category objects
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
            $l['count'] = sizeof($events->select('[category=' . $l['id'] . ' and deleted=0]'));
            $objects_objects[$l['id']] = $l;
        }
        return $objects_objects;
    }


    /**
     * Get all active (not deleted) category objects
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
     * Get all active category objects ready to use with HTML::select()
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
     * Get all deleted category objects
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
     * Returns true if category has events assigned
     *
     * @param  int  $id  Category ID to check
     *
     * @return bool
     *
     */
    public static function hasEvents($id)
    {
        $events = new Table('events');
        return sizeof($events->select('[category=' . $id . ' and deleted=0]', 'all'))>0;
    }

}