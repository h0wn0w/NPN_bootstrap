<?php
/* geoip.php
 *"
 "
 " Script modified by Tec in order to meet Sphider-plus requirements.
 " date 2012.10.12
 " date 2015.08.31      	$scheme = $_SERVER['REQUEST_SCHEME'];

 * Copyright (C) 2007 MaxMind LLC
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

define("GEOIP_COUNTRY_BEGIN", 16776960);
define("GEOIP_STATE_BEGIN_REV0", 16700000);
define("GEOIP_STATE_BEGIN_REV1", 16000000);
define("GEOIP_STANDARD", 0);
define("GEOIP_MEMORY_CACHE", 1);
define("GEOIP_SHARED_MEMORY", 2);
define("STRUCTURE_INFO_MAX_SIZE", 20);
define("DATABASE_INFO_MAX_SIZE", 100);
define("GEOIP_COUNTRY_EDITION", 106);
define("GEOIP_PROXY_EDITION", 8);
define("GEOIP_ASNUM_EDITION", 9);
define("GEOIP_NETSPEED_EDITION", 10);
define("GEOIP_REGION_EDITION_REV0", 112);
define("GEOIP_REGION_EDITION_REV1", 3);
define("GEOIP_CITY_EDITION_REV0", 111);
define("GEOIP_CITY_EDITION_REV1", 2);
define("GEOIP_ORG_EDITION", 110);
define("GEOIP_ISP_EDITION", 4);
define("SEGMENT_RECORD_LENGTH", 3);
define("STANDARD_RECORD_LENGTH", 3);
define("ORG_RECORD_LENGTH", 4);
define("MAX_RECORD_LENGTH", 4);
define("MAX_ORG_RECORD_LENGTH", 300);
define("GEOIP_SHM_KEY", 0x4f415401);
define("US_OFFSET", 1);
define("CANADA_OFFSET", 677);
define("WORLD_OFFSET", 1353);
define("FIPS_RANGE", 360);
define("GEOIP_UNKNOWN_SPEED", 0);
define("GEOIP_DIALUP_SPEED", 1);
define("GEOIP_CABLEDSL_SPEED", 2);
define("GEOIP_CORPORATE_SPEED", 3);
define("GEOIP_DOMAIN_EDITION", 11);
define("GEOIP_COUNTRY_EDITION_V6", 12);
define("GEOIP_LOCATIONA_EDITION", 13);
define("GEOIP_ACCURACYRADIUS_EDITION", 14);
define("GEOIP_CITYCOMBINED_EDITION", 15);
define("GEOIP_CITY_EDITION_REV1_V6", 30);
define("GEOIP_CITY_EDITION_REV0_V6",31);
define("GEOIP_NETSPEED_EDITION_REV1",32);
define("GEOIP_NETSPEED_EDITION_REV1_V6",33);
define("GEOIP_USERTYPE_EDITION",28);
define("GEOIP_USERTYPE_EDITION_V6",29);
define("GEOIP_ASNUM_EDITION_V6",21);
define("GEOIP_ISP_EDITION_V6",22);
define("GEOIP_ORG_EDITION_V6",23);
define("GEOIP_DOMAIN_EDITION_V6",24);

define("CITYCOMBINED_FIXED_RECORD", 7 );

class GeoIP {
    var $flags;
    var $filehandle;
    var $memory_buffer;
    var $databaseType;
    var $databaseSegments;
    var $record_length;
    var $shmid;
    var $GEOIP_COUNTRY_CODE_TO_NUMBER = array(
"" => 0, "AP" => 1, "EU" => 2, "AD" => 3, "AE" => 4, "AF" => 5,
"AG" => 6, "AI" => 7, "AL" => 8, "AM" => 9, "CW" => 10, "AO" => 11,
"AQ" => 12, "AR" => 13, "AS" => 14, "AT" => 15, "AU" => 16, "AW" => 17,
"AZ" => 18, "BA" => 19, "BB" => 20, "BD" => 21, "BE" => 22, "BF" => 23,
"BG" => 24, "BH" => 25, "BI" => 26, "BJ" => 27, "BM" => 28, "BN" => 29,
"BO" => 30, "BR" => 31, "BS" => 32, "BT" => 33, "BV" => 34, "BW" => 35,
"BY" => 36, "BZ" => 37, "CA" => 38, "CC" => 39, "CD" => 40, "CF" => 41,
"CG" => 42, "CH" => 43, "CI" => 44, "CK" => 45, "CL" => 46, "CM" => 47,
"CN" => 48, "CO" => 49, "CR" => 50, "CU" => 51, "CV" => 52, "CX" => 53,
"CY" => 54, "CZ" => 55, "DE" => 56, "DJ" => 57, "DK" => 58, "DM" => 59,
"DO" => 60, "DZ" => 61, "EC" => 62, "EE" => 63, "EG" => 64, "EH" => 65,
"ER" => 66, "ES" => 67, "ET" => 68, "FI" => 69, "FJ" => 70, "FK" => 71,
"FM" => 72, "FO" => 73, "FR" => 74, "SX" => 75, "GA" => 76, "GB" => 77,
"GD" => 78, "GE" => 79, "GF" => 80, "GH" => 81, "GI" => 82, "GL" => 83,
"GM" => 84, "GN" => 85, "GP" => 86, "GQ" => 87, "GR" => 88, "GS" => 89,
"GT" => 90, "GU" => 91, "GW" => 92, "GY" => 93, "HK" => 94, "HM" => 95,
"HN" => 96, "HR" => 97, "HT" => 98, "HU" => 99, "ID" => 100, "IE" => 101,
"IL" => 102, "IN" => 103, "IO" => 104, "IQ" => 105, "IR" => 106, "IS" => 107,
"IT" => 108, "JM" => 109, "JO" => 110, "JP" => 111, "KE" => 112, "KG" => 113,
"KH" => 114, "KI" => 115, "KM" => 116, "KN" => 117, "KP" => 118, "KR" => 119,
"KW" => 120, "KY" => 121, "KZ" => 122, "LA" => 123, "LB" => 124, "LC" => 125,
"LI" => 126, "LK" => 127, "LR" => 128, "LS" => 129, "LT" => 130, "LU" => 131,
"LV" => 132, "LY" => 133, "MA" => 134, "MC" => 135, "MD" => 136, "MG" => 137,
"MH" => 138, "MK" => 139, "ML" => 140, "MM" => 141, "MN" => 142, "MO" => 143,
"MP" => 144, "MQ" => 145, "MR" => 146, "MS" => 147, "MT" => 148, "MU" => 149,
"MV" => 150, "MW" => 151, "MX" => 152, "MY" => 153, "MZ" => 154, "NA" => 155,
"NC" => 156, "NE" => 157, "NF" => 158, "NG" => 159, "NI" => 160, "NL" => 161,
"NO" => 162, "NP" => 163, "NR" => 164, "NU" => 165, "NZ" => 166, "OM" => 167,
"PA" => 168, "PE" => 169, "PF" => 170, "PG" => 171, "PH" => 172, "PK" => 173,
"PL" => 174, "PM" => 175, "PN" => 176, "PR" => 177, "PS" => 178, "PT" => 179,
"PW" => 180, "PY" => 181, "QA" => 182, "RE" => 183, "RO" => 184, "RU" => 185,
"RW" => 186, "SA" => 187, "SB" => 188, "SC" => 189, "SD" => 190, "SE" => 191,
"SG" => 192, "SH" => 193, "SI" => 194, "SJ" => 195, "SK" => 196, "SL" => 197,
"SM" => 198, "SN" => 199, "SO" => 200, "SR" => 201, "ST" => 202, "SV" => 203,
"SY" => 204, "SZ" => 205, "TC" => 206, "TD" => 207, "TF" => 208, "TG" => 209,
"TH" => 210, "TJ" => 211, "TK" => 212, "TM" => 213, "TN" => 214, "TO" => 215,
"TL" => 216, "TR" => 217, "TT" => 218, "TV" => 219, "TW" => 220, "TZ" => 221,
"UA" => 222, "UG" => 223, "UM" => 224, "US" => 225, "UY" => 226, "UZ" => 227,
"VA" => 228, "VC" => 229, "VE" => 230, "VG" => 231, "VI" => 232, "VN" => 233,
"VU" => 234, "WF" => 235, "WS" => 236, "YE" => 237, "YT" => 238, "RS" => 239,
"ZA" => 240, "ZM" => 241, "ME" => 242, "ZW" => 243, "A1" => 244, "A2" => 245,
"O1" => 246, "AX" => 247, "GG" => 248, "IM" => 249, "JE" => 250, "BL" => 251,
"MF" => 252, "BQ" => 253,
);
    var $GEOIP_COUNTRY_CODES = array(
     "","AP","EU","AD","AE","AF","AG","AI","AL","AM","CW",
	"AO","AQ","AR","AS","AT","AU","AW","AZ","BA","BB",
	"BD","BE","BF","BG","BH","BI","BJ","BM","BN","BO",
	"BR","BS","BT","BV","BW","BY","BZ","CA","CC","CD",
	"CF","CG","CH","CI","CK","CL","CM","CN","CO","CR",
	"CU","CV","CX","CY","CZ","DE","DJ","DK","DM","DO",
	"DZ","EC","EE","EG","EH","ER","ES","ET","FI","FJ",
	"FK","FM","FO","FR","SX","GA","GB","GD","GE","GF",
	"GH","GI","GL","GM","GN","GP","GQ","GR","GS","GT",
	"GU","GW","GY","HK","HM","HN","HR","HT","HU","ID",
	"IE","IL","IN","IO","IQ","IR","IS","IT","JM","JO",
	"JP","KE","KG","KH","KI","KM","KN","KP","KR","KW",
	"KY","KZ","LA","LB","LC","LI","LK","LR","LS","LT",
	"LU","LV","LY","MA","MC","MD","MG","MH","MK","ML",
	"MM","MN","MO","MP","MQ","MR","MS","MT","MU","MV",
	"MW","MX","MY","MZ","NA","NC","NE","NF","NG","NI",
	"NL","NO","NP","NR","NU","NZ","OM","PA","PE","PF",
	"PG","PH","PK","PL","PM","PN","PR","PS","PT","PW",
	"PY","QA","RE","RO","RU","RW","SA","SB","SC","SD",
	"SE","SG","SH","SI","SJ","SK","SL","SM","SN","SO",
	"SR","ST","SV","SY","SZ","TC","TD","TF","TG","TH",
	"TJ","TK","TM","TN","TO","TL","TR","TT","TV","TW",
	"TZ","UA","UG","UM","US","UY","UZ","VA","VC","VE",
	"VG","VI","VN","VU","WF","WS","YE","YT","RS","ZA",
	"ZM","ME","ZW","A1","A2","O1","AX","GG","IM","JE",
  "BL","MF", "BQ");
    var $GEOIP_COUNTRY_CODES3 = array(
      "","AP","EU","AND","ARE","AFG","ATG","AIA","ALB","ARM","CUW",
	"AGO","ATA","ARG","ASM","AUT","AUS","ABW","AZE","BIH","BRB",
	"BGD","BEL","BFA","BGR","BHR","BDI","BEN","BMU","BRN","BOL",
	"BRA","BHS","BTN","BVT","BWA","BLR","BLZ","CAN","CCK","COD",
	"CAF","COG","CHE","CIV","COK","CHL","CMR","CHN","COL","CRI",
	"CUB","CPV","CXR","CYP","CZE","DEU","DJI","DNK","DMA","DOM",
	"DZA","ECU","EST","EGY","ESH","ERI","ESP","ETH","FIN","FJI",
	"FLK","FSM","FRO","FRA","SXM","GAB","GBR","GRD","GEO","GUF",
	"GHA","GIB","GRL","GMB","GIN","GLP","GNQ","GRC","SGS","GTM",
	"GUM","GNB","GUY","HKG","HMD","HND","HRV","HTI","HUN","IDN",
	"IRL","ISR","IND","IOT","IRQ","IRN","ISL","ITA","JAM","JOR",
	"JPN","KEN","KGZ","KHM","KIR","COM","KNA","PRK","KOR","KWT",
	"CYM","KAZ","LAO","LBN","LCA","LIE","LKA","LBR","LSO","LTU",
	"LUX","LVA","LBY","MAR","MCO","MDA","MDG","MHL","MKD","MLI",
	"MMR","MNG","MAC","MNP","MTQ","MRT","MSR","MLT","MUS","MDV",
	"MWI","MEX","MYS","MOZ","NAM","NCL","NER","NFK","NGA","NIC",
	"NLD","NOR","NPL","NRU","NIU","NZL","OMN","PAN","PER","PYF",
	"PNG","PHL","PAK","POL","SPM","PCN","PRI","PSE","PRT","PLW",
	"PRY","QAT","REU","ROU","RUS","RWA","SAU","SLB","SYC","SDN",
	"SWE","SGP","SHN","SVN","SJM","SVK","SLE","SMR","SEN","SOM",
	"SUR","STP","SLV","SYR","SWZ","TCA","TCD","ATF","TGO","THA",
	"TJK","TKL","TKM","TUN","TON","TLS","TUR","TTO","TUV","TWN",
	"TZA","UKR","UGA","UMI","USA","URY","UZB","VAT","VCT","VEN",
	"VGB","VIR","VNM","VUT","WLF","WSM","YEM","MYT","SRB","ZAF",
	"ZMB","MNE","ZWE","A1","A2","O1","ALA","GGY","IMN","JEY",
  "BLM","MAF", "BES"
  );
    var $GEOIP_COUNTRY_NAMES = array(
    "","Asia/Pacific Region","Europe","Andorra","United Arab Emirates","Afghanistan","Antigua and Barbuda","Anguilla","Albania","Armenia","Curacao",
	"Angola","Antarctica","Argentina","American Samoa","Austria","Australia","Aruba","Azerbaijan","Bosnia and Herzegovina","Barbados",
	"Bangladesh","Belgium","Burkina Faso","Bulgaria","Bahrain","Burundi","Benin","Bermuda","Brunei Darussalam","Bolivia",
	"Brazil","Bahamas","Bhutan","Bouvet Island","Botswana","Belarus","Belize","Canada","Cocos (Keeling) Islands","Congo, The Democratic Republic of the",
	"Central African Republic","Congo","Switzerland","Cote D'Ivoire","Cook Islands","Chile","Cameroon","China","Colombia","Costa Rica",
	"Cuba","Cape Verde","Christmas Island","Cyprus","Czech Republic","Germany","Djibouti","Denmark","Dominica","Dominican Republic",
	"Algeria","Ecuador","Estonia","Egypt","Western Sahara","Eritrea","Spain","Ethiopia","Finland","Fiji",
	"Falkland Islands (Malvinas)","Micronesia, Federated States of","Faroe Islands","France","Sint Maarten (Dutch part)","Gabon","United Kingdom","Grenada","Georgia","French Guiana",
	"Ghana","Gibraltar","Greenland","Gambia","Guinea","Guadeloupe","Equatorial Guinea","Greece","South Georgia and the South Sandwich Islands","Guatemala",
	"Guam","Guinea-Bissau","Guyana","Hong Kong","Heard Island and McDonald Islands","Honduras","Croatia","Haiti","Hungary","Indonesia",
	"Ireland","Israel","India","British Indian Ocean Territory","Iraq","Iran, Islamic Republic of","Iceland","Italy","Jamaica","Jordan",
	"Japan","Kenya","Kyrgyzstan","Cambodia","Kiribati","Comoros","Saint Kitts and Nevis","Korea, Democratic People's Republic of","Korea, Republic of","Kuwait",
	"Cayman Islands","Kazakhstan","Lao People's Democratic Republic","Lebanon","Saint Lucia","Liechtenstein","Sri Lanka","Liberia","Lesotho","Lithuania",
	"Luxembourg","Latvia","Libya","Morocco","Monaco","Moldova, Republic of","Madagascar","Marshall Islands","Macedonia","Mali",
	"Myanmar","Mongolia","Macau","Northern Mariana Islands","Martinique","Mauritania","Montserrat","Malta","Mauritius","Maldives",
	"Malawi","Mexico","Malaysia","Mozambique","Namibia","New Caledonia","Niger","Norfolk Island","Nigeria","Nicaragua",
	"Netherlands","Norway","Nepal","Nauru","Niue","New Zealand","Oman","Panama","Peru","French Polynesia",
	"Papua New Guinea","Philippines","Pakistan","Poland","Saint Pierre and Miquelon","Pitcairn Islands","Puerto Rico","Palestinian Territory","Portugal","Palau",
	"Paraguay","Qatar","Reunion","Romania","Russian Federation","Rwanda","Saudi Arabia","Solomon Islands","Seychelles","Sudan",
	"Sweden","Singapore","Saint Helena","Slovenia","Svalbard and Jan Mayen","Slovakia","Sierra Leone","San Marino","Senegal","Somalia","Suriname",
	"Sao Tome and Principe","El Salvador","Syrian Arab Republic","Swaziland","Turks and Caicos Islands","Chad","French Southern Territories","Togo","Thailand",
	"Tajikistan","Tokelau","Turkmenistan","Tunisia","Tonga","Timor-Leste","Turkey","Trinidad and Tobago","Tuvalu","Taiwan",
	"Tanzania, United Republic of","Ukraine","Uganda","United States Minor Outlying Islands","United States","Uruguay","Uzbekistan","Holy See (Vatican City State)","Saint Vincent and the Grenadines","Venezuela",
	"Virgin Islands, British","Virgin Islands, U.S.","Vietnam","Vanuatu","Wallis and Futuna","Samoa","Yemen","Mayotte","Serbia","South Africa",
	"Zambia","Montenegro","Zimbabwe","Anonymous Proxy","Satellite Provider","Other","Aland Islands","Guernsey","Isle of Man","Jersey",
        "Saint Barthelemy","Saint Martin", "Bonaire, Saint Eustatius and Saba"
);

    var $GEOIP_CONTINENT_CODES = array(
  "--", "AS","EU","EU","AS","AS","NA","NA","EU","AS","NA",
        "AF","AN","SA","OC","EU","OC","NA","AS","EU","NA",
        "AS","EU","AF","EU","AS","AF","AF","NA","AS","SA",
        "SA","NA","AS","AN","AF","EU","NA","NA","AS","AF",
        "AF","AF","EU","AF","OC","SA","AF","AS","SA","NA",
        "NA","AF","AS","AS","EU","EU","AF","EU","NA","NA",
        "AF","SA","EU","AF","AF","AF","EU","AF","EU","OC",
        "SA","OC","EU","EU","NA","AF","EU","NA","AS","SA",
        "AF","EU","NA","AF","AF","NA","AF","EU","AN","NA",
        "OC","AF","SA","AS","AN","NA","EU","NA","EU","AS",
        "EU","AS","AS","AS","AS","AS","EU","EU","NA","AS",
        "AS","AF","AS","AS","OC","AF","NA","AS","AS","AS",
        "NA","AS","AS","AS","NA","EU","AS","AF","AF","EU",
        "EU","EU","AF","AF","EU","EU","AF","OC","EU","AF",
        "AS","AS","AS","OC","NA","AF","NA","EU","AF","AS",
        "AF","NA","AS","AF","AF","OC","AF","OC","AF","NA",
        "EU","EU","AS","OC","OC","OC","AS","NA","SA","OC",
        "OC","AS","AS","EU","NA","OC","NA","AS","EU","OC",
        "SA","AS","AF","EU","EU","AF","AS","OC","AF","AF",
        "EU","AS","AF","EU","EU","EU","AF","EU","AF","AF",
        "SA","AF","NA","AS","AF","NA","AF","AN","AF","AS",
        "AS","OC","AS","AF","OC","AS","EU","NA","OC","AS",
        "AF","EU","AF","OC","NA","SA","AS","EU","NA","SA",
        "NA","NA","AS","OC","OC","OC","AS","AF","EU","AF",
        "AF","EU","AF","--","--","--","EU","EU","EU","EU",
        "NA","NA","NA"
);

}
function geoip_load_shared_mem ($file) {

  $fp = fopen($file, "rb");
  if (!$fp) {
    print "error opening $file: $php_errormsg\n";
    exit;
  }
  $s_array = fstat($fp);
  $size = $s_array['size'];
  if ($shmid = @shmop_open (GEOIP_SHM_KEY, "w", 0, 0)) {
    shmop_delete ($shmid);
    shmop_close ($shmid);
  }
  $shmid = shmop_open (GEOIP_SHM_KEY, "c", 0644, $size);
  shmop_write ($shmid, fread($fp, $size), 0);
  shmop_close ($shmid);
}

function _setup_segments($gi){
  $gi->databaseType = GEOIP_COUNTRY_EDITION;
  $gi->record_length = STANDARD_RECORD_LENGTH;
  if ($gi->flags & GEOIP_SHARED_MEMORY) {
    $offset = @shmop_size ($gi->shmid) - 3;
    for ($i = 0; $i < STRUCTURE_INFO_MAX_SIZE; $i++) {
        $delim = @shmop_read ($gi->shmid, $offset, 3);
        $offset += 3;
        if ($delim == (chr(255).chr(255).chr(255))) {
            $gi->databaseType = ord(@shmop_read ($gi->shmid, $offset, 1));
            $offset++;

            if ($gi->databaseType == GEOIP_REGION_EDITION_REV0){
                $gi->databaseSegments = GEOIP_STATE_BEGIN_REV0;
            } else if ($gi->databaseType == GEOIP_REGION_EDITION_REV1){
                $gi->databaseSegments = GEOIP_STATE_BEGIN_REV1;
	    } else if (($gi->databaseType == GEOIP_CITY_EDITION_REV0)||
                     ($gi->databaseType == GEOIP_CITY_EDITION_REV1)
                    || ($gi->databaseType == GEOIP_ORG_EDITION)
                    || ($gi->databaseType == GEOIP_ORG_EDITION_V6)
                    || ($gi->databaseType == GEOIP_DOMAIN_EDITION)
                    || ($gi->databaseType == GEOIP_DOMAIN_EDITION_V6)
		    || ($gi->databaseType == GEOIP_ISP_EDITION)
		    || ($gi->databaseType == GEOIP_ISP_EDITION_V6)
      		    || ($gi->databaseType == GEOIP_USERTYPE_EDITION)
		    || ($gi->databaseType == GEOIP_USERTYPE_EDITION_V6)
		    || ($gi->databaseType == GEOIP_LOCATIONA_EDITION)
		    || ($gi->databaseType == GEOIP_ACCURACYRADIUS_EDITION)
		    || ($gi->databaseType == GEOIP_CITY_EDITION_REV0_V6)
		    || ($gi->databaseType == GEOIP_CITY_EDITION_REV1_V6)
                    || ($gi->databaseType == GEOIP_NETSPEED_EDITION_REV1)
                    || ($gi->databaseType == GEOIP_NETSPEED_EDITION_REV1_V6)
		    || ($gi->databaseType == GEOIP_ASNUM_EDITION)
		    || ($gi->databaseType == GEOIP_ASNUM_EDITION_V6)){
                $gi->databaseSegments = 0;
                $buf = @shmop_read ($gi->shmid, $offset, SEGMENT_RECORD_LENGTH);
                for ($j = 0;$j < SEGMENT_RECORD_LENGTH;$j++){
                    $gi->databaseSegments += (ord($buf[$j]) << ($j * 8));
                }
	            if (($gi->databaseType == GEOIP_ORG_EDITION)
	                || ($gi->databaseType == GEOIP_ORG_EDITION_V6)
                        || ($gi->databaseType == GEOIP_DOMAIN_EDITION)
                        || ($gi->databaseType == GEOIP_DOMAIN_EDITION_V6)
			|| ($gi->databaseType == GEOIP_ISP_EDITION)
			|| ($gi->databaseType == GEOIP_ISP_EDITION_V6)) {
	                $gi->record_length = ORG_RECORD_LENGTH;
                }
            }
            break;
        } else {
            $offset -= 4;
        }
    }
    if (($gi->databaseType == GEOIP_COUNTRY_EDITION)||
        ($gi->databaseType == GEOIP_COUNTRY_EDITION_V6)||
        ($gi->databaseType == GEOIP_PROXY_EDITION)||
        ($gi->databaseType == GEOIP_NETSPEED_EDITION)){
        $gi->databaseSegments = GEOIP_COUNTRY_BEGIN;
    }
  } else {
    $filepos = ftell($gi->filehandle);
    fseek($gi->filehandle, -3, SEEK_END);
    for ($i = 0; $i < STRUCTURE_INFO_MAX_SIZE; $i++) {
        $delim = fread($gi->filehandle,3);
        if ($delim == (chr(255).chr(255).chr(255))){
        $gi->databaseType = ord(fread($gi->filehandle,1));
        if ($gi->databaseType == GEOIP_REGION_EDITION_REV0){
            $gi->databaseSegments = GEOIP_STATE_BEGIN_REV0;
        }
        else if ($gi->databaseType == GEOIP_REGION_EDITION_REV1){
	    $gi->databaseSegments = GEOIP_STATE_BEGIN_REV1;
                }  else if (($gi->databaseType == GEOIP_CITY_EDITION_REV0)
                    || ($gi->databaseType == GEOIP_CITY_EDITION_REV1)
                    || ($gi->databaseType == GEOIP_CITY_EDITION_REV0_V6)
                    || ($gi->databaseType == GEOIP_CITY_EDITION_REV1_V6)
                    || ($gi->databaseType == GEOIP_ORG_EDITION)
                    || ($gi->databaseType == GEOIP_DOMAIN_EDITION)
		    || ($gi->databaseType == GEOIP_ISP_EDITION)
                    || ($gi->databaseType == GEOIP_ORG_EDITION_V6)
                    || ($gi->databaseType == GEOIP_DOMAIN_EDITION_V6)
		    || ($gi->databaseType == GEOIP_ISP_EDITION_V6)
		    || ($gi->databaseType == GEOIP_LOCATIONA_EDITION)
		    || ($gi->databaseType == GEOIP_ACCURACYRADIUS_EDITION)
                    || ($gi->databaseType == GEOIP_CITY_EDITION_REV0_V6)
		    || ($gi->databaseType == GEOIP_CITY_EDITION_REV1_V6)
                    || ($gi->databaseType == GEOIP_NETSPEED_EDITION_REV1)
                    || ($gi->databaseType == GEOIP_NETSPEED_EDITION_REV1_V6)
    		    || ($gi->databaseType == GEOIP_USERTYPE_EDITION)
		    || ($gi->databaseType == GEOIP_USERTYPE_EDITION_V6)
                    || ($gi->databaseType == GEOIP_ASNUM_EDITION)
                    || ($gi->databaseType == GEOIP_ASNUM_EDITION_V6)){
            $gi->databaseSegments = 0;
            $buf = fread($gi->filehandle,SEGMENT_RECORD_LENGTH);
            for ($j = 0;$j < SEGMENT_RECORD_LENGTH;$j++){
            $gi->databaseSegments += (ord($buf[$j]) << ($j * 8));
            }
	    if (   ( $gi->databaseType == GEOIP_ORG_EDITION )
                || ( $gi->databaseType == GEOIP_DOMAIN_EDITION )
                || ( $gi->databaseType == GEOIP_ISP_EDITION )
                || ( $gi->databaseType == GEOIP_ORG_EDITION_V6 )
                || ( $gi->databaseType == GEOIP_DOMAIN_EDITION_V6 )
                || ( $gi->databaseType == GEOIP_ISP_EDITION_V6 )) {
	    $gi->record_length = ORG_RECORD_LENGTH;
            }
        }
        break;
        } else {
        fseek($gi->filehandle, -4, SEEK_CUR);
        }
    }
    if (($gi->databaseType == GEOIP_COUNTRY_EDITION)||
        ($gi->databaseType == GEOIP_COUNTRY_EDITION_V6)||
        ($gi->databaseType == GEOIP_PROXY_EDITION)||
        ($gi->databaseType == GEOIP_NETSPEED_EDITION)){
         $gi->databaseSegments = GEOIP_COUNTRY_BEGIN;
    }
    fseek($gi->filehandle,$filepos,SEEK_SET);
  }
  return $gi;
}

function geoip_open($filename, $flags) {
  $gi = new GeoIP;
  $gi->flags = $flags;
  if ($gi->flags & GEOIP_SHARED_MEMORY) {
    $gi->shmid = @shmop_open (GEOIP_SHM_KEY, "a", 0, 0);
    } else {
    $gi->filehandle = fopen($filename,"rb") or die( "Can not open $filename\n" );
    if ($gi->flags & GEOIP_MEMORY_CACHE) {
        $s_array = fstat($gi->filehandle);
        $gi->memory_buffer = fread($gi->filehandle, $s_array['size']);
    }
  }

  $gi = _setup_segments($gi);
  return $gi;
}

function geoip_close($gi) {
  if ($gi->flags & GEOIP_SHARED_MEMORY) {
    return true;
  }

  return fclose($gi->filehandle);
}

function geoip_country_id_by_name_v6($gi, $name) {
  $rec = dns_get_record($name, DNS_AAAA);
  if ( !$rec ) {
    return false;
  }
  $addr = $rec[0]["ipv6"];
  if (!$addr || $addr == $name) {
    return false;
  }
  return geoip_country_id_by_addr_v6($gi, $addr);
}

function geoip_country_id_by_name($gi, $name) {
  $addr = gethostbyname($name);
  if (!$addr || $addr == $name) {
    return false;
  }
  return geoip_country_id_by_addr($gi, $addr);
}

function geoip_country_code_by_name_v6($gi, $name) {
  $country_id = geoip_country_id_by_name_v6($gi,$name);
  if ($country_id !== false) {
        return $gi->GEOIP_COUNTRY_CODES[$country_id];
  }
  return false;
}

function geoip_country_code_by_name($gi, $name) {
  $country_id = geoip_country_id_by_name($gi,$name);
  if ($country_id !== false) {
        return $gi->GEOIP_COUNTRY_CODES[$country_id];
  }
  return false;
}

function geoip_country_name_by_name_v6($gi, $name) {
  $country_id = geoip_country_id_by_name_v6($gi,$name);
  if ($country_id !== false) {
        return $gi->GEOIP_COUNTRY_NAMES[$country_id];
  }
  return false;
}

function geoip_country_name_by_name($gi, $name) {
  $country_id = geoip_country_id_by_name($gi,$name);
  if ($country_id !== false) {
        return $gi->GEOIP_COUNTRY_NAMES[$country_id];
  }
  return false;
}

function geoip_country_id_by_addr_v6($gi, $addr) {
  $ipnum = inet_pton($addr);
  return _geoip_seek_country_v6($gi, $ipnum) - GEOIP_COUNTRY_BEGIN;
}

function geoip_country_id_by_addr($gi, $addr) {
  $ipnum = ip2long($addr);
  return _geoip_seek_country($gi, $ipnum) - GEOIP_COUNTRY_BEGIN;
}

function geoip_country_code_by_addr_v6($gi, $addr) {
    $country_id = geoip_country_id_by_addr_v6($gi,$addr);
    if ($country_id !== false) {
      return $gi->GEOIP_COUNTRY_CODES[$country_id];
    }
  return false;
}

function geoip_country_code_by_addr($gi, $addr) {
  if ($gi->databaseType == GEOIP_CITY_EDITION_REV1) {
    $record = geoip_record_by_addr($gi,$addr);
    if ( $record !== false ) {
      return $record->country_code;
    }
  } else {
    $country_id = geoip_country_id_by_addr($gi,$addr);
    if ($country_id !== false) {
      return $gi->GEOIP_COUNTRY_CODES[$country_id];
    }
  }
  return false;
}

function geoip_country_name_by_addr_v6($gi, $addr) {
    $country_id = geoip_country_id_by_addr_v6($gi,$addr);
    if ($country_id !== false) {
      return $gi->GEOIP_COUNTRY_NAMES[$country_id];
    }
  return false;
}

function geoip_country_name_by_addr($gi, $addr) {
  if ($gi->databaseType == GEOIP_CITY_EDITION_REV1) {
    $record = geoip_record_by_addr($gi,$addr);
    return $record->country_name;
  } else {
    $country_id = geoip_country_id_by_addr($gi,$addr);
    if ($country_id !== false) {
      return $gi->GEOIP_COUNTRY_NAMES[$country_id];
    }
  }
  return false;
}

function _geoip_seek_country_v6($gi, $ipnum) {

  # arrays from unpack start with offset 1
  # yet another php mystery. array_merge work around
  # this broken behaviour
  $v6vec = array_merge(unpack( "C16", $ipnum));

  $offset = 0;
  for ($depth = 127; $depth >= 0; --$depth) {
    if ($gi->flags & GEOIP_MEMORY_CACHE) {
      // workaround php's broken substr, strpos, etc handling with
      // mbstring.func_overload and mbstring.internal_encoding
      $enc = mb_internal_encoding();
       mb_internal_encoding('ISO-8859-1');

      $buf = substr($gi->memory_buffer,
                            2 * $gi->record_length * $offset,
                            2 * $gi->record_length);

      mb_internal_encoding($enc);
    } elseif ($gi->flags & GEOIP_SHARED_MEMORY) {
      $buf = @shmop_read ($gi->shmid,
                            2 * $gi->record_length * $offset,
                            2 * $gi->record_length );
        } else {
      fseek($gi->filehandle, 2 * $gi->record_length * $offset, SEEK_SET) == 0
        or die("fseek failed");
      $buf = fread($gi->filehandle, 2 * $gi->record_length);
    }
    $x = array(0,0);
    for ($i = 0; $i < 2; ++$i) {
      for ($j = 0; $j < $gi->record_length; ++$j) {
        $x[$i] += ord($buf[$gi->record_length * $i + $j]) << ($j * 8);
      }
    }

    $bnum = 127 - $depth;
    $idx = $bnum >> 3;
    $b_mask = 1 << ( $bnum & 7 ^ 7 );
    if (($v6vec[$idx] & $b_mask) > 0) {
      if ($x[1] >= $gi->databaseSegments) {
        return $x[1];
      }
      $offset = $x[1];
    } else {
      if ($x[0] >= $gi->databaseSegments) {
        return $x[0];
      }
      $offset = $x[0];
    }
  }
  trigger_error("error traversing database - perhaps it is corrupt?", E_USER_ERROR);
  return false;
}

function _geoip_seek_country($gi, $ipnum) {
  $offset = 0;
  for ($depth = 31; $depth >= 0; --$depth) {
    if ($gi->flags & GEOIP_MEMORY_CACHE) {
      // workaround php's broken substr, strpos, etc handling with
      // mbstring.func_overload and mbstring.internal_encoding
      $enc = mb_internal_encoding();
       mb_internal_encoding('ISO-8859-1');

      $buf = substr($gi->memory_buffer,
                            2 * $gi->record_length * $offset,
                            2 * $gi->record_length);

      mb_internal_encoding($enc);
    } elseif ($gi->flags & GEOIP_SHARED_MEMORY) {
      $buf = @shmop_read ($gi->shmid,
                            2 * $gi->record_length * $offset,
                            2 * $gi->record_length );
        } else {
      fseek($gi->filehandle, 2 * $gi->record_length * $offset, SEEK_SET) == 0
        or die("fseek failed");
      $buf = fread($gi->filehandle, 2 * $gi->record_length);
    }
    $x = array(0,0);
    for ($i = 0; $i < 2; ++$i) {
      for ($j = 0; $j < $gi->record_length; ++$j) {
        $x[$i] += ord($buf[$gi->record_length * $i + $j]) << ($j * 8);
      }
    }
    if ($ipnum & (1 << $depth)) {
      if ($x[1] >= $gi->databaseSegments) {
        return $x[1];
      }
      $offset = $x[1];
        } else {
      if ($x[0] >= $gi->databaseSegments) {
        return $x[0];
      }
      $offset = $x[0];
    }
  }
  trigger_error("error traversing database - perhaps it is corrupt?", E_USER_ERROR);
  return false;
}

function _common_get_org($gi, $seek_org){
  $record_pointer = $seek_org + (2 * $gi->record_length - 1) * $gi->databaseSegments;
  if ($gi->flags & GEOIP_SHARED_MEMORY) {
    $org_buf = @shmop_read ($gi->shmid, $record_pointer, MAX_ORG_RECORD_LENGTH);
    } else {
    fseek($gi->filehandle, $record_pointer, SEEK_SET);
    $org_buf = fread($gi->filehandle,MAX_ORG_RECORD_LENGTH);
  }
  // workaround php's broken substr, strpos, etc handling with
  // mbstring.func_overload and mbstring.internal_encoding
  $enc = mb_internal_encoding();
  mb_internal_encoding('ISO-8859-1');
  $org_buf = substr($org_buf, 0, strpos($org_buf, "\0"));
  mb_internal_encoding($enc);
  return $org_buf;
}

function _get_org_v6($gi,$ipnum){
  $seek_org = _geoip_seek_country_v6($gi,$ipnum);
  if ($seek_org == $gi->databaseSegments) {
    return NULL;
  }
  return _common_get_org($gi, $seek_org);
}

function _get_org($gi,$ipnum){
  $seek_org = _geoip_seek_country($gi,$ipnum);
  if ($seek_org == $gi->databaseSegments) {
    return NULL;
  }
  return _common_get_org($gi, $seek_org);
}



function geoip_name_by_addr_v6 ($gi,$addr) {
  if ($addr == NULL) {
    return 0;
  }
  $ipnum = inet_pton($addr);
  return _get_org_v6($gi, $ipnum);
}

function geoip_name_by_addr ($gi,$addr) {
  if ($addr == NULL) {
    return 0;
  }
  $ipnum = ip2long($addr);
  return _get_org($gi, $ipnum);
}

function geoip_org_by_addr ($gi,$addr) {
  return geoip_name_by_addr($gi, $addr);
}

function _get_region($gi,$ipnum){
  if ($gi->databaseType == GEOIP_REGION_EDITION_REV0){
    $seek_region = _geoip_seek_country($gi,$ipnum) - GEOIP_STATE_BEGIN_REV0;
    if ($seek_region >= 1000){
      $country_code = "US";
      $region = chr(($seek_region - 1000)/26 + 65) . chr(($seek_region - 1000)%26 + 65);
    } else {
            $country_code = $gi->GEOIP_COUNTRY_CODES[$seek_region];
      $region = "";
    }
  return array ($country_code,$region);
    }  else if ($gi->databaseType == GEOIP_REGION_EDITION_REV1) {
    $seek_region = _geoip_seek_country($gi,$ipnum) - GEOIP_STATE_BEGIN_REV1;
    //print $seek_region;
    if ($seek_region < US_OFFSET){
      $country_code = "";
      $region = "";
        } else if ($seek_region < CANADA_OFFSET) {
      $country_code = "US";
      $region = chr(($seek_region - US_OFFSET)/26 + 65) . chr(($seek_region - US_OFFSET)%26 + 65);
        } else if ($seek_region < WORLD_OFFSET) {
      $country_code = "CA";
      $region = chr(($seek_region - CANADA_OFFSET)/26 + 65) . chr(($seek_region - CANADA_OFFSET)%26 + 65);
    } else {
            $country_code = $gi->GEOIP_COUNTRY_CODES[($seek_region - WORLD_OFFSET) / FIPS_RANGE];
      $region = "";
    }
  return array ($country_code,$region);
  }
}

function geoip_region_by_addr ($gi,$addr) {
  if ($addr == NULL) {
    return 0;
  }
  $ipnum = ip2long($addr);
  return _get_region($gi, $ipnum);
}

function getdnsattributes ($l,$ip){
  $r = new Net_DNS_Resolver();
  $r->nameservers = array("ws1.maxmind.com");
  $p = $r->search($l."." . $ip .".s.maxmind.com","TXT","IN");
  $str = is_object($p->answer[0])?$p->answer[0]->string():'';
  $str = substr( $str, 1, -1 );
  return $str;
}

/* ---------------------------------------------------------------------------------------------------
*
 * This class takes the ip as a parameter and find the
 * approximated location of the user.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   	Generic system methods
 * @package    	geo location
 * @author     	Frederik Yssing <fryss@pinchlabs.dk>
 * @copyright  	2012-2013 pinch
 * @license    	http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    	SVN: 1.0.0
 * @link       	          http://www.pinchlabs.dk
 * @since      	File available since Release 1.0.0
 */

