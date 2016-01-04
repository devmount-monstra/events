<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

// Initialize Database
Table::create(
    'events',
    array(
        'title',
        'timestamp',
        'category',
        'date',
        'time',
        'location',
        'description',
        'image',
        'audio',
        'color',
    )
);
Table::create(
    'categories',
    array(
        'title',
        'color',
    )
);

// Add Options
// Option::add('toggle_duration', '400');
// Option::add('toggle_easing', 'swing');