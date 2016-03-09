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
$e->addField('short');
$e->addField('description');
$e->addField('archiv');
$e->addField('hashtag');
$e->addField('facebook');
$e->addField('image');
$e->addField('imagesection');
$e->addField('gallery');
$e->addField('audio');
$e->addField('color');

Table::create('categories', array('title'));
$c = new Table('categories');
$c->addField('deleted');
$c->addField('color');
$c->addField('hidden_in_archive');

Table::create('locations', array('title'));
$c = new Table('locations');
$c->addField('deleted');
$c->addField('website');
$c->addField('address');

// Add Options
Option::add('events_image_directory', '/');
Option::add('events_placeholder_archiv', '');
Option::add('events_audio_directory', '/');