class netGeo{
	/**
     * The ip adress to look up, if supplied.
	 * freegeoip will find the best ip if no Ip is given.
	 *
     * @var string Ip
     * @access public
	 * @static
     */
	public static $Ip = '0';
	/**
     * Country code in letters eg. DK for Denmark.
	 *
     * @var string CountryCode
     * @access public
	 * @static
     */
	public static $CountryCode = '';
	/**
     * @var string CountryName
     * @access public
	 * @static
     */
	public static $CountryName = '';
	/**
     * @var string RegionCode
     * @access public
	 * @static
     */
	public static $RegionCode = '';
	/**
     * @var string RegionName
     * @access public
	 * @static
     */
	public static $RegionName = '';
	/**
     * @var string City
     * @access public
	 * @static
     */
	public static $City = '';
	/**
     * @var string ZipCode
     * @access public
	 * @static
     */
	public static $ZipCode = '';
	/**
     * @var string Latitude
     * @access public
	 * @static
     */
	public static $Latitude = '';
	/**
     * @var string Longitude
     * @access public
	 * @static
     */
	public static $Longitude = '';
	/**
     * @var string MetroCode
     * @access public
	 * @static
     */
	public static $MetroCode = '';
	/**
     * @var string AreaCode
     * @access public
	 * @static
     */
	public static $AreaCode = '';

