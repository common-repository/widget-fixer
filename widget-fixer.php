<?php
/*
Plugin Name: Widget Fixer
Plugin URI: http://wordpress.org/extend/plugins/widget-fixer/
Description: Makes Widgets work again after migrating a WordPress database to a new host/domain.  May resolve other widget issues as well, if your widgets spontaneously stop work$
Version: 1.2
Author: Infragistics Inc.
Author URI: http://www.infragistics.com
License: GPL2
*/
/*  Copyright 2013  Infragistics  (email: wordpress-dev@infragistics.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

error_reporting(E_ERROR);

require_once("includes/widget-fixer-functions.php");
initialize_mysql();
add_action("admin_menu","widget_fixer");
