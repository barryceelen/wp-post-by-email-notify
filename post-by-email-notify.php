<?php
/**
 * Post By Email Notify.
 *
 * @package   Post_By_Email_Notify
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-2.0+
 * @link      http://github.com/barryceelen/post-by-email-notify
 * @copyright 2013 Barry Ceelen
 *
 * @wordpress-plugin
 * Plugin Name: Post By Email Notify
 * Plugin URI:  http://github.com/barryceelen/post-by-email-notify
 * Description: Sends a notification to authors when a post is created by email.
 * Version:     0.0.1
 * Author:      Barry Ceelen
 * Author URI:  http://github.com/barryceelen
 * Text Domain: post-by-email-notify
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

/*  Copyright 2013  Barry Ceelen (email : b@rryceelen.com)

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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . 'class-post-by-email-notify.php' );
add_action( 'plugins_loaded', array( 'Post_By_Email_Notify', 'get_instance' ) );