	/**
	 * This method is used to ask freegeoip for geo information based on
	 * either the supplied IP or the IP of the asker.
	 * The answer from freegeoip is an XML file.
	 *
	 * @param string $ip The Ip address to ask on.
	 *
	 * @return bool Returns TRUE on success or FALSE on failure.
	 *
	 * @access public
	 * @static
	 * @since Method available since Release 1.0.0
	 */
	public static function getNetGeo($ip = 0){
		if($ip){
			$url = 'http://freegeoip.net/xml/'.$ip;
		} else {
			$url = 'http://freegeoip.net/xml/';
		}

		try{
			$result = file_get_contents($url, false);
			if(!$result){
				throw new Exception('Could not get a response from freegeoip.net');
				return false;
			} else {
				self::pickDataFromXML($result);
			}
		}
		catch (Exception $e){
			self::DBug('Caught exception: '. $e->getMessage(). ' in method: '.__METHOD__.' in file: '.__FILE__);
			return false;
		}
		return true;
	}

	/**
	 * This method finds the relevant data from the returned xml file.
	 * It then assigns those data to the variables.
	 *
	 * @param string $file The xml file with the data found..
	 *
	 * @return bool Returns TRUE on success or FALSE on failure.
	 *
	 * @access public
	 * @static
	 * @since Method available since Release 1.0.0
	 * @todo use a pointer to the xml file.
	 */
	public static function pickDataFromXML($file){
		$XMLFile = '';
		try{
			$XMLFile = simplexml_load_string($file);
			if(!$XMLFile){
				throw new Exception('Could not load XML file!');
			} else {
				self::$Ip = $XMLFile->Ip;
				self::$CountryCode = $XMLFile->CountryCode;
				self::$CountryName = $XMLFile->CountryName;
				self::$RegionCode = $XMLFile->RegionCode;
				self::$RegionName = $XMLFile->RegionName;
				self::$City = $XMLFile->City;
				self::$ZipCode = $XMLFile->ZipCode;
				self::$Latitude = $XMLFile->Latitude;
				self::$Longitude = $XMLFile->Longitude;
				self::$MetroCode = $XMLFile->MetroCode;
				self::$AreaCode = $XMLFile->AreaCode;
				//unlink($XMLFile);
			}
		}
		catch (Exception $e){
			self::DBug('Caught exception: '. $e->getMessage(). ' in method: '.__METHOD__.' in file: '.__FILE__);
			return false;
		}
		return true;
	}
}
/* ----------------------------------------------------------------------------------------------------------------- */

