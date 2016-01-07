<?php

$include_dir = "./sphider-pdo/include";
$template_dir = "./templates";
$settings_dir = "./sphider-pdo/settings";
$language_dir = "./sphider-pdo/languages";

  include "$template_dir/searchhead.html";
  include "$template_dir/navbar.html";
  include "$template_dir/searchheader.html";
  include ("$include_dir/commonfuncs.php");

  require_once("$settings_dir/database.php");
  require_once("$include_dir/searchfuncs.php");
  require_once("$include_dir/categoryfuncs.php");
  require_once "$settings_dir/conf.php";

  if (!isset($query)
    || isset($sph_messages['SearchPrompt']) && strcasecmp($query, $sph_messages['SearchPrompt']) == 0)
    $query = "";

if (file_exists("$template_dir/$template/header_$language.html"))
  include_once("$template_dir/$template/header_$language.html");
else
  require_once("$template_dir/$template/header.html");

require_once("$template_dir/$template/search_form.html");

if (!isset($search) || strlen($query) == 0)
    $search = 0;
if (!isset($start))
    $start = 0;
switch ($search) {
    case 1:
        if (!isset($results))
            $results = "";
        if ($type != "phrase") {
            $query = str_replace("\"", " ", $query);
            $query = str_replace("&quot;", " ", $query);
            $query = str_replace("&#39;", " ", $query);
        }
        $query = str_replace("&amp;", " ", $query);
        $query = str_replace("&lt;", " ", $query);
        $query = str_replace("&gt;", " ", $query);
        $query = str_replace("#", " ", $query);
        $query = str_replace("&", " ", $query);
        $query = str_replace(";", " ", $query);
        $query = str_replace("'", " ", $query);
        $query = str_replace("*", " ", $query);
        $query = str_replace("%", " ", $query);
        $query = str_replace("\\", " ", $query);
        if (strpos($query, '\0') != FALSE)
            $query = "";
        $search_results = get_search_results($query, $start, $category, $type, $results, $domain);
        require("$template_dir/$template/search_results.html");
    break;
    default:
        if ($show_categories) {
            if (isset($_REQUEST['catid']) && $_REQUEST['catid'] && is_numeric($catid)) {
                $cat_info = get_category_info($catid);
            } else {
                $cat_info = get_categories_view();
            }
            require("$template_dir/$template/categories.html");
        }
    break;
    }

if (file_exists("$template_dir/$template/footer_$language.html"))
    include_once("$template_dir/$template/footer_$language.html");
else
    require_once("$template_dir/$template/footer.html");
?>