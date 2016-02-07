<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

// Initialize Database
Table::create('events', array('title'));
$e = new Table('events');
$e->addField('timestamp');
$e->addField('deleted');
$e->addField('category');
$e->addField('date');
$e->addField('openat');
$e->addField('time');
$e->addField('location');
$e->addField('address');
$e->addField('short');
$e->addField('description');
$e->addField('hashtag');
$e->addField('facebook');
$e->addField('image');
$e->addField('imagesection');
$e->addField('audio');
$e->addField('color');

Table::create('categories', array('title'));
$c = new Table('categories');
$c->addField('deleted');
$c->addField('color');

// Add Options
Option::add('events_image_directory', '/');
Option::add('events_audio_directory', '/');