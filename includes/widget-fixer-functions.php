<?php

function widget_fixer() {
	add_submenu_page("options-general.php","Widget Fixer","Widget Fixer","manage_options","widget-fix.php","display_widget_fixer");
}

function initialize_mysql($wp_config_file="") {
	global $db;
	if (empty($wp_config_file)) {
	        $wp_config_file=preg_replace("/^(.*)\/wp-admin\/.*$/","$1/wp-config.php",$_SERVER['SCRIPT_FILENAME']);
	}
        $CONFIG=file($wp_config_file);

        foreach ($CONFIG as $line) {
                if (preg_match("/DB_([a-zA-Z_\-0-9]+)', ?'?(.*)'.*$/",$line,$matches)) {
                        $db_var="DB_".$matches[1];
                        $$db_var=$matches[2];
                }
        }
        $db=mysql_connect($DB_HOST,$DB_USER,$DB_PASSWORD) or print "Could not connect to the DB Server!";
	mysql_set_charset('utf8',$db);
        mysql_select_db($DB_NAME,$db);
}


function get_option_names() {
	global $db;
	$query="select option_name from wp_options where ( option_name like '%widget%' or option_name like '%theme%' ) and option_value like'%a:%s:%'";;
	$result=mysql_query($query,$db) or print "Error with MySQL query.\n";
	$results=array();
	if ($result && mysql_num_rows($result) > 0) {
		while ($row=mysql_fetch_assoc($result)) {
			array_push($results,$row['option_name']);
		}
	}
	return $results;
}


function display_widget_fixer($type="web") {
	global $db;
	if (empty($type)) $type="web";
	$option_names=get_option_names();
	$records=array();
	// Each record to check gets its own array of values below
	foreach ($option_names as $option_name) {
	        array_push($records,array("table"=>"wp_options",
        	                        "item"=>"option_name",
                	                "itemvalue"=>$option_name,
                        	        "returnvalue"=>"option_value",
                        	        )
                	);
	}

       array_push($records,array("table"=>"wp_postmeta",
                               "item"=>"meta_key",
                               "itemvalue"=>"_wp_attached_file",
                               "returnvalue"=>"meta_value",
                       )
        );

       array_push($records,array("table"=>"wp_postmeta",
                               "item"=>"meta_key",
                               "itemvalue"=>"_wp_attachment_metadata",
                               "returnvalue"=>"meta_value",
                       )
        );

	if ($type=="web") {
		print "<br><br><br>\n";
		print "<form method=POST action=".$_REQUEST['REQUEST_URI'].">\n";
		if ($_REQUEST['action']!="fix-widgets" && $_REQUEST['action']!="really-fix") {
			print "<input type=hidden name=action value='fix-widgets'>\n";
			print "<input type=submit value='Fix Widgets - Dry Run'>\n";
		} elseif ($_REQUEST['action']=="fix-widgets") {
	                print "<input type=hidden name=action value='really-fix'>\n";
	                print "<input type=submit value='Fix Widgets'>\n";
			print "</form>\n";
			print "<form method=POST action=".$_REQUEST['REQUEST_URI'].">\n";
			print "<input type=submit value='Cancel'>\n";
			print "</form>\n";
			print "<pre>\n";
			foreach ($records as $record) {
				fix_widgets(0,$record,$type);
			}
			print "</pre>\n";
		} elseif ($_REQUEST['action']=="really-fix") {
			if ($type=="web") print "<pre>\n";
			foreach ($records as $record) {
				fix_widgets(1,$record,$type);
			}
			if ($type=="web") print "</pre>\n";
		}
	} else {
		if ($type=="web") print "<pre>\n";
		foreach ($records as $record) {
			fix_widgets(1,$record,$type);
		}
		if ($type=="web") print "</pre>\n";
	}
}

function fix_widgets($really=1,$record=array(),$type="web") {
	global $db;

	if (count($record)==0) {
		$record['table']="wp_options";
		$record['item']="option_name";
		$record['itemvalue']="widget_text";
		$record['returnvalue']="option_value";
	}


	$query="select ".$record['returnvalue']." from ".$record['table']." where ".$record['item']." ='".$record['itemvalue']."'";
	$result=mysql_query($query,$db);
	if ($result && mysql_num_rows($result) > 0) {
	        while ($row=mysql_fetch_assoc($result)) {
	                $ORIGINAL=$row[$record['returnvalue']];
	        }
	} else {
	        print "Invalid query.\n";
		if ($type=="web") print "<br>";
	}

	$fixed=0;

	$fixedlines=array();
	if (!empty($ORIGINAL)) {
	        $parts=preg_split("/\";/",$ORIGINAL);
	        $count=count($parts);
	        for ($i=0; $i<$count; $i++) {
	                if (preg_match("/^s:([0-9]+):\"(.*)$/sD",$parts[$i],$matches)) {
				$matches[0]=$matches[0].'"';
	                        if (!empty($matches[1]) && !empty($matches[2])) {
					$newstring=serialize($matches[2]);
					$newstring=preg_replace("/;$/","",$newstring);
						if ($newstring != $matches[0] ) {
							array_push($fixedlines,"Old: ".$matches[0]."\nNew: ".$newstring."\n");
	                                                $fixed++;
							$newstring=preg_replace("/\"$/","",$newstring);
	                                                $parts[$i]=$newstring;
	                                        }
	                        }
	                }
	        }
	        $NEW=addslashes(join("\";",$parts));
	}

	$update_query="update ".$record['table']." set ".$record['returnvalue']." = '".$NEW."' where ".$record['item']." = '".$record['itemvalue']."'";

	if (!($really)) {
		print "Would have applied $fixed fixes to ".$record['table']." -> ".$record['item']." ".$record['itemvalue']."\n";
	} else {
		if ($fixed > 0) {
			mysql_query($update_query,$db);
		}
		print "Applied $fixed fixes to ".$record['table']." -> ".$record['item']." ".$record['itemvalue']."\n";
	}
	foreach ($fixedlines as $line) {
		if ($type=="web") {
			$linetext=htmlspecialchars($line);
		} else {
			$linetext=$line;
		}
		print "$linetext\n";
	}
	print "\n";
}


?>
