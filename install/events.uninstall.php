<?php 

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

// drop db tables
// Table::drop('events');
// Table::drop('categories');

// Delete Options
Option::delete('events_image_directory');
Option::delete('events_audio_directory');