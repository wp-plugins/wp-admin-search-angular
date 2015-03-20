<?php
/**
 * Plugin Name: Admin Search Angular
 * Plugin URI: http://webcodingplace.com/admin-search-angular
 * Description: A great plugin for wordpress Admins to search and manage posts, pages by type and filter.
 * Version: 1.0
 * Author: Rameez
 * Author URI: http://webcodingplace.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: post-search
 */

/*

  Copyright (C) 2015  Rameez  rameez.iqbal@live.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
*/
require_once('plugin.class.php');

if( class_exists('Admin_Search_Angular')){
	
	$just_initialize = new Admin_Search_Angular;
}

?>