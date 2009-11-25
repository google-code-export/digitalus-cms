<?php
/**
 * Validator for the timezone
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @author      LowTower - lowtower@gmx.de
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     $Id:$
 * @category    Digitalus core
 * @package     Digitalus_Validate
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * Validator for the timezone
 *
 * @copyright   Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license     http://digitalus-media.com/license/new-bsd     New BSD License
 * @version     Release: @package_version@
 * @link        http://www.digitaluscms.com
 * @since       Release 1.8.0
 */
class Digitalus_Validate_Timezone extends Zend_Validate_Abstract
{
    /**
     * Error constants
     * @const string
     */
    const TIMEZONE = 'timezoneFalse';
    const REGION   = 'regionFalse';

    /**
     * Sets validator options
     * @param  string  $region The region to validate the timezone for
     * @param  boolean $strict If validation is done in strict mode
     * @return void
     */
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (1 < func_num_args()) {
            trigger_error('Multiple arguments to constructor are deprecated in favour of options array', E_USER_NOTICE);
            $case   = func_get_arg(1);
            $strict = func_get_arg(2);
            $this->setCase($case);
            $this->setStrict($strict);
        }

        if (is_array($options)) {
            if (isset($options['region'])) {
                $this->setRegion($options['region']);
                unset($options['region']);
            }
            if (isset($options['strict'])) {
                $this->setStrict($options['strict']);
                unset($options['strict']);
            }
        }
    }

    /**
     * Returns the regions
     * @param  boolean $strict If validation is done in strict mode
     * @return array
     */
    public static function getRegions($strict = true)
    {
        $regions = self::getTimezones(null, $strict);
        return array_keys($regions);
    }

    /**
     * Validates the timezone region
     * @param  string  $region The region to validate the timezone for
     * @param  boolean $strict If validation is done in strict mode
     * @return boolean
     */
    public static function isValidRegion($region, $strict = true)
    {
        if (in_array($region, self::getRegions($strict))) {
            return true;
        }
        return false;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if the given timezone $value is included in the
     * list with valid timezones
     *
     * @param  string  $value Given name of timezone
     * @param  string  $region The region to validate the timezone for
     * @param  boolean $strict If validation is done in strict mode
     * @return boolean
     */
    public function isValid($value, $region = null, $strict = true)
    {
        if (in_array($value, self::getValidTimezones($region, $strict))) {
            return true;
        }
        return false;
    }

    /**
     * Returns an array with the valid timezones
     *
     * @param  string  $region The region to validate the timezone for
     * @param  boolean $strict If validation is done in strict mode
     * @return array
     */
    public static function getValidTimezones($region = null, $strict = true)
    {
        $timezones = self::getTimezones($region, $strict);
        $validTimezones = array();
        if ($region === null) {
            foreach (self::getRegions($strict) as $region) {
                foreach ($timezones[$region] as $timezone) {
                    $validTimezones[$timezone] = $timezone;
                }
            }
        } else {
            if ('Deprecated' == $region && true == $strict) {
                require_once 'Digitalus/Validate/Exception.php';
                throw new Digitalus_Validate_Exception('The deprecated timezones are not valid in strict mode!');
            } else {
                foreach ($timezones[$region] as $timezone) {
                    $validTimezones[$timezone] = $timezone;
                }
            }
        }
        return $validTimezones;
    }

    /**
     * Returns an array with timezones for a given region
     *
     * @param  string  $region The region to validate the timezone for
     * @param  boolean $strict If validation is done in strict mode
     * @return array
     */
    public static function getTimezones($region = null, $strict = true)
    {
        if (isset($region)) {
            if (self::isValidRegion($region, $strict)) {
                if ('Deprecated' == $region && true == $strict) {
                    require_once 'Digitalus/Validate/Exception.php';
                    throw new Digitalus_Validate_Exception('The deprecated timezones are not valid in strict mode!');
                }
                $timezones = self::getTimezones(null, $strict);
                return $timezones[$region];
            }
        } else {
            $timezones = array(
                'Africa' => array(
                    'Africa/Abidjan',
                    'Africa/Accra',
                    'Africa/Addis_Ababa',
                    'Africa/Algiers',
                    'Africa/Asmara',
                    'Africa/Asmera',
                    'Africa/Bamako',
                    'Africa/Bangui',
                    'Africa/Banjul',
                    'Africa/Bissau',
                    'Africa/Blantyre',
                    'Africa/Brazzaville',
                    'Africa/Bujumbura',
                    'Africa/Cairo',
                    'Africa/Casablanca',
                    'Africa/Ceuta',
                    'Africa/Conakry',
                    'Africa/Dakar',
                    'Africa/Dar_es_Salaam',
                    'Africa/Djibouti',
                    'Africa/Douala',
                    'Africa/El_Aaiun',
                    'Africa/Freetown',
                    'Africa/Gaborone',
                    'Africa/Harare',
                    'Africa/Johannesburg',
                    'Africa/Kampala',
                    'Africa/Khartoum',
                    'Africa/Kigali',
                    'Africa/Kinshasa',
                    'Africa/Lagos',
                    'Africa/Libreville',
                    'Africa/Lome',
                    'Africa/Luanda',
                    'Africa/Lubumbashi',
                    'Africa/Lusaka',
                    'Africa/Malabo',
                    'Africa/Maputo',
                    'Africa/Maseru',
                    'Africa/Mbabane',
                    'Africa/Mogadishu',
                    'Africa/Monrovia',
                    'Africa/Nairobi',
                    'Africa/Ndjamena',
                    'Africa/Niamey',
                    'Africa/Nouakchott',
                    'Africa/Ouagadougou',
                    'Africa/Porto-Novo',
                    'Africa/Sao_Tome',
                    'Africa/Timbuktu',
                    'Africa/Tripoli',
                    'Africa/Tunis',
                    'Africa/Windhoek',
                ),
                'America' => array(
                    'America/Adak',
                    'America/Anchorage',
                    'America/Anguilla',
                    'America/Antigua',
                    'America/Araguaina',
                    'America/Argentina/Buenos_Aires',
                    'America/Argentina/Catamarca',
                    'America/Argentina/ComodRivadavia',
                    'America/Argentina/Cordoba',
                    'America/Argentina/Jujuy',
                    'America/Argentina/La_Rioja',
                    'America/Argentina/Mendoza',
                    'America/Argentina/Rio_Gallegos',
                    'America/Argentina/Salta',
                    'America/Argentina/San_Juan',
                    'America/Argentina/San_Luis',
                    'America/Argentina/Tucuman',
                    'America/Argentina/Ushuaia',
                    'America/Aruba',
                    'America/Asuncion',
                    'America/Atikokan',
                    'America/Atka',
                    'America/Bahia',
                    'America/Barbados',
                    'America/Belem',
                    'America/Belize',
                    'America/Blanc-Sablon',
                    'America/Boa_Vista',
                    'America/Bogota',
                    'America/Boise',
                    'America/Buenos_Aires',
                    'America/Cambridge_Bay',
                    'America/Campo_Grande',
                    'America/Cancun',
                    'America/Caracas',
                    'America/Catamarca',
                    'America/Cayenne',
                    'America/Cayman',
                    'America/Chicago',
                    'America/Chihuahua',
                    'America/Coral_Harbour',
                    'America/Cordoba',
                    'America/Costa_Rica',
                    'America/Cuiaba',
                    'America/Curacao',
                    'America/Danmarkshavn',
                    'America/Dawson',
                    'America/Dawson_Creek',
                    'America/Denver',
                    'America/Detroit',
                    'America/Dominica',
                    'America/Edmonton',
                    'America/Eirunepe',
                    'America/El_Salvador',
                    'America/Ensenada',
                    'America/Fort_Wayne',
                    'America/Fortaleza',
                    'America/Glace_Bay',
                    'America/Godthab',
                    'America/Goose_Bay',
                    'America/Grand_Turk',
                    'America/Grenada',
                    'America/Guadeloupe',
                    'America/Guatemala',
                    'America/Guayaquil',
                    'America/Guyana',
                    'America/Halifax',
                    'America/Havana',
                    'America/Hermosillo',
                    'America/Indiana/Indianapolis',
                    'America/Indiana/Knox',
                    'America/Indiana/Marengo',
                    'America/Indiana/Petersburg',
                    'America/Indiana/Tell_City',
                    'America/Indiana/Vevay',
                    'America/Indiana/Vincennes',
                    'America/Indiana/Winamac',
                    'America/Indianapolis',
                    'America/Inuvik',
                    'America/Iqaluit',
                    'America/Jamaica',
                    'America/Jujuy',
                    'America/Juneau',
                    'America/Kentucky/Louisville',
                    'America/Kentucky/Monticello',
                    'America/Knox_IN',
                    'America/La_Paz',
                    'America/Lima',
                    'America/Los_Angeles',
                    'America/Louisville',
                    'America/Maceio',
                    'America/Managua',
                    'America/Manaus',
                    'America/Marigot',
                    'America/Martinique',
                    'America/Mazatlan',
                    'America/Mendoza',
                    'America/Menominee',
                    'America/Merida',
                    'America/Mexico_City',
                    'America/Miquelon  ',
                    'America/Moncton',
                    'America/Monterrey',
                    'America/Montevideo',
                    'America/Montreal',
                    'America/Montserrat',
                    'America/Nassau',
                    'America/New_York',
                    'America/Nipigon',
                    'America/Nome',
                    'America/Noronha',
                    'America/North_Dakota/Center',
                    'America/North_Dakota/New_Salem',
                    'America/Panama',
                    'America/Pangnirtung',
                    'America/Paramaribo',
                    'America/Phoenix',
                    'America/Port-au-Prince',
                    'America/Port_of_Spain',
                    'America/Porto_Acre',
                    'America/Porto_Velho',
                    'America/Puerto_Rico',
                    'America/Rainy_River',
                    'America/Rankin_Inlet',
                    'America/Recife',
                    'America/Regina',
                    'America/Resolute',
                    'America/Rio_Branco',
                    'America/Rosario',
                    'America/Santarem',
                    'America/Santiago',
                    'America/Santo_Domingo',
                    'America/Sao_Paulo',
                    'America/Scoresbysund',
                    'America/Shiprock',
                    'America/St_Barthelemy',
                    'America/St_Johns',
                    'America/St_Kitts',
                    'America/St_Lucia',
                    'America/St_Thomas',
                    'America/St_Vincent ',
                    'America/Swift_Current',
                    'America/Tegucigalpa',
                    'America/Thule',
                    'America/Thunder_Bay',
                    'America/Tijuana',
                    'America/Toronto',
                    'America/Tortola',
                    'America/Vancouver',
                    'America/Virgin',
                    'America/Whitehorse',
                    'America/Winnipeg',
                    'America/Yakutat',
                    'America/Yellowknife',
                ),
                'Antarctica' => array(
                    'Antarctica/Casey',
                    'Antarctica/Davis',
                    'Antarctica/DumontDUrville',
                    'Antarctica/Mawson',
                    'Antarctica/McMurdo',
                    'Antarctica/Palmer',
                    'Antarctica/Rothera',
                    'Antarctica/South_Pole',
                    'Antarctica/Syowa',
                    'Antarctica/Vostok',
                ),
                'Arctic' => array(
                    'Arctic/Longyearbyen',
                ),
                'Asia' => array(
                    'Asia/Aden',
                    'Asia/Almaty',
                    'Asia/Amman',
                    'Asia/Anadyr',
                    'Asia/Aqtau',
                    'Asia/Aqtobe',
                    'Asia/Ashgabat',
                    'Asia/Ashkhabad',
                    'Asia/Baghdad',
                    'Asia/Bahrain',
                    'Asia/Baku',
                    'Asia/Bangkok',
                    'Asia/Beirut',
                    'Asia/Bishkek',
                    'Asia/Brunei',
                    'Asia/Calcutta',
                    'Asia/Choibalsan',
                    'Asia/Chongqing',
                    'Asia/Chungking',
                    'Asia/Colombo',
                    'Asia/Dacca',
                    'Asia/Damascus',
                    'Asia/Dhaka',
                    'Asia/Dili',
                    'Asia/Dubai',
                    'Asia/Dushanbe',
                    'Asia/Gaza',
                    'Asia/Harbin',
                    'Asia/Ho_Chi_Minh',
                    'Asia/Hong_Kong',
                    'Asia/Hovd',
                    'Asia/Irkutsk',
                    'Asia/Istanbul',
                    'Asia/Jakarta',
                    'Asia/Jayapura',
                    'Asia/Jerusalem',
                    'Asia/Kabul',
                    'Asia/Kamchatka',
                    'Asia/Karachi',
                    'Asia/Kashgar',
                    'Asia/Kathmandu',
                    'Asia/Katmandu',
                    'Asia/Kolkata',
                    'Asia/Krasnoyarsk',
                    'Asia/Kuala_Lumpur',
                    'Asia/Kuching',
                    'Asia/Kuwait',
                    'Asia/Macao',
                    'Asia/Macau',
                    'Asia/Magadan',
                    'Asia/Makassar',
                    'Asia/Manila',
                    'Asia/Muscat',
                    'Asia/Nicosia',
                    'Asia/Novosibirsk',
                    'Asia/Omsk',
                    'Asia/Oral',
                    'Asia/Phnom_Penh',
                    'Asia/Pontianak',
                    'Asia/Pyongyang',
                    'Asia/Qatar',
                    'Asia/Qyzylorda',
                    'Asia/Rangoon',
                    'Asia/Riyadh',
                    'Asia/Saigon',
                    'Asia/Sakhalin',
                    'Asia/Samarkand',
                    'Asia/Seoul',
                    'Asia/Shanghai',
                    'Asia/Singapore',
                    'Asia/Taipei',
                    'Asia/Tashkent',
                    'Asia/Tbilisi',
                    'Asia/Tehran',
                    'Asia/Tel_Aviv',
                    'Asia/Thimbu',
                    'Asia/Thimphu',
                    'Asia/Tokyo',
                    'Asia/Ujung_Pandang',
                    'Asia/Ulaanbaatar',
                    'Asia/Ulan_Bator',
                    'Asia/Urumqi',
                    'Asia/Vientiane',
                    'Asia/Vladivostok',
                    'Asia/Yakutsk',
                    'Asia/Yekaterinburg',
                    'Asia/Yerevan',
                ),
                'Atlantic' => array(
                    'Atlantic/Azores',
                    'Atlantic/Bermuda',
                    'Atlantic/Canary',
                    'Atlantic/Cape_Verde',
                    'Atlantic/Faeroe',
                    'Atlantic/Faroe',
                    'Atlantic/Jan_Mayen',
                    'Atlantic/Madeira',
                    'Atlantic/Reykjavik',
                    'Atlantic/South_Georgia',
                    'Atlantic/St_Helena',
                    'Atlantic/Stanley',
                ),
                'Australia' => array(
                    'Australia/ACT',
                    'Australia/Adelaide',
                    'Australia/Brisbane',
                    'Australia/Broken_Hill',
                    'Australia/Canberra',
                    'Australia/Currie',
                    'Australia/Darwin',
                    'Australia/Eucla',
                    'Australia/Hobart',
                    'Australia/LHI',
                    'Australia/Lindeman',
                    'Australia/Lord_Howe',
                    'Australia/Melbourne',
                    'Australia/North',
                    'Australia/NSW',
                    'Australia/Perth',
                    'Australia/Queensland',
                    'Australia/South',
                    'Australia/Sydney',
                    'Australia/Tasmania',
                    'Australia/Victoria',
                    'Australia/West',
                    'Australia/Yancowinna',
                ),
                'Europe' => array(
                    'Europe/Amsterdam',
                    'Europe/Andorra',
                    'Europe/Athens',
                    'Europe/Belfast',
                    'Europe/Belgrade',
                    'Europe/Berlin',
                    'Europe/Bratislava',
                    'Europe/Brussels',
                    'Europe/Bucharest',
                    'Europe/Budapest',
                    'Europe/Chisinau',
                    'Europe/Copenhagen',
                    'Europe/Dublin',
                    'Europe/Gibraltar',
                    'Europe/Guernsey',
                    'Europe/Helsinki',
                    'Europe/Isle_of_Man',
                    'Europe/Istanbul',
                    'Europe/Jersey',
                    'Europe/Kaliningrad',
                    'Europe/Kiev',
                    'Europe/Lisbon',
                    'Europe/Ljubljana',
                    'Europe/London',
                    'Europe/Luxembourg',
                    'Europe/Madrid',
                    'Europe/Malta',
                    'Europe/Mariehamn',
                    'Europe/Minsk',
                    'Europe/Monaco',
                    'Europe/Moscow',
                    'Europe/Nicosia',
                    'Europe/Oslo',
                    'Europe/Paris',
                    'Europe/Podgorica',
                    'Europe/Prague',
                    'Europe/Riga',
                    'Europe/Rome',
                    'Europe/Samara',
                    'Europe/San_Marino',
                    'Europe/Sarajevo',
                    'Europe/Simferopol',
                    'Europe/Skopje',
                    'Europe/Sofia',
                    'Europe/Stockholm',
                    'Europe/Tallinn',
                    'Europe/Tirane',
                    'Europe/Tiraspol',
                    'Europe/Uzhgorod',
                    'Europe/Vaduz',
                    'Europe/Vatican',
                    'Europe/Vienna',
                    'Europe/Vilnius',
                    'Europe/Volgograd',
                    'Europe/Warsaw',
                    'Europe/Zagreb',
                    'Europe/Zaporozhye',
                    'Europe/Zurich',
                ),
                'Indian' => array(
                    'Indian/Antananarivo',
                    'Indian/Chagos',
                    'Indian/Christmas',
                    'Indian/Cocos',
                    'Indian/Comoro',
                    'Indian/Kerguelen',
                    'Indian/Mahe',
                    'Indian/Maldives',
                    'Indian/Mauritius',
                    'Indian/Mayotte',
                    'Indian/Reunion',
                ),
                'Pacific' => array(
                    'Pacific/Apia',
                    'Pacific/Auckland',
                    'Pacific/Chatham',
                    'Pacific/Easter',
                    'Pacific/Efate',
                    'Pacific/Enderbury',
                    'Pacific/Fakaofo',
                    'Pacific/Fiji',
                    'Pacific/Funafuti',
                    'Pacific/Galapagos',
                    'Pacific/Gambier',
                    'Pacific/Guadalcanal',
                    'Pacific/Guam',
                    'Pacific/Honolulu',
                    'Pacific/Johnston',
                    'Pacific/Kiritimati',
                    'Pacific/Kosrae',
                    'Pacific/Kwajalein',
                    'Pacific/Majuro',
                    'Pacific/Marquesas',
                    'Pacific/Midway',
                    'Pacific/Nauru',
                    'Pacific/Niue',
                    'Pacific/Norfolk',
                    'Pacific/Noumea',
                    'Pacific/Pago_Pago',
                    'Pacific/Palau',
                    'Pacific/Pitcairn',
                    'Pacific/Ponape',
                    'Pacific/Port_Moresby',
                    'Pacific/Rarotonga',
                    'Pacific/Saipan',
                    'Pacific/Samoa',
                    'Pacific/Tahiti',
                    'Pacific/Tarawa',
                    'Pacific/Tongatapu',
                    'Pacific/Truk',
                    'Pacific/Wake',
                    'Pacific/Wallis',
                    'Pacific/Yap',
                ),
                'Deprecated' => array(
                    'Brazil/Acre',
                    'Brazil/DeNoronha',
                    'Brazil/East',
                    'Brazil/West',
                    'Canada/Atlantic',
                    'Canada/Central',
                    'Canada/East-Saskatchewan',
                    'Canada/Eastern  Canada/Mountain',
                    'Canada/Newfoundland',
                    'Canada/Pacific',
                    'Canada/Saskatchewan',
                    'Canada/Yukon',
                    'CET',
                    'Chile/Continental',
                    'Chile/EasterIsland',
                    'CST6CDT',
                    'Cuba',
                    'EET',
                    'Egypt',
                    'Eire',
                    'EST',
                    'EST5EDT',
                    'Etc/GMT',
                    'Etc/GMT+0',
                    'Etc/GMT+1',
                    'Etc/GMT+10',
                    'Etc/GMT+11',
                    'Etc/GMT+12',
                    'Etc/GMT+2',
                    'Etc/GMT+3',
                    'Etc/GMT+4',
                    'Etc/GMT+5',
                    'Etc/GMT+6',
                    'Etc/GMT+7',
                    'Etc/GMT+8',
                    'Etc/GMT+9',
                    'Etc/GMT-0',
                    'Etc/GMT-1',
                    'Etc/GMT-10',
                    'Etc/GMT-11',
                    'Etc/GMT-12',
                    'Etc/GMT-13',
                    'Etc/GMT-14',
                    'Etc/GMT-2',
                    'Etc/GMT-3',
                    'Etc/GMT-4',
                    'Etc/GMT-5',
                    'Etc/GMT-6',
                    'Etc/GMT-7',
                    'Etc/GMT-8',
                    'Etc/GMT-9',
                    'Etc/GMT0',
                    'Etc/Greenwich',
                    'Etc/UCT',
                    'Etc/Universal',
                    'Etc/UTC',
                    'Etc/Zulu',
                    'Factory',
                    'GB',
                    'GB-Eire',
                    'GMT',
                    'GMT+0',
                    'GMT-0',
                    'GMT0',
                    'Greenwich',
                    'Hongkong',
                    'HST',
                    'Iceland',
                    'Iran',
                    'Israel',
                    'Jamaica',
                    'Japan',
                    'Kwajalein',
                    'Libya',
                    'MET',
                    'Mexico/BajaNorte',
                    'Mexico/BajaSur',
                    'Mexico/General',
                    'MST',
                    'MST7MDT',
                    'Navajo',
                    'NZ',
                    'NZ-CHAT',
                    'Poland',
                    'Portugal',
                    'PRC',
                    'PST8PDT',
                    'ROC',
                    'ROK',
                    'Singapore',
                    'Turkey',
                    'UCT',
                    'Universal',
                    'US/Alaska',
                    'US/Aleutian',
                    'US/Arizona',
                    'US/Central',
                    'US/East-Indiana',
                    'US/Eastern',
                    'US/Hawaii',
                    'US/Indiana-Starke',
                    'US/Michigan',
                    'US/Mountain',
                    'US/Pacific',
                    'US/Pacific-New',
                    'US/Samoa',
                    'UTC',
                    'W-SU',
                    'WET',
                    'Zulu',
                )
            );
            if (isset($strict) && true == $strict) {
                unset($timezones['Deprecated']);
            }
            return $timezones;
        }
    }

}