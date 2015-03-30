<?php
 /**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2015 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/



$get = new FOFInput('GET');

$rawData = JRequest::get('GET', 2);


$rawData2 = $get->getArray();


echo 'hallo';