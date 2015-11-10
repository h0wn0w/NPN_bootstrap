<?php

    error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE & ~E_STRICT);

    $com_in     = array();      //  intermediate array for ignored words
    $all_in     = array();      //  intermediate array for ignored words
    $common     = array();      //  array fo ignored words
    $ext        = array();      //  array for ignored file suffixes
    $whitelist  = array();      //  array for whitelist
    $white      = array();
    $white_in   = array();
    $blacklist  = array();      //  array for blacklist
    $black_in   = array();
    $uas_in     = array();      //  intermediate array for evil User Agents
    $ips_in     = array();      //  intermediate array for bad IPs
    $black_uas  = array();      // User Agent strings belonging to evil bots
    $black_ips  = array();      // IPs belonging to Google, MSN, Amazon, etc bots
    $black      = array();
    $image		= array();	    //	array for image suffixes
    $audio		= array();		//	array for audio suffixes
    $video		= array();	    //	array for video suffixes
    $pres_not   = array();      //      array for pre classes not to be indexed
    $uls_not   = array();       //      array for ul classes not to be indexed
    $divs_not   = array();      //      array for divs not to be indexed
    $divs_use   = array();      //      array for divs to be indexed
    $docs       = array();      //      array holding  a list of documents to be indexed
    $elements_not   = array();  //      array for HTML elements not to be indexed
    $elements_use   = array();  //      array for HTML elements to be indexed
    $slv        = array();      //      array of most common Second Level Domains

    //  currently not required
    //$mysql_charset = conv_mysqli($home_charset); //  convert the home._charset to MySQL format

    if (is_dir($common_dir)) {
        $handle = opendir($common_dir);
        if ($use_common == 'all') {
            while (false !== ($common_file = readdir($handle))) {   //  get all common files
                if (strpos($common_file, "ommon_")) {
                    $act = @file($common_dir.$common_file);         //  get content of this common file
                    $all_in = array_merge($all_in, $act);           //  build a complete array of common words
                }
            }
        }

        if ($use_common != 'all' && $use_common != 'none') {
            $all_in = @file("".$common_dir."common_".$use_common.".txt");         //  get content of language specific common file
        }

        if (is_array($all_in)) {
            while (list($id, $word) = each($all_in))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $com_in[] = $word;
            }
        }

        if (is_array($com_in)) {
            while (list($id, $word) = each($com_in))
            $common[trim($word)] = 1;
        }

        if ($use_white1 == '1' || $use_white2 == '1') {
            $white_in = @file($common_dir.'whitelist.txt');    //  get all words to enable page indexing
            foreach ($white_in as $val) {
                if ($case_sensitive == '0') {
                    $val = lower_case($val);
                }
                $val = @iconv($home_charset,"UTF-8",$val);
                if (preg_match("/\S/", $val)) {    //  remove empty entries from list
                    $white[] = addslashes($val);
                }
            }

            while (list($id, $word) = each($white))
            $whitelist[] = trim($word);
            $whitelist = array_unique($whitelist);
            sort($whitelist);
        }

        $suffix     = @file($common_dir.'suffix.txt');      //  get all file suffixes to be ignored during index procedure
        $black_in   = @file($common_dir.'blacklist.txt');   //  get all words to prevent indexing of page
        $uas_in     = @file($common_dir.'black_uas.txt');   //  get all evil user-agent strings
        $ips_in     = @file($common_dir.'black_ips.txt');   //  get all Meta search engine IPs
        $image      = @file($common_dir.'image.txt');       //  get all image suffixes to be indexed
        $audio      = @file($common_dir.'audio.txt');       //  get all audio suffixes to be indexed
        $video      = @file($common_dir.'video.txt');       //  get all audio suffixes to be indexed
        $pres_not   = @file($common_dir.'pres_not.txt');    //  get all pre tag classes not to be indexed (Admin selected)
        $uls_not    = @file($common_dir.'uls_not.txt');     //  get all ul tag classes not to be indexed (Admin selected)
        $divs_not   = @file($common_dir.'divs_not.txt');    //  get all div's not to be indexed (Admin selected)
        $divs_use   = @file($common_dir.'divs_use.txt');    //  get all div's to be indexed (Admin selected)
        $docu       = @file($common_dir.'docs.txt');        //  get all document suffixes to be indexed (Admin selected)
        $elements_not   = @file($common_dir.'elements_not.txt');    //  get all HTML elements to not to be indexed (Admin selected)
        $elements_use   = @file($common_dir.'elements_use.txt');    //  get all HTML elements to be indexed (Admin selected)
        $sld        = @file($common_dir.'sld.txt');         //  get all SLDs

        closedir($handle);

        //  $ext is required only for index procedure, not for search function
        if (strpos($_SERVER["SCRIPT_NAME"], "admin")) {
            while (list($id, $word) = each($suffix))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $ext[] = trim($word);
            }

            //  if JavaScript redirections should not be followed
            if (!$js_reloc) {
                $ext[] = "js";  //  add suffix for JavaScript files
            }

            $ext = array_unique($ext);
            sort($ext);
        }

        if ($use_black == 1) {
            foreach ($black_in as $val) {
                if ($case_sensitive == '0') {
                    $val = lower_case($val);
                }
                $val = @iconv($home_charset,"UTF-8",$val);
                if (preg_match("/\S/", $val)) {    //  remove empty entries from list
                    $black[] = trim($val);
                }
            }

            while (list($id, $word) = each($black))
            $blacklist[] = $word;
            $blacklist = array_unique($blacklist);
            sort($blacklist);
        }

        if ($index_image == 1) {
            while (list($id, $word) = each($image))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $imagelist[] = trim(strtolower($word));
            }
            $imagelist = array_unique($imagelist);
            sort($imagelist);
        }

        if ($index_audio == 1) {
            while (list($id, $word) = each($audio))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $audiolist[] = trim(strtolower($word));
            }
            $audiolist = array_unique($audiolist);
            sort($audiolist);
        }

        if ($index_video == 1) {
            while (list($id, $word) = each($video))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $videolist[] = trim(strtolower($word));
            }
            $videolist = array_unique($videolist);
            sort($videolist);
        }

        if ($not_pres == 1) {
            while (list($id, $word) = each($pres_not))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $not_prelist[] = trim($word);
            }
            $not_prelist = array_unique($not_prelist);
            sort($not_prelist);
        }

        if ($not_uls == 1) {
            while (list($id, $word) = each($uls_not))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $not_ullist[] = trim($word);
            }
            $not_ullist = array_unique($not_ullist);
            sort($not_ullist);
        }

        if ($not_divs == 1) {
            while (list($id, $word) = each($divs_not))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $not_divlist[] = trim($word);
            }
            $not_divlist = array_unique($not_divlist);
            sort($not_divlist);
        }

        if ($use_divs == 1) {
            while (list($id, $word) = each($divs_use))
            if (preg_match("/\S/", $word)) {
                $use_divlist[] = trim($word);
            }
            $use_divlist = array_unique($use_divlist);
            sort($use_divlist);
        }

        if ($only_docs == 1) {
            while (list($id, $word) = each($docu))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $docs[] = trim(strtolower($word));
            }
            $docs = array_unique($docs);
            sort($docs);
        }

        if ($not_elems == 1) {
            while (list($id, $word) = each($elements_not))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $not_elementslist[] = trim($word);
            }
            $not_elementslist = array_unique($not_elementslist);
            sort($not_elementslist);
        }

        if ($use_elems == 1) {
            while (list($id, $word) = each($elements_use))
            if (preg_match("/\S/", $word)) {
                $use_elementslist[] = trim($word);
            }
            $use_elementslist = array_unique($use_elementslist);
            sort($use_elementslist);
        }

        if ($redir_host == 1 || $other_host == 1) {
            while (list($id, $word) = each($sld))
            $sldlist[] = trim($word);
            $sldlist = array_unique($sldlist);
            sort($sldlist);
        }

        if ($kill_black_uas == 1) {
            while (list($id, $word) = each($uas_in))
            if (preg_match("/\S/", $word)) {    //  remove empty entries from list
                $black_uas[] = $word;
            }
        }

        if ($kill_black_ips == 1) {
            foreach($ips_in as $word) {
                $word = trim($word);
                if (strpos($word, " ")) {
                    $word = trim(substr($word, 0, strpos($word, " ", 1)));
                }
                if (!strstr($word, "#") && strlen($word) > 3) { //  remove empty entries and comment rows
                    $black_ips[] = $word;
                }
            }

        }
    }

?>