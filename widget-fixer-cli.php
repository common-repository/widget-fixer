<?php

require_once("includes/widget-fixer-functions.php");
$_REQUEST['action']="really-fix";

if (!empty($argv[1])) {
	$wp_config_file=$argv[1];
} else {
	print "\nUsage: php -q widget-fixer-cli.php [path-to-wp-config]\n\n";
	die();
}

initialize_mysql($wp_config_file);
display_widget_fixer("cli");