/*
Copyright (C) 2012 - 2014 Vagharshak Tozalakyan <vagh@tozalakyan.com>

Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

MapBuilder class, ver. 1.05

Requirements:
- PHP 5.2 or higher version;
- JSON extension (optional, used for Geocoding only);
- Sockets extension (optional, used for Geocoding only with appropriate fetch mode);
- CURL extension (optional, used for Geocoding only with appropriate fetch mode);
- An active Internet connection to access the Google Maps API.
*/

class MapBuilderException extends Exception {};

class MapBuilder
{
    const MAP_TYPE_ID_HYBRID = 'HYBRID';
    const MAP_TYPE_ID_ROADMAP = 'ROADMAP';
    const MAP_TYPE_ID_SATELLITE = 'SATELLITE';
    const MAP_TYPE_ID_TERRAIN = 'TERRAIN';

    const CONTROL_POSITION_BOTTOM_CENTER = 'BOTTOM_CENTER';
    const CONTROL_POSITION_BOTTOM_LEFT = 'BOTTOM_LEFT';
    const CONTROL_POSITION_BOTTOM_RIGHT = 'BOTTOM_RIGHT';
    const CONTROL_POSITION_LEFT_BOTTOM = 'LEFT_BOTTOM';
    const CONTROL_POSITION_LEFT_CENTER = 'LEFT_CENTER';
    const CONTROL_POSITION_LEFT_TOP = 'LEFT_TOP';
    const CONTROL_POSITION_RIGHT_BOTTOM = 'RIGHT_BOTTOM';
    const CONTROL_POSITION_RIGHT_CENTER = 'RIGHT_CENTER';
    const CONTROL_POSITION_RIGHT_TOP = 'RIGHT_TOP';
    const CONTROL_POSITION_TOP_CENTER = 'TOP_CENTER';
    const CONTROL_POSITION_TOP_LEFT = 'TOP_LEFT';
    const CONTROL_POSITION_TOP_RIGHT = 'TOP_RIGHT';

