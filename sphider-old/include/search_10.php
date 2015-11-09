<?php

    $remote_addr = '';
    $remote_addr = @$_SERVER['REMOTE_ADDR'];
    if (strlen($remote_addr) < '3') {
        die('<br /><p style="text-align:center">With many greetings from the admin of Sphider-plus:<br /><br />Anonymous queries are not answered.<br /><br /> Bye for now.<br />');
    }

    $settings_dir   = "./settings";

    include "./admin/settings/backup/Sphider-plus_default-configuration.php";  //  intermediate for first wakeup

    $language_dir   = "./languages";
    $admin_dir      = "./admin/";
    $image_dir      = "$include_dir/images";
    $textcache_dir  = "$include_dir/textcache";
    $mediacache_dir = "$include_dir/mediacache";
    $shot_dir		= "$include_dir/tmp";
    $stem_dir       = "$include_dir/stemming";
    $result = '';

    if ($block_flood == "1") {
        $flood_file = "$shot_dir/flood_file.txt";
        $flood_sum  = @file($flood_file);   //get all former flood attempts
        $total      = '0';

        foreach ($flood_sum as $value) {
            //  known IP ?
            if (strstr($value, $remote_addr)) {
                $total++;
                if ($total > '3' ) { //  three attempts to flood is quite enough
                    die('<br /><p style="text-align:center">With many greetings from the admin of Sphider-plus:<br /><br />You are known to be evil by trying to flood the search engine several times.<br /><br /> You are blocked now.<br />');
                }
            }
        }
    }

    require_once("$settings_dir/database.php");

    //      get active table prefix for "Search user"
    if ($dbu_act == '1') {
        $mysql_table_prefix = $mysql_table_prefix1;
    }
    if ($dbu_act == '2') {
        $mysql_table_prefix = $mysql_table_prefix2;
    }
    if ($dbu_act == '3') {
        $mysql_table_prefix = $mysql_table_prefix3;
    }
    if ($dbu_act == '4') {
        $mysql_table_prefix = $mysql_table_prefix4;
    }
    if ($dbu_act == '5') {
        $mysql_table_prefix = $mysql_table_prefix5;
    }

    //  get settings for active db and default table-prefix
    $def_config = '';
    $plus_nr    = '';
    @include "".$settings_dir."/db".$dbu_act."/conf_".$mysql_table_prefix.".php";
    if (!$plus_nr) {    //  if not yet defined, use default settings
        $def_config = '1';
        include "/admin/settings/backup/Sphider-plus_default-configuration.php";
    }

    include ("$include_dir/commonfuncs.php");
    extract (getHttpVars());    //  get all the passed input variables

    //      get an intermediate database, just to warm-up
    $db_con = db_connect($mysql_host1, $mysql_user1, $mysql_password1, $database1);

    //  load the language file
    include "$language_dir/$language-language.php";
    // try to get the currently valid language
    if ($auto_lng == 1) {   //  if enabled in Admin settings, get country code of calling client
        if ( isset ( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            $cc = substr( htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2);
            $handle = @fopen ("$language_dir/$cc-language.php","r");
            if ($handle) {
                $language = $cc; // if available set language to users slang
                include "$language_dir/$language-language.php";
                @fclose($handle);
            }
        }
    }
    //echo "\r\n\r\n<br>_SERVER array:<br><pre>";print_r($_SERVER);echo "</pre>\r\n";

    $start_links    = '';
    $domain         = '0';
    $adv            = '';
    $query_t        = '';
    $query_m        = '';
    $type           = '';
    $start          = '';
    $search         = '';
    $results        = '';
    $category       = '0';
    $catid          = '0';
    $media_type     = '';
    $media_only     = '0';
    $text_only      = '';
    $link           = '';
    $title          = '';
    $db             = '0';
    $prefix         = '0';
    $avg            = '';
    $wildcount      = '';
    $one_word       = '';
    $mustbe_and     = '';
    $mark0          = '';
    $tpl_           = array();
    $black          = array();
    $description0   = 'Sphider-plus. The PHP search engine';    //  presented as description tag for blank search form
    $description1   = 'Sphider-plus. The PHP search engine';    //  presented as description tag for result listing
    $cat_sel0       = '';
    $cat_sel0a      = '';
    $cat_sel1       = '';
    $cat_sel2       = '';
    $cat_sel3       = '';
    $cat_sel4       = '';
    $up_advanced    = '';
    $change         = '';
    $smt_button     = '';

    if (isset($_GET['query_t']))
    $query_t = cleaninput(substr(trim($_GET['query_t']),0,255));  //  query for text results
    if (isset($_GET['query_m']))
    $query_m = cleaninput(substr(trim($_GET['query_m']),0,255));  // query for media results
    if (isset($_GET['search']))
    $search = cleaninput(substr(trim($_GET['search']),0,10));
    if (isset($_GET['domain']))
    $domain = cleaninput(substr(trim($_GET['domain']),0,255));
    if (isset($_GET['type']))
    $type = cleaninput(substr(trim($_GET['type']),0,10));
    if (isset($_GET['catid']))
    $catid = cleaninput(substr(trim($_GET['catid']),0,10));
    if (isset($_GET['category']))
    $category = cleaninput(substr(trim($_GET['category']),0,255));
    if (isset($_GET['cat_sel0']))
    $cat_sel0 = cleaninput(substr(trim($_GET['cat_sel0']),0,255));
    if (isset($_GET['cat_sel0a']))
    $cat_sel0a = cleaninput(substr(trim($_GET['cat_sel0a']),0,255));
    if (isset($_GET['cat_sel1']))
    $cat_sel1 = cleaninput(substr(trim($_GET['cat_sel1']),0,255));
    if (isset($_GET['cat_sel2']))
    $cat_sel2 = cleaninput(substr(trim($_GET['cat_sel2']),0,255));
    if (isset($_GET['cat_sel3']))
    $cat_sel3 = cleaninput(substr(trim($_GET['cat_sel3']),0,255));
    if (isset($_GET['cat_sel4']))
    $cat_sel4 = cleaninput(substr(trim($_GET['cat_sel4']),0,255));
    if (isset($_GET['mark']))
    $mark0 = cleaninput(substr(trim($_GET['mark']),0,64));
    if (isset($_GET['results']))
    $results = cleaninput(substr(trim($_GET['results']),0,10));
    if (isset($_GET['start']))
    $start = cleaninput(substr(trim($_GET['start']),0,10));
    if (isset($_GET['start_links']))
    $start_links = cleaninput(substr(trim($_GET['start_links']),0,10));
    if (isset($_GET['adv']))
    $adv = cleaninput(substr(trim($_GET['adv']),0,10));
    if (isset($_GET['up_advanced']))
    $up_advanced = cleaninput(substr(trim($_GET['up_advanced']),0,10));
    if (isset($_GET['change']))
    $change = cleaninput(substr(trim($_GET['change']),0,3));
    if (isset($_GET['media_type']))
    $media_type = cleaninput(substr(trim($_GET['media_type']),0,10));
    if (isset($_GET['media_only']))
    $media_only = cleaninput(substr(trim($_GET['media_only']),0,10));
    if (isset($_GET['link']))
    $link = cleaninput(substr(trim($_GET['link']),0,255));
    if (isset($_GET['title']))
    $title = cleaninput(substr(trim($_GET['title']),0,255));
    if (isset($_GET['db']))
    $db = cleaninput(substr(trim($_GET['db']),0,1));
    if (isset($_GET['prefix']))
    $prefix = cleaninput(substr(trim($_GET['prefix']),0,20));
    if (isset($_GET['sort']))
    $sort = cleaninput(substr(trim($_GET['sort']),0,20));
    if (isset($_GET['smt_button']))
    $smt_button = cleaninput(substr(trim($_GET['smt_button']),0,20));

    //  enter here for suggested query (keyword) by jQuery
    //  because there wasn't any submit button active
    if (strlen($smt_button) < '2') {
        $sug_array  = file('./include/tmp/suggest.txt');
        $media_only = trim($sug_array[1]);
        if ($media_only == '1') {
            $smt_button = 'media';
            $media_only = '1';  //  find only media results for this suggestion
        } else {
            $text_only  = '1';  //  find only text results for this suggestion
            $media_only = '0';
        }
    }

    //  overwrite the suggestion for combined search form
    //  if not separated search buttons are selected in admin backend
    if ($show_media == '1' && !$sep_media && strlen($smt_button) < '2'){
        $text_only  = '0';  //  find text and also media results for this suggestion
        $media_only = '0';
    }

    //  for separate search buttons: find out whether media or text should be searched
    if ($sep_media && $query_m && ($smt_button == $sph_messages['m_search'] || stristr($smt_button, "media"))) {
        $query      = $query_m;  //  search for media only
        $media_only = '1';
    } else {
        $query      = $query_t;  //  search for text only
    }

    $query = preg_replace("@<|>@", "", $query);     //  delete remaining tags from query
    $query = preg_replace("@ +@si", " ", $query);   //  delete duplicate blanks from query

//  if search with 'wildcards' at the end of each search string should become default,
//  uncomment the following row.
    //$query = $query."*";

    //  search with wildcard is valid only for single word queries
    if (strpos($query, " ") && strpos($query, "*")) {
        if (strpos($query, "*") < strpos($query, " ")) {
            $query  = substr($query, 0, strpos($query, " "));
        } else {
            $query  = substr($query, strpos($query, " ")+1);
        }
        $type   = "and";
    }

//if 'Search only Media' should become default,
//uncomment the following row
    //$media_only = '1';

    $start_all   = getmicrotime();

    $nostalgic_phrase = '';
    if (strpos($query, "\"")) {
        $nostalgic_phrase = '1';
        $query = str_replace('"', '', $query);
    }

    //  if requested by query, overwrite search type to AND
    if (strpos($query, " && ")){
        $type   = "and";
    }

    //  if requested by query, overwrite search type to OR
    if (strpos($query, " || ")){
        $type   = "or";
    }

    if($type_search) {  // if Search form settings should be overwritten
        $type = $type_search;
    }

    //      if requested by Search-form, overwrite default db number
    if ($db > 0 && $db <= 5) {

        //  build an array of active db's
        $active = array();
        if ($db1_set == "1") $active[] = "1";
        if ($db2_set == "1") $active[] = "2";
        if ($db3_set == "1") $active[] = "3";
        if ($db4_set == "1") $active[] = "4";
        if ($db5_set == "1") $active[] = "5";

        //  check for active db
        if (in_array($db, $active) ) {
            $dbu_act = $db;
        } else {
            //  inactive db selected
            if ($debug_user == "1") {
                echo "Selected database $db is inactive";
            }
            $query = '';
        }
    }

    //      get active database
    if ($dbu_act == '1') {
        $db_con = db_connect($mysql_host1, $mysql_user1, $mysql_password1, $database1);
        $mysql_table_prefix = $mysql_table_prefix1;
    }

    if ($dbu_act == '2') {

        $db_con = db_connect($mysql_host2, $mysql_user2, $mysql_password2, $database2);
        $mysql_table_prefix = $mysql_table_prefix2;
    }

    if ($dbu_act == '3') {
        $db_con = db_connect($mysql_host3, $mysql_user3, $mysql_password3, $database3);
        $mysql_table_prefix = $mysql_table_prefix3;
    }

    if ($dbu_act == '4') {
        $db_con = db_connect($mysql_host4, $mysql_user4, $mysql_password4, $database4);
        $mysql_table_prefix = $mysql_table_prefix4;
    }

    if ($dbu_act == '5') {
        $db_con = db_connect($mysql_host5, $mysql_user5, $mysql_password5, $database5);
        $mysql_table_prefix = $mysql_table_prefix5;
    }

    //      if requested by Search-form, overwrite default table prefix
    if ($prefix) {
        //  check for valid table prefix
        $sql_query = "SELECT * from ".$prefix."sites";
        $result = $db_con->query($sql_query);
        if ($debug && $db_con->errno) {
            $err_row = __LINE__-2;
            printf("<p><span class='red'>&nbsp;MySQL failure: %s&nbsp;\n<br /></span></p>", $db_con->error);
            if (__FUNCTION__) {
                printf("<p><span class='red'>&nbsp;Found in script: ".__FILE__."&nbsp;&nbsp;row: $err_row&nbsp;&nbsp;in function():&nbsp;".__FUNCTION__."&nbsp;<br /></span></p>");
            } else {
                printf("<p><span class='red'>&nbsp;Found in script: ".__FILE__."&nbsp;&nbsp;row: $err_row&nbsp;<br /></span></p>");
            }
            printf("<p><span class='red'>&nbsp;Script execution aborted.&nbsp;<br /></span>");
            printf("<p><strong>Invalid query string, which caused the SQL error:</strong></p>");
            echo   "<p> $sql_query </p>";
            exit;
        }

        if($result->num_rows) {
            $mysql_table_prefix = $prefix;
        } else {
            //  invalid prefix
            if ($debug_user && $db_con->errno) {
                $err_row = __LINE__-2;
                printf("<p><span class='red'>&nbsp;MySQL failure: %s&nbsp;\n<br /></span></p>", $db_con->error);
                if (__FUNCTION__) {
                    printf("<p><span class='red'>&nbsp;Found in script: ".__FILE__."&nbsp;&nbsp;row: $err_row&nbsp;&nbsp;in function():&nbsp;".__FUNCTION__."&nbsp;<br /></span></p>");
                } else {
                    printf("<p><span class='red'>&nbsp;Found in script: ".__FILE__."&nbsp;&nbsp;row: $err_row&nbsp;<br /></span></p>");
                }
                printf("<p><span class='red'>&nbsp;Script execution aborted.&nbsp;<br /></span>");
                printf("<p><strong>Invalid table prefix</strong></p>");
                exit;
            }
            $query = '';
        }
    }

    //  if search form has overwritten the prior db and table-prefix, get correct settings
    $def_config = '';
    $plus_nr    = '';
    @include "".$settings_dir."/db".$dbu_act."/conf_".$mysql_table_prefix.".php";

    if (!$plus_nr) {
        $def_config = '1';
        include "/admin/settings/backup/Sphider-plus_default-configuration.php";
    }

    //clear all previous XML results
    if ($out == 'xml') {
        require_once ("$include_dir/xml.php");
        @unlink("".$xml_dir."/text_".$xml_name."");             // remove last XML file holding text results
        @unlink("".$xml_dir."/media_".$xml_name."");            // remove last XML file holding media results
        @unlink("".$xml_dir."/link_".$xml_name."");             // remove last XML file holding link results
        @unlink("".$xml_dir."/multiple_link_".$xml_name."");    // remove last XML file holding multiple link results
    }

    if ($block_flood == "1") {
        //  block attempts to flood the search engine with queries
        $sql_query = "SELECT * from ".$mysql_table_prefix."query_log where ip = '$remote_addr' order by time desc  limit 0,10 ";
        $result = $db_con->query($sql_query);
        if ($debug && $db_con->errno) {
            $err_row = __LINE__-2;
            printf("<p><span class='red'>&nbsp;MySQL failure: %s&nbsp;\n<br /></span></p>", $db_con->error);
            if (__FUNCTION__) {
                printf("<p><span class='red'>&nbsp;Found in script: ".__FILE__."&nbsp;&nbsp;row: $err_row&nbsp;&nbsp;in function():&nbsp;".__FUNCTION__."&nbsp;<br /></span></p>");
            } else {
                printf("<p><span class='red'>&nbsp;Found in script: ".__FILE__."&nbsp;&nbsp;row: $err_row&nbsp;<br /></span></p>");
            }
            printf("<p><span class='red'>&nbsp;Script execution aborted.&nbsp;<br /></span>");
            printf("<p><strong>Invalid query string, which caused the SQL error:</strong></p>");
            echo   "<p> $sql_query </p>";
            exit;
        }
        $result_array   = Array();
        $last_queried   = Array();
        while ($row = $result->fetch_array(MYSQLI_NUM)) {
            $result_array[] = $row;;
            $last_queried[] = retMktimest($row[1]);
        }

        if (time() - $last_queried[1] <= '2') {
            //  save all info to the flood info file
            $fp1 = fopen("$flood_file", "a");
            if (!$fp1) {
                echo "  <br />
                            <div style=\"text-align:center;\">
                                <p class='warnadmin cntr'><br />
                                Unable to write into text cache folder.<br /><br />Script execution aborted for security reasons.<br />
                                <br /><br /></p>
                            </div>
                        </div>
                      </body>
                    </html>
                        ";
                die();
            } else {
                $value  = '';
                $delim  = "-";
                $value .= $remote_addr;        //  client IP
                $value .= $delim;
                $value .= $result_array[0][0];  //  query string
                $value .= $delim;
                $value .= $last_queried[0];     //  last queried
                $value .= "$delim\r\n";
                fwrite($fp1, $value);
                fclose($fp1);
            }
            die('<br /><p style="text-align:center">With many greetings from the admin of Sphider-plus:<br /><br />You tried to flood Sphider-plus with queries.<br /><br />Bye for now.<br /></p>');
        }
    }

    //  block all queries from known evil user-agents
    if ($kill_black_uas && @$_SERVER['HTTP_USER_AGENT']) {
        $client_ua = strtolower(htmlspecialchars(@$_SERVER['HTTP_USER_AGENT']));
        //$client_ua = "mozilla/2.0"; //  test for evil User-Agent string
        foreach ($black_uas as $value) {    //  check all known evil User-Agent strings
        	$value = strtolower(htmlspecialchars($value));
            if (trim($value) == $client_ua) {
                if ($debug_user) {
                    die("<br />With respect to the corresponding Admin setting,<br />no results are presented for the known evil User-Agent: <strong>'$client_ua'</strong>");
                } else {
                    die("<br />No results found.");
                }
            }
        }
    }

    $client_ip = @$_SERVER['REMOTE_ADDR'];
    //$client_ip = "174.129.228.68";  //test
    //  block all queries from meta search engines (restricted to IPv4)
    if ($kill_black_ips && (false===strrpos($_SERVER['REMOTE_ADDR'], ":"))) {

        //$client_ip = "174.129.228.67";  //test for known IP used by Amazon
        //$client_ip = "66.249.72.170";  //test for known IP range used by Google
        $client_ip_long = ip2long($client_ip);
        foreach ($black_ips as $value) {    //  check all forbidden single IPs and IP ranges

            if(!strpos($value, "-")) {      //  enter here for single IPs
                $value_long = ip2long(trim($value));

                if ($client_ip_long == $value_long) {
                    if ($debug_user) {
                        die("<br />With respect to the corresponding Admin setting,<br />no results are presented for the IP <strong>$client_ip</strong><br />Known to be used by a Meta search engine, or has been former evil.");
                    } else {
                        die("<br />No results found.");
                    }
                }
            } else {    //  enter here for IP range
                $range = explode('-', trim($value)); // separate the low border IP from the high border
                if($client_ip_long >= ip2long($range[0]) && $client_ip_long <= ip2long($range[1])) {
                    if ($debug_user) {
                        $value = str_replace("-", " - ", $value);
                        die("<br />With respect to the corresponding Admin setting,<br />no results are presented for the IP range <strong> $value </strong><br /> Known to be used by a Meta search engine.<br />Here they used: <strong>$client_ip</strong>");
                    } else {
                        die("<br />No results found.");
                    }
                }
            }
        }
    }

    if ($use_ids == 1 && $def_config != 1){     // if Intrusion Detection System should be used
        require_once ("$include_dir/ids_handler.php");
    }

    //  does the IDS detect an attack?
    if (strlen($result) > 13 && $def_config != 1) {
        //  get impact of intrusion
        $len = strpos($result, "<")-13;
        $res = trim(substr($result, '13', $len));
        if ($res >= $ids_warn) {
            $mytitle .= " - IDS supervisor";
            require_once "".$template_dir."/html/010_html_header.html";
            echo "      <br /><br />
            <div class='headline cntr'>
                IDS result message
            </div>
            <br /><br />
            $result
            <br />
            <div class='cntr warnadmin'>
                <br />
                Further input blocked by the Sphider-plus supervisor, because the
                <br /><br />
                Intrusion Detection System noticed the above attempt to attack this search engine.
                <br /><br />
            </div>
            <div class='headline cntr'>
                &nbsp;
            </div>
            <br /><br />
        </body>
    </html>
                ";
            die();
        }
    }
    //echo "\r\n\r\n<br>_SERVER Array:<br><pre>";print_r($_SERVER);echo "</pre>\r\n";
    //  already known as an eval IP by the IDS ?
    if ($ids_blocked == 1 && $def_config != 1) {
        $blocked = '';
        if ( isset ( $_SERVER['REMOTE_ADDR'] ) ) {      //  get actual IP from user
            $new_ip = htmlspecialchars($_SERVER['REMOTE_ADDR']);
            $handle = @fopen ("$include_dir/IDS/tmp/phpids_log.txt","r");
            if ($handle) {      //      read IDS log-file
                $lines = @file("$include_dir/IDS/tmp/phpids_log.txt");
                @fclose($handle);
            }

            foreach ($lines as $thisline) {                             //  analyze all stored intrusion attempts
                preg_match("@\"(.*?)\",(.*?),(.*?),@",$thisline, $regs);
                if ($new_ip == urldecode($regs[1]) && $regs[3] >= $ids_stop) {     //  if actual IP is known to be evil and impact was significant
                    $blocked = '1';
                }
            }

            if ($blocked) {
                $mytitle .= " - IDS supervisor";
                require_once "".$template_dir."/html/010_html_header.html";
                echo "      <br /><br />
                <div class='headline cntr'>
                    IDS message: You are known to be evil due to former attacks
                </div>
                <br /><br />
                <div class='cntr warnadmin'>
                    <br />
                    Further access blocked by the Sphider-plus supervisor, because the
                    <br /><br />
                    Intrusion Detection System already noticed an attempt to attack this search engine.
                    <br /><br />
                </div>
                <div class='headline cntr'>
                    &nbsp;
                </div>
                <br /><br />
            </body>
        </html>
                    ";
                die();
            }
        }
    }

    //  overwrite the configuration setting with respect to users decision
    if($mark0) {
        $mark = $mark0;
    }

    if ($mb == 1) {
        mb_internal_encoding("UTF-8");      //  define standard charset for mb functions
    }

    if ($debug == '0') {
        if (function_exists("ini_set")) {
            ini_set("display_errors", "0");
        }
    }

    if ($show_media == 1) {
        include "$include_dir/search_media.php";
    }

    //  load the final language file, regarding the config definition
    include "$language_dir/$language-language.php";
    // try to get the currently valid language
    if ($auto_lng == 1) {   //  if enabled in Admin settings, get country code of calling client
        if ( isset ( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
            $cc = substr( htmlspecialchars($_SERVER['HTTP_ACCEPT_LANGUAGE']), 0, 2);
            $handle = @fopen ("$language_dir/$cc-language.php","r");
            if ($handle) {
                $language = $cc; // if available set language to users slang
                include "$language_dir/$language-language.php";
                @fclose($handle);
            }
        }
    }
    //echo "\r\n\r\n<br>Info array:<br><pre>";print_r($_SERVER);echo "</pre>\r\n";

    if($user_lng) {  // if Admin settings should be overwritten
        $language = $user_lng;
    }

    //  check for multiple category selection
    $cat_sel = '';
    if ($group_name_0 || $group_name_1) {
        $cat_sel    = 1;    //  activate multiple category search
        $category   = -1;
    }

    //  now replace some variables with actual Admin settings as of the $dbu_act config file
    include "commons.php";

    require_once("$include_dir/searchfuncs.php");
    require_once("$include_dir/categoryfuncs.php");

    include "$language_dir/$language-language.php";

    if ($mark == $sph_messages['markbold']) $mark = 'markbold';
    if ($mark == $sph_messages['markred']) $mark = 'markred';
    if ($mark == $sph_messages['markyellow']) $mark = 'markyellow';
    if ($mark == $sph_messages['markgreen']) $mark = 'markgreen';
    if ($mark == $sph_messages['markblue']) $mark = 'markblue';

    if ($catid && is_numeric($catid)){
        $cattree = array(" ",$sph_messages['Categories']);
        $cat_info = get_category_info($catid);
        foreach ($cat_info['cat_tree'] as $_val){
            $thiscat = $_val['category'];
            array_push($cattree," > ",$thiscat);
        }
        $cattree = implode($cattree);
    }

    //now follow the submit button of search form for text and media search
    if ($smt_button) {
        if ($smt_button == $sph_messages['t_search'] || stristr($smt_button, "text")) {
            $text_only = "1";
        }
        if ($smt_button == $sph_messages['m_search'] || stristr($smt_button, "media")) {
            $media_only = "1";
        }
    }

    $strictpos = '';
    $strictpos = strpos($query, '!');
/*
    if (!strstr($query, " ")) {
        $strictpos = '0';
        $query = "!".$query;
    }
*/


    $wildcount = substr_count($query, '*');
    if ($wildcount || $strictpos === 0) {
        if ($type != 'and') {
            $mustbe_and = '1';
        }
        $type = 'and';                  //  if wildcard, or strict search mode, switch always to AND search
        $strict_search  = '1';          //  prevent wildcard for quotes search
        if(strpos($query, " ", 3)) {
            $query = substr($query, 0, strpos($query, " ", 3)); // only the first word of the query will be used for these search modes
            $one_word = '1';
        }
    }

    if ($type != "or" && $type != "and" && $type != "phrase" && $type != "tol") {
        $type = "and";
    }
    /*
     if (preg_match("/[^a-z0-9-.]+/", $domain)) {    //prevents domain search for localhost domain
     $domain="";
     }
     */
    if ($results != "") {
        $results_per_page = $results;
    }

    if (!is_numeric($catid)) {
        $catid = "";
    }

    if (!is_numeric($category)) {
        $category = "-1";
    }

    $checked_cat = '';
    $checked_all = '';

    if ($category == '-1') {
        $checked_all = 'checked="checked"';   //  remember that last query was for all sites
    } else {
        $checked_cat = 'checked="checked"';   //  remember that last query was in category
    }

    if ($catid && is_numeric($catid)) {
        $result = sqli_fetch_all('SELECT * FROM '.$mysql_table_prefix.'categories WHERE category_id='.(int)$_REQUEST['catid']);
        $tpl_['category'] = $result[0]['category'];
    }

    $has_categories = 0;
    $count_level0   = sqli_fetch_all('SELECT * FROM '.$mysql_table_prefix.'categories WHERE parent_num=0');

    if ($count_level0) {
        $has_categories = $count_level0[0]['0'];
    }

    $type_rem   = $type;
    $result_rem = $results_per_page;
    $mark_rem   = $mark;
    $catid_rem  = $catid;
    $cat_rem    = $category;

    $query = str_replace("\\", "", $query);         //      kill remained backslash
    $query = preg_replace("/&apos;/", "'", $query); //      replace '&nbsp;' with " ' "  else: print quote_replace($query);
    $query_t = str_replace("\\", "", $query_t);     //      kill remained backslash
    $query_m = str_replace("\\", "", $query_m);     //      kill remained backslash

        if ($show_categories && $change != "1") {
            if ($_REQUEST['catid']  && is_numeric($catid)) {
                $cat_info = get_category_info($catid);
            } else {
                $cat_info = get_categories_view();
            }

            //  extract all categories and additional selectors form main category array
            $group_sel0     = array();
            $group_all      = array();
            $cat_sel_all    = $sph_messages['all'];
            $group_all[]    = $cat_sel_all;

            foreach ($cat_info['main_list'] as $this_sel) {
                $group_sel0[] = $this_sel['category'];
            }
            $group_sel0 = array_unique($group_sel0);                //  kill duplicates
            usort($group_sel0, "cmp_val");                          //  sort alphpabetic
            $group_sel0 = array_merge( $group_all, $group_sel0);    //  add the default on top

            $group_sel0a    = array();
            $group_sel0a[]  = '';
            foreach ($cat_info['main_list'] as $this_sel) {
                if ($this_sel['category']) {
                    $group_sel0a[] = $this_sel['category'];
                }
            }
            $group_sel0a = array_unique($group_sel0a);
            usort($group_sel0a, "cmp_val");
            //$group_sel0a = array_merge( $group_all, $group_sel0a);

            if ($group_name_1) {
                $group_sel1     = array();
                //$group_sel1[]   = $cat_sel_all;
                foreach ($cat_info['main_list'] as $this_sel) {
                    if ($this_sel['group_sel0']) {
                        $group_sel1[] = $this_sel['group_sel0'];
                    }
                }
                $group_sel1 = array_unique($group_sel1);
                usort($group_sel1, "cmp_val");
                $group_sel1 = array_merge( $group_all, $group_sel1);

            }

            if ($group_name_2) {
                $group_sel2     = array();
                foreach ($cat_info['main_list'] as $this_sel) {
                    if ($this_sel['group_sel1']) {
                        $group_sel2[] = $this_sel['group_sel1'];
                    }
                }
                $group_sel2 = array_unique($group_sel2);
                usort($group_sel2, "cmp_val");
                $group_sel2 = array_merge( $group_all, $group_sel2);
            }


            if ($group_name_3) {
                $group_sel3     = array();
                foreach ($cat_info['main_list'] as $this_sel) {
                    if ($this_sel['group_sel2']) {
                        $group_sel3[] = $this_sel['group_sel2'];
                    }
                }
                $group_sel3 = array_unique($group_sel3);
                usort($group_sel3, "cmp_val");
                $group_sel3 = array_merge( $group_all, $group_sel3);
            }

            if ($group_name_4) {
                $group_sel4     = array();
                foreach ($cat_info['main_list'] as $this_sel) {
                    if ($this_sel['group_sel3']) {
                        $group_sel4[] = $this_sel['group_sel3'];
                    }
                }
                $group_sel4 = array_unique($group_sel4);
                usort($group_sel4, "cmp_val");
                $group_sel1 = array_merge( $group_all, $group_sel4);
            }
        }

        if ($cat_sel0a <= $cat_sel0 || $cat_sel0a == $cat_sel_all) {  //  search only for multiple category selection
            $cat_sel0a = $cat_sel0;     //  $cat_sel0a must be > $cat_sel0
        }

        //      otput of HTML-header
        if (!$embedded) {
            include "".$template_dir."/html/010_html_header.html";  //  complete HTML header
        } else {
            include "".$template_dir."/html/011_html_header.html";  //  only the Sphider-plus relevant part of the HTML header
        }


?>