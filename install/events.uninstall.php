<?php

// let only monstra allow to use this script
defined('MONSTRA_ACCESS') or die('No direct script access.');

// drop db tables
// Table::drop('events');
// Table::drop('categories');
// Table::drop('locations');

// Delete Options
Option::delete('upcon_title');
Option::delete('upcon_id');
Option::delete('upcon_active');
Option::delete('upcon_mail_confirmation');
Option::delete('upcon_mail_confirmation_subject');
Option::delete('upcon_mail_info');
Option::delete('upcon_mail_info_subject');
Option::delete('upcon_admin_mail');