    const MAP_TYPE_CONTROL_STYLE_DEFAULT = 'DEFAULT';
    const MAP_TYPE_CONTROL_STYLE_DROPDOWN_MENU = 'DROPDOWN_MENU';
    const MAP_TYPE_CONTROL_STYLE_HORIZONTAL_BAR = 'HORIZONTAL_BAR';

    const SCALE_CONTROL_STYLE_DEFAULT = 'DEFAULT';

    const ZOOM_CONTROL_STYLE_DEFAULT = 'DEFAULT';
    const ZOOM_CONTROL_STYLE_LARGE = 'LARGE';
    const ZOOM_CONTROL_STYLE_SMALL = 'SMALL';

    const URL_FETCH_METHOD_CURL = 'curl';
    const URL_FETCH_METHOD_SOCKETS = 'sockets';

    const ANIMATION_BOUNCE = 'BOUNCE';
    const ANIMATION_DROP = 'DROP';

    protected $_id = '';
    protected $_width = 600;
    protected $_height = 600;
    protected $_fullScreen = false;

    protected $_lat = 51.522784;
    protected $_lng = 0.020168;

    protected $_sensor = false;
    protected $_geoMarker = null;
    protected $_overrideCenterByGeo = false;

    protected $_backgroundColor = null;
    protected $_disableDefaultUI = null;
    protected $_disableDoubleClickZoom = null;
    protected $_draggable = null;
    protected $_draggableCursor = null;
    protected $_draggingCursor = null;
    protected $_heading = null;
    protected $_keyboardShortcuts = null;
    protected $_mapMaker = null;
    protected $_mapTypeControl = null;
    protected $_mapTypeControlIds = array(self::MAP_TYPE_ID_HYBRID, self::MAP_TYPE_ID_ROADMAP, self::MAP_TYPE_ID_SATELLITE, self::MAP_TYPE_ID_TERRAIN);
    protected $_mapTypeControlPosition = self::CONTROL_POSITION_RIGHT_TOP;
    protected $_mapTypeControlStyle = self::MAP_TYPE_CONTROL_STYLE_DEFAULT;
    protected $_mapTypeId = self::MAP_TYPE_ID_HYBRID;
    protected $_maxZoom = null;
    protected $_minZoom = null;
    protected $_noClear = null;
    protected $_overviewMapControl = null;
    protected $_overviewMapControlOpened = null;
    protected $_panControl = null;
    protected $_panControlPosition = self::CONTROL_POSITION_LEFT_TOP;
    protected $_rotateControl = null;
    protected $_rotateControlPosition = self::CONTROL_POSITION_LEFT_TOP;
    protected $_scaleControl = null;
    protected $_scaleControlStyle = self::SCALE_CONTROL_STYLE_DEFAULT;
    protected $_scaleControlPosition = self::CONTROL_POSITION_LEFT_BOTTOM;
    protected $_scrollwheel = null;
    protected $_streetViewControl = null;
    protected $_streetViewControlPosition = null;
    protected $_tilt = null;
    protected $_zoom = 15;
    protected $_zoomControl = null;
    protected $_zoomControlPosition = self::CONTROL_POSITION_LEFT_TOP;
    protected $_zoomControlStyle = self::ZOOM_CONTROL_STYLE_DEFAULT;

    protected $_markers = array();
    protected $_polylines = array();
    protected $_polygons = array();

    protected static $defMarkerOptions = array(
        'animation' => null,
        'clickable' => null,
        'cursor' => null,
        'draggable' => null,
        'flat' => null,
        'icon' => null,
        'defColor' => null,
        'defSymbol' => null,
        'optimized' => null,
        'raiseOnDrag' => null,
        'shadow' => null,
        'shape' => null,
        'title' => null,
        'visible' => null,
        'zIndex' => null,
        'html' => null,
        'infoMaxWidth' => null,
        'infoDisableAutoPan' => null,
        'infoZIndex' => null,
        'infoCloseOthers' => false,
    );

    public function __construct($id = '')
    {
        $this->setId($id);
    }

