<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

// Initialize Database
Table::create('events', array('title'));
$e = new Table('events');
$e->addField('timestamp');
$e->addField('category');
$e->addField('date');
$e->addField('time');
$e->addField('location');
$e->addField('short');
$e->addField('description');
$e->addField('image');
$e->addField('audio');
$e->addField('color');

Table::create('categories', array('title'));
$c = new Table('categories');
$c->addField('color');

// Add Options
// Option::add('toggle_duration', '400');
// Option::add('toggle_easing', 'swing');