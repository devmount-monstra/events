<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

// Initialize Database
Table::create('events', array('title'));
$e = new Table('events');
$e->addField('status');
$e->addField('timestamp');
$e->addField('timestamp_end');
$e->addField('deleted');
$e->addField('category');
$e->addField('date');
$e->addField('openat');
$e->addField('time');
$e->addField('location');
$e->addField('short');
$e->addField('description');
$e->addField('archive');
$e->addField('hashtag');
$e->addField('facebook');
$e->addField('image');
$e->addField('imagesection');
$e->addField('gallery');
$e->addField('audio');
$e->addField('color');
$e->addField('number_staff');
$e->addField('number_visitors');

Table::create('categories', array('title'));
$c = new Table('categories');
$c->addField('deleted');
$c->addField('color');
$c->addField('hidden_in_archive');

Table::create('locations', array('title'));
$l = new Table('locations');
$l->addField('deleted');
$l->addField('website');
$l->addField('address');
$l->addField('lon');
$l->addField('lat');

// Add Options
Option::add('events_image_directory', '/');
Option::add('events_placeholder_archive', '');
Option::add('events_audio_directory', '/');