    public function setId($id)
    {
        $this->_id = ($id == '' ? 'map' . mt_rand(10000, 99999) : $id);
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setSize($width, $height)
    {
        $this->_width = abs(intval($width));
        $this->_height = abs(intval($height));
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setFullScreen($fullScreen)
    {
        $this->_fullScreen = $fullScreen;
    }

    public function getFullScreen()
    {
        return $this->_fullScreen;
    }

    public function setCenter($lat, $lng)
    {
        $this->_lat = $lat;
        $this->_lng = $lng;
    }

    public function getCenterLat()
    {
        return $this->_lat;
    }

    public function getCenterLng()
    {
        return $this->_lng;
    }

    public function setSensor($sensor)
    {
        $this->_sensor = $sensor;
    }

    public function getSensor()
    {
        return $this->_sensor;
    }

    public function setDisableDefaultUI($disableDefaultUI)
    {
        $this->_disableDefaultUI = $disableDefaultUI;
    }

    public function getDisableDefaultUI()
    {
        return $this->_disableDefaultUI;
    }

    public function setDisableDoubleClickZoom($disableDoubleClickZoom)
    {
        $this->_disableDoubleClickZoom = $disableDoubleClickZoom;
    }

    public function getDisableDoubleClickZoom()
    {
        return $this->_disableDoubleClickZoom;
    }

    public function setDraggable($draggable)
    {
        $this->_draggable = $draggable;
    }

    public function getDraggable()
    {
        return $this->_draggable;
    }

    public function setDraggableCursor($draggableCursor)
    {
        $this->_draggableCursor = $draggableCursor;
    }

    public function getDraggableCursor()
    {
        return $this->_draggableCursor;
    }

    public function setDraggingCursor($draggingCursor)
    {
        $this->_draggingCursor = $draggingCursor;
    }

    public function getDraggingCursor()
    {
        return $this->_draggingCursor;
    }

    public function setHeading($heading)
    {
        $this->_heading = abs(intval($heading));
    }

    public function getHeading()
    {
        return $this->_heading;
    }

    public function setKeyboardShortcuts($keyboardShortcuts)
    {
        $this->_keyboardShortcuts = $keyboardShortcuts;
    }

    public function getKeyboardShortcuts()
    {
        return $this->_keyboardShortcuts;
    }

    public function setMapMaker($mapMaker)
    {
        $this->_mapMaker = $mapMaker;
    }

    public function getMapMaker()
    {
        return $this->_mapMaker;
    }

    public function setMapTypeControl($mapTypeControl)
    {
        $this->_mapTypeControl = $mapTypeControl;
    }

    public function getMapTypeControl()
    {
        return $this->_mapTypeControl;
    }

    public function setMapTypeControlIds($mapTypeControlIds)
    {
        $this->_mapTypeControlIds = $mapTypeControlIds;
    }

    public function getMapTypeControlIds()
    {
        return $this->_mapTypeControlIds;
    }

    public function setMapTypeControlPosition($mapTypeControlPosition)
    {
        $this->_mapTypeControlPosition = $mapTypeControlPosition;
    }

    public function getMapTypeControlPosition()
    {
        return $this->_mapTypeControlPosition;
    }

    public function setMapTypeControlStyle($mapTypeControlStyle)
    {
        $this->_mapTypeControlStyle = $mapTypeControlStyle;
    }

    public function getMapTypeControlStyle()
    {
        return $this->_mapTypeControlStyle;
    }

    public function setMapTypeId($mapTypeId)
    {
        $this->_mapTypeId = $mapTypeId;
    }

    public function getMapTypeId()
    {
        return $this->_mapTypeId;
    }

    public function setMaxZoom($maxZoom)
    {
        $this->_maxZoom = abs(intval($maxZoom));
    }

    public function getMaxZoom()
    {
        return $this->_maxZoom;
    }

    public function setMinZoom($minZoom)
    {
        $this->_minZoom = abs(intval($minZoom));
    }

    public function getMinZoom()
    {
        return $this->_minZoom;
    }

    public function setNoClear($noClear)
    {
        $this->_noClear = $noClear;
    }

    public function getNoClear()
    {
        return $this->_noClear;
    }

    public function setOverviewMapControl($overviewMapControl)
    {
        $this->_overviewMapControl = $overviewMapControl;
    }

    public function getOverviewMapControl()
    {
        return $this->_overviewMapControl;
    }

    public function setOverviewMapControlOpened($overviewMapControlOpened)
    {
        $this->_overviewMapControlOpened = $overviewMapControlOpened;
    }

    public function getOverviewMapControlOpened()
    {
        return $this->_overviewMapControlOpened;
    }

    public function setPanControl($panControl)
    {
        $this->_panControl = $panControl;
    }

    public function getPanControl()
    {
        return $this->_panControl;
    }

    public function setPanControlPosition($panControlPosition)
    {
        $this->_panControlPosition = $panControlPosition;
    }

    public function getPanControlPosition()
    {
        return $this->_panControlPosition;
    }

    public function setRotateControl($rotateControl)
    {
        $this->_rotateControl = $rotateControl;
    }

    public function getRotateControl()
    {
        return $this->_rotateControl;
    }

    public function setRotateControlPosition($rotateControlPosition)
    {
        $this->_rotateControlPosition = $rotateControlPosition;
    }

    public function getRotateControlPosition()
    {
        return $this->_rotateControlPosition;
    }

    public function setScaleControl($scaleControl)
    {
        $this->_scaleControl = $scaleControl;
    }

    public function getScaleControl()
    {
        return $this->_scaleControl;
    }

    public function setScaleControlStyle($scaleControlStyle)
    {
        $this->_scaleControlStyle = $scaleControlStyle;
    }

    public function getScaleControlStyle()
    {
        return $this->_scaleControlStyle;
    }

    public function setScaleControlPosition($scaleControlPosition)
    {
        $this->_scaleControlPosition = $scaleControlPosition;
    }

    public function getScaleControlPosition()
    {
        return $this->_scaleControlPosition;
    }

    public function setScrollwheel($scrollwheel)
    {
        $this->_scrollwheel = $scrollwheel;
    }

    public function getScrollwheel()
    {
        return $this->_scrollwheel;
    }

    public function setStreetViewControl($streetViewControl)
    {
        $this->_streetViewControl = $streetViewControl;
    }

    public function getStreetViewControl()
    {
        return $this->_streetViewControl;
    }

    public function setStreetViewControlPosition($streetViewControlPosition)
    {
        $this->_streetViewControlPosition = $streetViewControlPosition;
    }

    public function getStreetViewControlPosition()
    {
        return $this->_streetViewControlPosition;
    }

    public function setTilt($tilt)
    {
        $this->_tilt = abs(intval($tilt));
    }

    public function getTilt()
    {
        return $this->_tilt;
    }

    public function setZoom($zoom)
    {
        $this->_zoom = abs(intval($zoom));
    }

    public function getZoom()
    {
        return $this->_zoom;
    }

    public function setZoomControl($zoomControl)
    {
        $this->_zoomControl = $zoomControl;
    }

    public function getZoomControl()
    {
        return $this->_zoomControl;
    }

    public function setZoomControlPosition($zoomControlPosition)
    {
        $this->_zoomControlPosition = $zoomControlPosition;
    }

    public function getZoomControlPosition()
    {
        return $this->_zoomControlPosition;
    }

    public function setZoomControlStyle($zoomControlStyle)
    {
        $this->_zoomControlStyle = $zoomControlStyle;
    }

    public function getZoomControlStyle()
    {
        return $this->_zoomControlStyle;
    }

    protected function _getByCurl($url)
    {
        if (!function_exists('curl_init')) {
            throw new MapBuilderException('cURL extension not installed.');
        }
        if (!($curl = curl_init($url))) {
            throw new MapBuilderException('Unable to initialize a cURL session.');
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (false === ($response = curl_exec($curl))) {
            throw new MapBuilderException(curl_error($curl));
        }
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpStatus != 200) {
            throw new MapBuilderException($response);
        }
        return $response;
    }

    protected function _getBySockets($url)
    {
        $parsedUrl  = parse_url($url);
        $scheme     = $_SERVER['HTTPS'];
	    if (!$scheme){
	        $scheme = "http";
	    } else {
	        $scheme = "https";
	    }
        $host 	    = $parsedUrl['host'];
        $port 	    = (isset($parsedUrl['port']) ? $parsedUrl['port'] : '80');
        $path 	    = (isset($parsedUrl['path']) ? $parsedUrl['path'] : '/');
        if (isset($parsedUrl['query'])) {
            $path .= '?' . $parsedUrl['query'];
        }
        $timeout = 10;
        $response = '';
        if (!($fp = @fsockopen($host, $port, $errNo, $errStr, $timeout))) {
            throw new MapBuilderException('Socket error #{' . $errNo . '}: {' . $errStr . '}');
        }
        fputs($fp, "GET $path HTTP/1.0\r\n" .
            "Host: $host\r\n" .
            "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
            "Accept: */*\r\n" .
            "Accept-Language: en-us,en;q=0.5\r\n" .
            "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
            "Keep-Alive: 300\r\n" .
            "Connection: keep-alive\r\n" .
            "Referer: $scheme://$host\r\n\r\n"
        );
        while ($line = fread($fp, 4096)) {
            $response .= $line;
        }
        fclose($fp);
        $responseBody = substr($response, strpos($response, "\r\n\r\n") + 4);
        list($firstLine) = explode("\r\n", $response);
        if (false === strpos($firstLine, ' 200 OK')) {
           throw new MapBuilderException($responseBody);
        }
        return $responseBody;
    }

    public function getLatLng($address, $urlFetchMethod = self::URL_FETCH_METHOD_SOCKETS)
    {
        $address = str_replace('�', '\'', $address);
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . rawurlencode($address) . '&sensor=false';
        if ($urlFetchMethod == self::URL_FETCH_METHOD_SOCKETS) {
            $json = $this->_getBySockets($url);
        } else {
            $json = $this->_getByCurl($url);
        }
        $resp = json_decode($json);
        if (isset($resp->results[0]->geometry->location->lat)) {
            $lat = $resp->results[0]->geometry->location->lat;
            $lng = $resp->results[0]->geometry->location->lng;
            return array('lat' => $lat, 'lng' => $lng);
        } else {
            throw new MapBuilderException('Unable to get coordinates. JSON parser error.');
        }
    }

    public function addMarker($lat, $lng, $options = array())
    {
        $opts = self::$defMarkerOptions;
        foreach ($options as $k => $v) {
            $opts[$k] = $v;
        }
        $this->_markers[] = array(
            'lat' => $lat,
            'lng' => $lng,
            'options' => $opts
        );
        return sizeof($this->_markers) - 1;
    }

    public function getMarkerLat($index)
    {
        if (isset($this->_markers[$index])) {
            return $this->_markers[$index]['lat'];
        } else {
            return false;
        }
    }

    public function getMarkerLng($index)
    {
        if (isset($this->_markers[$index])) {
            return $this->_markers[$index]['lng'];
        } else {
            return false;
        }
    }

    public function getMarkerOptions($index)
    {
        if (isset($this->_markers[$index])) {
            return $this->_markers[$index]['options'];
        } else {
            return false;
        }
    }

    public function getNumMarkers()
    {
        return sizeof($this->_markers);
    }

    public function removeMarker($index)
    {
        if (isset($this->_markers[$index])) {
            unset($this->_markers[$index]);
            return true;
        } else {
            return false;
        }
    }

    public function clearMarkers()
    {
        $this->_markers = array();
    }

    public function addGeoMarker($options = array())
    {
        $opts = self::$defMarkerOptions;
        foreach ($options as $k => $v) {
            $opts[$k] = $v;
        }
        $this->_geoMarker = array(
            'options' => $opts
        );
        $this->_sensor = true;
    }

    public function removeGeoMarker($resetSensor = true)
    {
        $this->_geoMarker = null;
        if (!$this->_overrideCenterByGeo && $resetSensor) {
            $this->_sensor = false;
        }
    }

    public function overrideCenterByGeo($override = true, $resetSensor = true)
    {
        $this->_overrideCenterByGeo = $override;
        if ($override) {
            $this->_sensor = true;
        } elseif (is_null($this->_geoMarker) && $resetSensor) {
            $this->_sensor = false;
        }
    }

    public function addPolyline($path, $color = '#000000', $weight = 1, $opacity = 1.0)
    {
        $this->_polylines[] = array(
            'path' => $path,
            'color' => $color,
            'weight' => $weight,
            'opacity' => $opacity
        );
        return sizeof($this->_polylines) - 1;
    }

    public function getNumPolylines()
    {
        return sizeof($this->_polylines);
    }

    public function removePolyline($index)
    {
        if (isset($this->_polylines[$index])) {
            unset($this->_polylines[$index]);
            return true;
        } else {
            return false;
        }
    }

    public function clearPolylines()
    {
        $this->_polylines = array();
    }

    public function addPolygon($path, $strokeColor = '#000000', $fillColor = '#FF0000', $strokeWeight = 1, $strokeOpacity = 1.0, $fillOpacity = 0.35)
    {
        $this->_polygons[] = array(
            'path' => $path,
            'strokeColor' => $strokeColor,
            'fillColor' => $fillColor,
            'strokeWeight' => $strokeWeight,
            'strokeOpacity' => $strokeOpacity,
            'fillOpacity' => $fillOpacity
        );
        return sizeof($this->_polygons) - 1;
    }

    public function getNumPolygons()
    {
        return sizeof($this->_polygons);
    }

    public function removePolygon($index)
    {
        if (isset($this->_polygons[$index])) {
            unset($this->_polygons[$index]);
            return true;
        } else {
            return false;
        }
    }

    public function clearPolygons()
    {
        $this->_polygons = array();
    }

    public function show($output = true)
    {
        $html = '';
        $html .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=' . ($this->_sensor ? 'true' : 'false') . '"></script>' . "\n";
        $html .= '<script type="text/javascript">' . "\n";
        $html .= '<!--' . "\n";
        $html .= 'var map;' . "\n";
        $html .= 'var markers = new Array();' . "\n";
        $html .= 'var infos = new Array();' . "\n";
        $html .= 'var polylines = new Array();' . "\n";
        $html .= 'var polygons = new Array();' . "\n";
        $html .= 'var geoMarker = null;' . "\n";
        $html .= 'var geoInfo = null;' . "\n";
        $html .= 'function initialize() {' . "\n";
        $html .= '    var latlng = new google.maps.LatLng(' . $this->_lat . ', ' . $this->_lng . ');' . "\n";

        $html .= '    var mapTypeControlOptions = {' . "\n";
        $html .= '        mapTypeIds: [';
        foreach ($this->_mapTypeControlIds as $id) {
            $html .= 'google.maps.MapTypeId.' . $id . ', ';
        }
        $html = substr($html, 0, -2);
        $html .= '],' . "\n";
        $html .= '        position: google.maps.ControlPosition.' . $this->_mapTypeControlPosition . ',' . "\n";
        $html .= '        style: google.maps.MapTypeControlStyle.' . $this->_mapTypeControlStyle . "\n";
        $html .= '    };' . "\n";

        $html .= '    var overviewMapControlOptions = {' . "\n";
        $html .= '        opened: ' . ($this->_overviewMapControlOpened ? 'true' : 'false') . "\n";
        $html .= '    };' . "\n";

        $html .= '    var panControlOptions = {' . "\n";
        $html .= '        position: google.maps.ControlPosition.' . $this->_panControlPosition . "\n";
        $html .= '    };' . "\n";

        $html .= '    var rotateControlOptions = {' . "\n";
        $html .= '        position: google.maps.ControlPosition.' . $this->_rotateControlPosition . "\n";
        $html .= '    };' . "\n";

        $html .= '    var scaleControlOptions = {' . "\n";
        $html .= '        position: google.maps.ControlPosition.' . $this->_scaleControlPosition . ',' . "\n";
        $html .= '        style: google.maps.ScaleControlStyle.' . $this->_scaleControlStyle . "\n";
        $html .= '    };' . "\n";

        if (!is_null($this->_streetViewControlPosition)) {
            $html .= '    var streetViewControlOptions = {' . "\n";
            $html .= '        position: google.maps.ControlPosition.' . $this->_streetViewControlPosition . "\n";
            $html .= '    };' . "\n";
        }

        $html .= '    var zoomControlOptions = {' . "\n";
        $html .= '        position: google.maps.ControlPosition.' . $this->_zoomControlPosition . ',' . "\n";
        $html .= '        style: google.maps.ZoomControlStyle.' . $this->_zoomControlStyle . "\n";
        $html .= '    };' . "\n";

        $html .= '    var myOptions = {' . "\n";

        if (!is_null($this->_backgroundColor)) {
            $html .= '        backgroundColor: "' . htmlspecialchars($this->_backgroundColor) . '",' . "\n";
        }
        if (!is_null($this->_disableDefaultUI)) {
            $html .= '        disableDefaultUI: ' . ($this->_disableDefaultUI ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_disableDoubleClickZoom)) {
            $html .= '        disableDoubleClickZoom: ' . ($this->_disableDoubleClickZoom ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_draggable)) {
            $html .= '        draggable: ' . ($this->_draggable ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_draggableCursor)) {
            $html .= '        draggableCursor: "' . $this->_draggableCursor . '",' . "\n";
        }
        if (!is_null($this->_draggingCursor)) {
            $html .= '        draggingCursor: "' . $this->_draggingCursor . '",' . "\n";
        }
        if (!is_null($this->_heading)) {
            $html .= '        heading: ' . abs(intval($this->_heading)) . ',' . "\n";
        }
        if (!is_null($this->_keyboardShortcuts)) {
            $html .= '        keyboardShortcuts: ' . ($this->_keyboardShortcuts ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_mapMaker)) {
            $html .= '        mapMaker: ' . ($this->_mapMaker ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_mapTypeControl)) {
            $html .= '        mapTypeControl: ' . ($this->_mapTypeControl ? 'true' : 'false') . ',' . "\n";
        }
        $html .= '        mapTypeControlOptions: mapTypeControlOptions,' . "\n";
        $html .= '        mapTypeId: google.maps.MapTypeId.' . $this->_mapTypeId . ',' . "\n";

        if (!is_null($this->_maxZoom)) {
            $html .= '        maxZoom: ' . abs(intval($this->_maxZoom)) . ',' . "\n";
        }
        if (!is_null($this->_minZoom)) {
            $html .= '        minZoom: ' . abs(intval($this->_minZoom)) . ',' . "\n";
        }

        if (!is_null($this->_noClear)) {
            $html .= '        noClear: ' . ($this->_noClear ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_overviewMapControl)) {
            $html .= '        overviewMapControl: ' . ($this->_overviewMapControl ? 'true' : 'false') . ',' . "\n";
        }
        $html .= '        overviewMapControlOptions: overviewMapControlOptions,' . "\n";

        if (!is_null($this->_panControl)) {
            $html .= '        panControl: ' . ($this->_panControl ? 'true' : 'false') . ',' . "\n";
        }
        $html .= '        panControlOptions: panControlOptions,' . "\n";

        if (!is_null($this->_rotateControl)) {
            $html .= '        rotateControl: ' . ($this->_rotateControl ? 'true' : 'false') . ',' . "\n";
        }
        $html .= '        rotateControlOptions: rotateControlOptions,' . "\n";

        if (!is_null($this->_scaleControl)) {
            $html .= '        scaleControl: ' . ($this->_scaleControl ? 'true' : 'false') . ',' . "\n";
        }
        $html .= '        scaleControlOptions: scaleControlOptions,' . "\n";

        if (!is_null($this->_scrollwheel)) {
            $html .= '        scrollwheel: ' . ($this->_scrollwheel ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_streetViewControl)) {
            $html .= '        streetViewControl: ' . ($this->_streetViewControl ? 'true' : 'false') . ',' . "\n";
        }
        if (!is_null($this->_streetViewControlPosition)) {
            $html .= '        streetViewControlOptions: streetViewControlOptions,' . "\n";
        }
        if (!is_null($this->_tilt)) {
            $html .= '        tilt: ' . abs(intval($this->_tilt)) . ',' . "\n";
        }
        $html .= '        zoom: ' . abs(intval($this->_zoom)) . ',' . "\n";
        if (!is_null($this->_zoomControl)) {
            $html .= '        zoomControl: ' . ($this->_zoomControl ? 'true' : 'false') . ',' . "\n";
        }
        $html .= '        zoomControlOptions: zoomControlOptions,' . "\n";

        $html .= '        center: latlng' . "\n";
        $html .= '    };' . "\n";
        $html .= '    map = new google.maps.Map(document.getElementById("' . $this->_id . '"), myOptions);' . "\n";

        foreach ($this->_markers as $i => $marker) {
            $html .= '    markers['. $i . '] = new google.maps.Marker({' . "\n";

            if (!is_null($marker['options']['animation'])) {
                $html .= '        animation: google.maps.Animation.' . $marker['options']['animation'] . ',' . "\n";
            }
            if (!is_null($marker['options']['clickable'])) {
                $html .= '        clickable: ' . ($marker['options']['clickable'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['cursor'])) {
                $html .= '        cursor: "' . $marker['options']['cursor'] . '",' . "\n";
            }
            if (!is_null($marker['options']['draggable'])) {
                $html .= '        draggable: ' . ($marker['options']['draggable'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['flat'])) {
                $html .= '        flat: ' . ($marker['options']['flat'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['icon'])) {
                $html .= '        icon: "' . $marker['options']['icon'] . '",' . "\n";
            }
            if (is_null($marker['options']['icon']) || $marker['options']['icon'] == '') {
                if (!is_null($marker['options']['defSymbol']) || !is_null($marker['options']['defColor'])) {
                    $sym = (!is_null($marker['options']['defSymbol']) ? $marker['options']['defSymbol'] : '%E2%80%A2');
                    $col = (!is_null($marker['options']['defColor']) ? $marker['options']['defColor'] : 'FE7569');
                    $col = ltrim($col, '#');
                    $defIconUrl = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' . $sym . '|' . $col;
                    $html .= '        icon: "' . $defIconUrl . '",' . "\n";
                }
            }
            if (!is_null($marker['options']['optimized'])) {
                $html .= '        optimized: ' . ($marker['options']['optimized'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['raiseOnDrag'])) {
                $html .= '        raiseOnDrag: ' . ($marker['options']['raiseOnDrag'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['shadow'])) {
                $html .= '        shadow: ' . ($marker['options']['shadow'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['title'])) {
                $html .= '        title: "' . htmlspecialchars($marker['options']['title']) . '",' . "\n";
            }
            if (!is_null($marker['options']['visible'])) {
                $html .= '        visible: ' . ($marker['options']['visible'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($marker['options']['zIndex'])) {
                $html .= '        zIndex: ' . abs(intval($marker['options']['zIndex'])) . ',' . "\n";
            }

            $html .= '        position: new google.maps.LatLng(' . $marker['lat'] . ', ' . $marker['lng'] . '),' . "\n";
            $html .= '        map: map' . "\n";
            $html .= '    });' . "\n";
            if (!empty($marker['options']['html'])) {
                $html .= '    infos[' . $i . '] = new google.maps.InfoWindow({' . "\n";

                if (!is_null($marker['options']['infoMaxWidth'])) {
                    $html .= '        maxWidth: ' . abs(intval($marker['options']['infoMaxWidth'])) . ',' . "\n";
                }
                if (!is_null($marker['options']['infoDisableAutoPan'])) {
                    $html .= '        disableAutoPan: ' . ($marker['options']['infoDisableAutoPan'] ? 'true' : 'false') . ',' . "\n";
                }
                if (!is_null($marker['options']['infoZIndex'])) {
                    $html .= '        zIndex: ' . abs(intval($marker['options']['infoZIndex'])) . ',' . "\n";
                }

                $html .= '        content: "' . addslashes($marker['options']['html']) . '"' . "\n";

                $html .= '    });' . "\n";
                $html .= '    google.maps.event.addListener(markers[' . $i . '], "click", function() {' . "\n";

                if ($marker['options']['infoCloseOthers']) {
                    $html .= '        for (i = 0; i < infos.length; i++) {' . "\n";
                    $html .= '            if (infos[i] != null) { infos[i].close(); }' . "\n";
                    $html .= '        }' . "\n";
                    $html .= '        if (geoInfo != null) { geoInfo.close(); }' . "\n";
                }
                $html .= '        infos[' . $i . '].open(map, markers[' . $i . ']);' . "\n";
                $html .= '    });' . "\n";
            } else {
                $html .= '    infos[' . $i . '] = null;' . "\n";
            }
        }

        foreach ($this->_polylines as $i => $polyline) {
            $numPoints = sizeof($polyline['path']);
            $html .= '    polylines[' . $i . '] = new google.maps.Polyline({' . "\n";
            $html .= '        path: [' . "\n";
            foreach ($polyline['path'] as $j => $point) {
                $html .= '            new google.maps.LatLng(' . $point[0] . ', ' . $point[1] . ')' . ($j < $numPoints - 1 ? ',' : '') . "\n";
            }
            $html .= '        ],' . "\n";
            $html .= '        strokeColor: "' . $polyline['color'] . '",' . "\n";
            $html .= '        strokeWeight: "' . $polyline['weight'] . '",' . "\n";
            $html .= '        strokeOpacity: "' . $polyline['opacity'] . '"' . "\n";
            $html .= '    });' . "\n";
            $html .= '    polylines[' . $i . '].setMap(map);' . "\n";
        }

        foreach ($this->_polygons as $i => $polygon) {
            $numPoints = sizeof($polygon['path']);
            $html .= '    polygons[' . $i . '] = new google.maps.Polygon({' . "\n";
            $html .= '        path: [' . "\n";
            foreach ($polygon['path'] as $j => $point) {
                $html .= '            new google.maps.LatLng(' . $point[0] . ', ' . $point[1] . ')' . ($j < $numPoints - 1 ? ',' : '') . "\n";
            }
            $html .= '        ],' . "\n";
            $html .= '        strokeColor: "' . $polygon['strokeColor'] . '",' . "\n";
            $html .= '        strokeWeight: "' . $polygon['strokeWeight'] . '",' . "\n";
            $html .= '        strokeOpacity: "' . $polygon['strokeOpacity'] . '",' . "\n";
            $html .= '        fillColor: "' . $polygon['fillColor'] . '",' . "\n";
            $html .= '        fillOpacity: "' . $polygon['fillOpacity'] . '"' . "\n";
            $html .= '    });' . "\n";
            $html .= '    polygons[' . $i . '].setMap(map);' . "\n";
        }

        if (!is_null($this->_geoMarker)) {

            $html .= '    if (navigator.geolocation) {' . "\n";
            $html .= '        navigator.geolocation.getCurrentPosition(function(position) {' . "\n";
            $html .= '            gpsLat = position.coords.latitude;' . "\n";
            $html .= '            gpsLng = position.coords.longitude;' . "\n";

            $html .= '            geoMarker = new google.maps.Marker({' . "\n";

            if (!is_null($this->_geoMarker['options']['animation'])) {
                $html .= '                animation: google.maps.Animation.' . $this->_geoMarker['options']['animation'] . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['clickable'])) {
                $html .= '                clickable: ' . ($this->_geoMarker['options']['clickable'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['cursor'])) {
                $html .= '                cursor: "' . $this->_geoMarker['options']['cursor'] . '",' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['draggable'])) {
                $html .= '                draggable: ' . ($this->_geoMarker['options']['draggable'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['flat'])) {
                $html .= '                flat: ' . ($this->_geoMarker['options']['flat'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['icon'])) {
                $html .= '                icon: "' . $this->_geoMarker['options']['icon'] . '",' . "\n";
            }
            if (is_null($this->_geoMarker['options']['icon']) || $this->_geoMarker['options']['icon'] == '') {
                if (!is_null($this->_geoMarker['options']['defSymbol']) || !is_null($this->_geoMarker['options']['defColor'])) {
                    $sym = (!is_null($this->_geoMarker['options']['defSymbol']) ? $this->_geoMarker['options']['defSymbol'] : '%E2%80%A2');
                    $col = (!is_null($this->_geoMarker['options']['defColor']) ? $this->_geoMarker['options']['defColor'] : 'FE7569');
                    $col = ltrim($col, '#');
                    $defIconUrl = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' . $sym . '|' . $col;
                    $html .= '                icon: "' . $defIconUrl . '",' . "\n";
                }
            }
            if (!is_null($this->_geoMarker['options']['optimized'])) {
                $html .= '                optimized: ' . ($this->_geoMarker['options']['optimized'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['raiseOnDrag'])) {
                $html .= '                raiseOnDrag: ' . ($this->_geoMarker['options']['raiseOnDrag'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['shadow'])) {
                $html .= '                shadow: ' . ($this->_geoMarker['options']['shadow'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['title'])) {
                $html .= '                title: "' . htmlspecialchars($this->_geoMarker['options']['title']) . '",' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['visible'])) {
                $html .= '                visible: ' . ($this->_geoMarker['options']['visible'] ? 'true' : 'false') . ',' . "\n";
            }
            if (!is_null($this->_geoMarker['options']['zIndex'])) {
                $html .= '                zIndex: ' . abs(intval($this->_geoMarker['options']['zIndex'])) . ',' . "\n";
            }

            $html .= '                position: new google.maps.LatLng(gpsLat, gpsLng),' . "\n";
            $html .= '                map: map' . "\n";
            $html .= '            });' . "\n";
            if (!empty($this->_geoMarker['options']['html'])) {
                $html .= '            geoInfo = new google.maps.InfoWindow({' . "\n";

                if (!is_null($this->_geoMarker['options']['infoMaxWidth'])) {
                    $html .= '                maxWidth: ' . abs(intval($this->_geoMarker['options']['infoMaxWidth'])) . ',' . "\n";
                }
                if (!is_null($this->_geoMarker['options']['infoDisableAutoPan'])) {
                    $html .= '                disableAutoPan: ' . ($this->_geoMarker['options']['infoDisableAutoPan'] ? 'true' : 'false') . ',' . "\n";
                }
                if (!is_null($this->_geoMarker['options']['infoZIndex'])) {
                    $html .= '                zIndex: ' . abs(intval($this->_geoMarker['options']['infoZIndex'])) . ',' . "\n";
                }

                $html .= '                content: "' . addslashes($this->_geoMarker['options']['html']) . '"' . "\n";

                $html .= '            });' . "\n";
                $html .= '            google.maps.event.addListener(geoMarker, "click", function() {' . "\n";
                if ($this->_geoMarker['options']['infoCloseOthers']) {
                    $html .= '                for (i = 0; i < infos.length; i++) {' . "\n";
                    $html .= '                    if (infos[i] != null) { infos[i].close(); }' . "\n";
                    $html .= '                }' . "\n";
                    $html .= '                if (geoInfo != null) { geoInfo.close(); }' . "\n";
                }
                $html .= '                geoInfo.open(map, geoMarker);' . "\n";
                $html .= '            });' . "\n";
            }

            if ($this->_overrideCenterByGeo) {
                $html .= '            map.setCenter(new google.maps.LatLng(gpsLat, gpsLng));' . "\n";
            }

            $html .= '        }, function (error) {' . "\n";
            $html .= '	          switch(error.code) {' . "\n";
            $html .= '            case error.TIMEOUT:' . "\n";
            $html .= '		          alert("Geolocation error: Timeout.");' . "\n";
            $html .= '			      break;' . "\n";
            $html .= '		      case error.POSITION_UNAVAILABLE:' . "\n";
            $html .= '			      alert ("Geolocation error: Position unavailable.");' . "\n";
            $html .= '			      break;' . "\n";
            $html .= '		      case error.PERMISSION_DENIED:' . "\n";
            $html .= '			      alert("Geolocation error: Permission denied.");' . "\n";
            $html .= '			      break;' . "\n";
            $html .= '		      case error.UNKNOWN_ERROR:' . "\n";
            $html .= '			      alert ("Geolocation error: Unknown error.");' . "\n";
            $html .= '			      break;' . "\n";
            $html .= '    	      }' . "\n";
            $html .= '	      });' . "\n";
            $html .= '    }' . "\n";

        }

        $html .= 'if (typeof mbOnAfterInit == "function") mbOnAfterInit(map);' . "\n";

        $html .= '}' . "\n";
        $html .= 'window.onload = initialize;' . "\n";
        $html .= '//-->' . "\n";
        $html .= '</script>' . "\n";
        $html .= '<div id="' . $this->_id . '" style="' . ($this->_fullScreen ? 'width:100%;height:100%' : 'width:' . $this->_width . 'px;height:' . $this->_height . 'px') . '"></div>' . "\n";
        if ($output) {
            echo $html;
            return '';
        } else {
            return $html;
        }
    }
}
?>
