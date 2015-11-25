<?php
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE & ~E_STRICT);
    $ddt        = '';
    $ddt_set    = '';
    $ddt = date_default_timezone_get();    //  try to read the server defaults
    if ($ddt) {
        $ddt_set = date_default_timezone_set($ddt);
        if (!$ddt_set){    //  this will prevent 'STRICT' error messages for date() and time() functions
            die ("<br /><br /><strong>&nbsp;&nbsp;&nbsp;&nbsp;The Sphider-plus scripts are unable to set the date_default_timezone on your server.<br /><br /><strong>&nbsp;&nbsp;&nbsp;&nbsp;Please enable this PHP function. Script execution aborted for security reasons.</strong>");
        }
    } else {
        die("<br /><br /><strong>&nbsp;&nbsp;&nbsp;&nbsp;The Sphider-plus scripts are unable to read the date_default_timezone from your server.<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;Please enable this PHP function. Script execution aborted for security reasons.</strong>");
    }

    set_time_limit (0);

    $database1  = '';
    $plus_nr    = '';
    $inst_dir   = '';
    $install_dir     = '';
    $dir0       = '';
    //  For command line operation, try to correct the working directory
    $dir0 = str_replace('\\', '/', __DIR__);
    if (strlen($dir0) < '2') {
        echo "<br />Attention: Command line operation may fail for your Sphider-plus installation.<br />";
        echo " Please install PHP version 5.3 or newer for proper operation. <br /><br />";
    } else {
        chdir($dir0);
    }
    // now get the actual directory
    $dir = str_replace('\\', '/', getcwd());
    if (!strpos($dir, "/admin")) {
        echo "Unable to set the admin directory to Sphider-plus installation folder. Execution of admin backend aborted.";
        die ('');
    }

    //  get the root URL of this Sphider-plus installation
    $inst_dir       = substr($dir0, 0, strpos($dir0, "/admin"));
    $install_dir    = str_replace('\\', '/',$_SERVER['DOCUMENT_ROOT']);
    if (strpos($install_dir, ":")) {
        $install_dir    = str_replace('\\', '/',$_SERVER['REQUEST_URI']);   //  XAMPP prefers this
    }

    if (strpos($install_dir, "/admin")) {
        $install_dir = substr($install_dir, 0, strpos($install_dir, "/admin"));
    }

    //  prepare the HTML base tag for Sphider-plus installation ($install_url)  and admin script ($admin_url)
    $scheme     = $_SERVER['HTTPS'];
    if (!$scheme){
        $scheme = "http";
    } else {
        $scheme = "https";
    }
    $host           = $_SERVER['HTTP_HOST'];
    $uri            = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $file           = 'admin.php';
    $admin_url      = "$scheme://$host$uri/$file";
    $install_url    = substr($admin_url, 0, strpos($admin_url, "/admin"));

    $admin_dir      = "$inst_dir/admin";
    $log_dir        = "$admin_dir/log";
    $tmp_dir        = "$admin_dir/tmp";
    $admset_dir     = "$admin_dir/settings";
    $admback_dir    = "$admin_dir/settings/backup";
    $smap_dir       = "$admin_dir/sitemaps";
    $url_dir        = "$admin_dir/urls";
    $thumb_folder   = "$admin_dir/thumbs";          //  temporary folder for thumbnails during index procedure
    $thumb_url      = "$scheme://$host$uri/thumbs";
    $url_path       = "$admin_dir/urls/";           //  folder for URL import / export will be handled

    $template_dir   = "$inst_dir/templates";        //  folder which holds in subfolders the different templates like 'Pure', 'Sphider-plus' etc.
    $template_url   = "$install_url/templates";     //  Base template URL for HTML includes
    $include_dir    = "$inst_dir/include";
    $include_url    = "$install_url/include";    

    $image_dir      = "$include_dir/images";
    $textcache_dir  = "$include_dir/textcache";
    $mediacache_dir = "$include_dir/mediacache";
    $thumb_dir      = "$include_dir/thumbs";        //  temporary folder for thumbnails for search algorithm
    $flood_dir      = "$include_dir/tmp";           //  temporary folder for web-shots and flood file

    $settings_dir   = "$inst_dir/settings";
    $converter_dir  = "$inst_dir/converter";
    $language_dir   = "$inst_dir/languages";
    $xml_dir        = "$inst_dir/xml";              //  folder for XML results

    include "$settings_dir/database.php";
    if (!$database1) {
        // HTML5 header";
        echo "<!DOCTYPE HTML>\n";
        echo "  <head>\n";
        echo "      <base href = $admin_url>\n";
        echo "      <title>Sphider-plus administrator warning</title>\n";
        // meta data
        echo "      <meta charset='UTF-8'>\n";
        echo "      <meta name='public' content='all'>\n";
        echo "      <meta http-equiv='expires' content='0'>\n";
        echo "      <meta http-equiv='pragma' content='no-cache'>\n";
        echo "      <meta http-equiv='X-UA-Compatible' content='IE=9' />\n";

        echo "      <link href='$template_url/html/sphider-plus.ico' rel='shortcut icon' type='image/x-icon' />\n";
        echo "  </head>\n";
        echo "  <body>
    <br /><br />
    <div style=\"text-align:center;\">
        <strong>Attention:</strong> Unable to load the database configuration file.<br />
        Please reinstall Sphider-plus by using the original scripts as per download.<br />
        <br /><br />
    </div>
  </body>
</html>
            ";
        die ();
    }

    //      get active database for Admin
    if ($dba_act == '1') {
        $db_con             = adb_connect($mysql_host1, $mysql_user1, $mysql_password1, $database1) ;
        $database           = $database1;
        $mysql_table_prefix = $mysql_table_prefix1;
    }

    if ($dba_act == '2') {
        $db_con             = adb_connect($mysql_host2, $mysql_user2, $mysql_password2, $database2) ;
        $database           = $database2;
        $mysql_table_prefix = $mysql_table_prefix2;
    }

    if ($dba_act == '3') {
        $db_con             = adb_connect($mysql_host3, $mysql_user3, $mysql_password3, $database3) ;
        $database           = $database3;
        $mysql_table_prefix = $mysql_table_prefix3;
    }

    if ($dba_act == '4') {
        $db_con             = adb_connect($mysql_host4, $mysql_user4, $mysql_password4, $database4) ;
        $database           = $database4;
        $mysql_table_prefix = $mysql_table_prefix4;
    }

    if ($dba_act == '5') {
        $db_con             = adb_connect($mysql_host5, $mysql_user5, $mysql_password5, $database5) ;
        $database           = $database5;
        $mysql_table_prefix = $mysql_table_prefix5;
    }

    $default = '';
    include "".$settings_dir."/db".$dba_act."/conf_".$mysql_table_prefix.".php";

    if (!$plus_nr) {
        include "$admin_dir/settings/backup/Sphider-plus_default-configuration.php";
        $default = '1';
    }

    //  Repeat detection of installation directory.
    //  Eventually the Sphider-plus installation was moved to another server,
    //  so that even default values are invalid.
    $inst_dir   = '';
    $install_dir     = '';

    //  For command line operation, try to correct the working directory
    $dir0 = str_replace('\\', '/', __DIR__);
    chdir($dir0);
    // now get the actual directory
    $dir = str_replace('\\', '/', getcwd());

    if (!strpos($dir, "/admin")) {
        echo "Unable to set the admin directory to Sphider-plus installation folder. Execution of admin backend aborted.";
        die ('');
    }
    //  get the root URL of this Sphider-plus installation
    $inst_dir       = substr($dir0, 0, strpos($dir0, "/admin"));
    $install_dir    = str_replace('\\', '/',$_SERVER['DOCUMENT_ROOT']);
    if (strpos($install_dir, ":")) {
        $install_dir         = str_replace('\\', '/',$_SERVER['REQUEST_URI']);   //  XAMPP prefers this
    }

    if (strpos($install_dir, "/admin")) {
        $install_dir = substr($install_dir, 0, strpos($install_dir, "/admin"));
    }

    //  prepare the HTML base tag for this script installation
    $scheme     = $_SERVER['HTTPS'];
    if (!$scheme){
        $scheme = "http";
    } else {
        $scheme = "https";
    }
    $host           = $_SERVER['HTTP_HOST'];
    $uri            = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $file           = 'admin.php';
    $admin_url      = "$scheme://$host$uri/$file";
    $install_url    = substr($admin_url, 0, strpos($admin_url, "/admin"));

    //  re-defining the most important folder
    $admin_dir      = "$inst_dir/admin";
    $log_dir        = "$admin_dir/log";
    $tmp_dir        = "$admin_dir/tmp";
    $admset_dir     = "$admin_dir/settings";
    $admback_dir    = "$admin_dir/settings/backup";
    $smap_dir       = "$admin_dir/sitemaps";
    $url_dir        = "$admin_dir/urls";
    $thumb_folder   = "$admin_dir/thumbs";      //  temporary folder for thumbnails during index procedure
    $url_path       = "$admin_dir/urls/";       //  folder relative to .../admin/ where all the files for URL import / export will be handled

    $template_dir   = "$inst_dir/templates";    //  folder which holds in subfolders the different templates like 'Pure', 'Sphider-plus' etc.
    $template_url   = "$install_url/templates"; //  Base template URL for HTML includes
    $include_dir    = "$inst_dir/include";
    $include_url    = "$install_url/include";

    $image_dir      = "$include_dir/images";
    $textcache_dir  = "$include_dir/textcache";
    $mediacache_dir = "$include_dir/mediacache";
    $thumb_dir      = "$include_dir/thumbs";    //  temporary folder for thumbnails during search procedure
    $flood_dir      = "$include_dir/tmp";       //  temporary folder for web-shots and flood file

    $settings_dir   = "$inst_dir/settings";
    $converter_dir  = "$inst_dir/converter";
    $language_dir   = "$inst_dir/languages";
    $xml_dir        = "$inst_dir/xml";          //  folder for XML results
    //  end of repetition

    //  here to continue after re-defining all the above
    include "$include_dir/commonfuncs.php";

    if ($debug == '0') {
        if (function_exists("ini_set")) {
            ini_set("display_errors", "0");
        }
    }
    //  check if multibyte functions are available
    //  this check is required only for first call of admin.php
    //  later on this check is also performed by configset.php together with a warning message
    $mb = '';
    if (function_exists('mb_internal_encoding')) {
        if(function_exists('mb_stripos')) {
            $mb = '1';
        }
    }
    if ($mb != 1) {
        $mb = '0';
    }

    $template_path  = "$template_url/$template";

    //require_once('phpSecInfo/PhpSecInfo.php');        //   (might not work on Shared Hosting server)
    include("$admin_dir/geoip.php");

    // HTML5 header";
    echo "<!DOCTYPE HTML>\n";
    echo "  <head>\n";
    echo "      <base href = $admin_url>\n";
    echo "      <title>Sphider-plus administrator</title>\n";
    // meta data
    echo "      <meta charset='UTF-8'>\n";
    echo "      <meta name='public' content='all'>\n";
    echo "      <meta http-equiv='expires' content='0'>\n";
    echo "      <meta http-equiv='pragma' content='no-cache'>\n";
    echo "      <meta http-equiv='X-UA-Compatible' content='IE=9' />\n";

    echo "      <link href='$template_url/html/sphider-plus.ico' rel='shortcut icon' type='image/x-icon' />\n";
    echo "      <link rel='stylesheet' type='text/css' href='$template_path/adminstyle.css' />\n";
    echo "      <script type='text/javascript' src='confirm.js'></script>
      <script type='text/javascript'>
          function JumpBottom() {
              window.scrollTo(0,1000);
          }
      </script>\n";
    echo "  </head>\n";
    echo "  <body>\n";

    $php_vers = phpversion();
    if (preg_match('/^4\./', trim($php_vers)) == '1') {
        echo "<br />
                <div id='main'>
                <h1 class='cntr'>
                Sphider-plus. The PHP Search Engine
                </h1>
                    <div class='cntr warnadmin'>
                        <br />
                        <p>Your current PHP version is $php_vers</p>
                        <p>Sorry, but Sphider-plus v. $plus_nr requires PHP 5.x</p>
                        <br /><br />
                    </div>
                </div>
                </body>
                </html>
            ";
        die ('');
    }

    include "$admin_dir/auth.php";

    // Database 1-5 connection
    function adb_connect($mysql_host, $mysql_user, $mysql_password, $database) {
        $db_con = '';
        $db_con = @new mysqli($mysql_host, $mysql_user, $mysql_password, $database);
        /* check connection */
/*
        if ($db_con->connect_errno) {
            echo "<p>&nbsp;</p>
            <p><p class='warnadmin cntr'><br />&nbsp;No valid database found to start up.<br />&nbsp;Configure at least one database.<br /><br />
            <p>&nbsp;</p>
            ";

        }
*/
        if (!$db_con->connect_errno) {
            /* define character set to utf8 */
            if (!$db_con->set_charset("utf8")) {
                printf("Error loading character set utf8: %s\n", $db_con->error);

                /* Print current character set */
                $charset = $db_con->character_set_name();
                printf ("<br />Current character set is: %s\n", $charset);

                $db_con->close();
                exit;
            }
        }
        return ($db_con);

    }

?>
