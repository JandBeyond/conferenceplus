<?php
/**
 * conferenceplus
 * @author Robert Deutz <rdeutz@googlemail.com>
 * @package conferenceplus
 **/

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * field type
 *
 * @package  conferenceplus
 * @since    1.0.0
 */
class ConferenceplusFormFieldCountry extends JFormFieldList
{
	/**
	 * The form field type.
	 */
	protected $type = 'Country';

	/**
	 * Method to get the field options.
	 *
	 * @return array
	 */
	public function getOptions()
	{
		$options = array();

		$options[] = JHtml::_('select.option', '', '- Select -');
		$options[] = JHtml::_('select.option', 4,'Afghanistan');
		$options[] = JHtml::_('select.option', 248,'Åland Islands');
		$options[] = JHtml::_('select.option', 8,'Albania');
		$options[] = JHtml::_('select.option', 12,'Algeria');
		$options[] = JHtml::_('select.option', 16,'American Samoa');
		$options[] = JHtml::_('select.option', 20,'Andorra');
		$options[] = JHtml::_('select.option', 24,'Angola');
		$options[] = JHtml::_('select.option', 660,'Anguilla');
		$options[] = JHtml::_('select.option', 10,'Antarctica');
		$options[] = JHtml::_('select.option', 28,'Antigua and Barbuda');
		$options[] = JHtml::_('select.option', 32,'Argentina');
		$options[] = JHtml::_('select.option', 51,'Armenia');
		$options[] = JHtml::_('select.option', 533,'Aruba');
		$options[] = JHtml::_('select.option', 36,'Australia');
		$options[] = JHtml::_('select.option', 40,'Austria');
		$options[] = JHtml::_('select.option', 31,'Azerbaijan');
		$options[] = JHtml::_('select.option', 44,'Bahamas');
		$options[] = JHtml::_('select.option', 48,'Bahrain');
		$options[] = JHtml::_('select.option', 50,'Bangladesh');
		$options[] = JHtml::_('select.option', 52,'Barbados');
		$options[] = JHtml::_('select.option', 112,'Belarus');
		$options[] = JHtml::_('select.option', 56,'Belgium');
		$options[] = JHtml::_('select.option', 84,'Belize');
		$options[] = JHtml::_('select.option', 204,'Benin');
		$options[] = JHtml::_('select.option', 60,'Bermuda');
		$options[] = JHtml::_('select.option', 64,'Bhutan');
		$options[] = JHtml::_('select.option', 68,'Bolivia, Plurinational State of');
		$options[] = JHtml::_('select.option', 535,'Bonaire, Sint Eustatius and Saba');
		$options[] = JHtml::_('select.option', 70,'Bosnia and Herzegovina');
		$options[] = JHtml::_('select.option', 72,'Botswana');
		$options[] = JHtml::_('select.option', 74,'Bouvet Island');
		$options[] = JHtml::_('select.option', 76,'Brazil');
		$options[] = JHtml::_('select.option', 86,'British Indian Ocean Territory');
		$options[] = JHtml::_('select.option', 96,'Brunei Darussalam');
		$options[] = JHtml::_('select.option', 100,'Bulgaria');
		$options[] = JHtml::_('select.option', 854,'Burkina Faso');
		$options[] = JHtml::_('select.option', 108,'Burundi');
		$options[] = JHtml::_('select.option', 116,'Cambodia');
		$options[] = JHtml::_('select.option', 120,'Cameroon');
		$options[] = JHtml::_('select.option', 124,'Canada');
		$options[] = JHtml::_('select.option', 132,'Cape Verde');
		$options[] = JHtml::_('select.option', 136,'Cayman Islands');
		$options[] = JHtml::_('select.option', 140,'Central African Republic');
		$options[] = JHtml::_('select.option', 148,'Chad');
		$options[] = JHtml::_('select.option', 152,'Chile');
		$options[] = JHtml::_('select.option', 156,'China');
		$options[] = JHtml::_('select.option', 162,'Christmas Island');
		$options[] = JHtml::_('select.option', 166,'Cocos (Keeling) Islands');
		$options[] = JHtml::_('select.option', 170,'Colombia');
		$options[] = JHtml::_('select.option', 174,'Comoros');
		$options[] = JHtml::_('select.option', 178,'Congo');
		$options[] = JHtml::_('select.option', 180,'Congo, the Democratic Republic of the');
		$options[] = JHtml::_('select.option', 184,'Cook Islands');
		$options[] = JHtml::_('select.option', 188,'Costa Rica');
		$options[] = JHtml::_('select.option', 384,"Côte d'Ivoire");
		$options[] = JHtml::_('select.option', 191,'Croatia');
		$options[] = JHtml::_('select.option', 192,'Cuba');
		$options[] = JHtml::_('select.option', 531,'Curaçao');
		$options[] = JHtml::_('select.option', 196,'Cyprus');
		$options[] = JHtml::_('select.option', 203,'Czech Republic');
		$options[] = JHtml::_('select.option', 208,'Denmark');
		$options[] = JHtml::_('select.option', 262,'Djibouti');
		$options[] = JHtml::_('select.option', 212,'Dominica');
		$options[] = JHtml::_('select.option', 214,'Dominican Republic');
		$options[] = JHtml::_('select.option', 218,'Ecuador');
		$options[] = JHtml::_('select.option', 818,'Egypt');
		$options[] = JHtml::_('select.option', 222,'El Salvador');
		$options[] = JHtml::_('select.option', 226,'Equatorial Guinea');
		$options[] = JHtml::_('select.option', 232,'Eritrea');
		$options[] = JHtml::_('select.option', 233,'Estonia');
		$options[] = JHtml::_('select.option', 231,'Ethiopia');
		$options[] = JHtml::_('select.option', 238,'Falkland Islands (Malvinas)');
		$options[] = JHtml::_('select.option', 234,'Faroe Islands');
		$options[] = JHtml::_('select.option', 242,'Fiji');
		$options[] = JHtml::_('select.option', 246,'Finland');
		$options[] = JHtml::_('select.option', 250,'France');
		$options[] = JHtml::_('select.option', 254,'French Guiana');
		$options[] = JHtml::_('select.option', 258,'French Polynesia');
		$options[] = JHtml::_('select.option', 260,'French Southern Territories');
		$options[] = JHtml::_('select.option', 266,'Gabon');
		$options[] = JHtml::_('select.option', 270,'Gambia');
		$options[] = JHtml::_('select.option', 268,'Georgia');
		$options[] = JHtml::_('select.option', 276,'Germany');
		$options[] = JHtml::_('select.option', 288,'Ghana');
		$options[] = JHtml::_('select.option', 292,'Gibraltar');
		$options[] = JHtml::_('select.option', 300,'Greece');
		$options[] = JHtml::_('select.option', 304,'Greenland');
		$options[] = JHtml::_('select.option', 308,'Grenada');
		$options[] = JHtml::_('select.option', 312,'Guadeloupe');
		$options[] = JHtml::_('select.option', 316,'Guam');
		$options[] = JHtml::_('select.option', 320,'Guatemala');
		$options[] = JHtml::_('select.option', 831,'Guernsey');
		$options[] = JHtml::_('select.option', 324,'Guinea');
		$options[] = JHtml::_('select.option', 624,'Guinea-Bissau');
		$options[] = JHtml::_('select.option', 328,'Guyana');
		$options[] = JHtml::_('select.option', 332,'Haiti');
		$options[] = JHtml::_('select.option', 334,'Heard Island and McDonald Islands');
		$options[] = JHtml::_('select.option', 336,'Holy See (Vatican City State)');
		$options[] = JHtml::_('select.option', 340,'Honduras');
		$options[] = JHtml::_('select.option', 344,'Hong Kong');
		$options[] = JHtml::_('select.option', 348,'Hungary');
		$options[] = JHtml::_('select.option', 352,'Iceland');
		$options[] = JHtml::_('select.option', 356,'India');
		$options[] = JHtml::_('select.option', 360,'Indonesia');
		$options[] = JHtml::_('select.option', 364,'Iran, Islamic Republic of');
		$options[] = JHtml::_('select.option', 368,'Iraq');
		$options[] = JHtml::_('select.option', 372,'Ireland');
		$options[] = JHtml::_('select.option', 833,'Isle of Man');
		$options[] = JHtml::_('select.option', 376,'Israel');
		$options[] = JHtml::_('select.option', 380,'Italy');
		$options[] = JHtml::_('select.option', 388,'Jamaica');
		$options[] = JHtml::_('select.option', 392,'Japan');
		$options[] = JHtml::_('select.option', 832,'Jersey');
		$options[] = JHtml::_('select.option', 400,'Jordan');
		$options[] = JHtml::_('select.option', 398,'Kazakhstan');
		$options[] = JHtml::_('select.option', 404,'Kenya');
		$options[] = JHtml::_('select.option', 296,'Kiribati');
		$options[] = JHtml::_('select.option', 408,"Korea, Democratic People's Republic of");
		$options[] = JHtml::_('select.option', 410,'Korea, Republic of');
		$options[] = JHtml::_('select.option', 414,'Kuwait');
		$options[] = JHtml::_('select.option', 417,'Kyrgyzstan');
		$options[] = JHtml::_('select.option', 418,"Lao People's Democratic Republic");
		$options[] = JHtml::_('select.option', 428,'Latvia');
		$options[] = JHtml::_('select.option', 422,'Lebanon');
		$options[] = JHtml::_('select.option', 426,'Lesotho');
		$options[] = JHtml::_('select.option', 430,'Liberia');
		$options[] = JHtml::_('select.option', 434,'Libya');
		$options[] = JHtml::_('select.option', 438,'Liechtenstein');
		$options[] = JHtml::_('select.option', 440,'Lithuania');
		$options[] = JHtml::_('select.option', 442,'Luxembourg');
		$options[] = JHtml::_('select.option', 446,'Macao');
		$options[] = JHtml::_('select.option', 807,'Macedonia, the former Yugoslav Republic of');
		$options[] = JHtml::_('select.option', 450,'Madagascar');
		$options[] = JHtml::_('select.option', 454,'Malawi');
		$options[] = JHtml::_('select.option', 458,'Malaysia');
		$options[] = JHtml::_('select.option', 462,'Maldives');
		$options[] = JHtml::_('select.option', 466,'Mali');
		$options[] = JHtml::_('select.option', 470,'Malta');
		$options[] = JHtml::_('select.option', 584,'Marshall Islands');
		$options[] = JHtml::_('select.option', 474,'Martinique');
		$options[] = JHtml::_('select.option', 478,'Mauritania');
		$options[] = JHtml::_('select.option', 480,'Mauritius');
		$options[] = JHtml::_('select.option', 175,'Mayotte');
		$options[] = JHtml::_('select.option', 484,'Mexico');
		$options[] = JHtml::_('select.option', 583,'Micronesia, Federated States of');
		$options[] = JHtml::_('select.option', 498,'Moldova, Republic of');
		$options[] = JHtml::_('select.option', 492,'Monaco');
		$options[] = JHtml::_('select.option', 496,'Mongolia');
		$options[] = JHtml::_('select.option', 499,'Montenegro');
		$options[] = JHtml::_('select.option', 500,'Montserrat');
		$options[] = JHtml::_('select.option', 504,'Morocco');
		$options[] = JHtml::_('select.option', 508,'Mozambique');
		$options[] = JHtml::_('select.option', 104,'Myanmar');
		$options[] = JHtml::_('select.option', 516,'Namibia');
		$options[] = JHtml::_('select.option', 520,'Nauru');
		$options[] = JHtml::_('select.option', 524,'Nepal');
		$options[] = JHtml::_('select.option', 528,'Netherlands');
		$options[] = JHtml::_('select.option', 540,'New Caledonia');
		$options[] = JHtml::_('select.option', 554,'New Zealand');
		$options[] = JHtml::_('select.option', 558,'Nicaragua');
		$options[] = JHtml::_('select.option', 562,'Niger');
		$options[] = JHtml::_('select.option', 566,'Nigeria');
		$options[] = JHtml::_('select.option', 570,'Niue');
		$options[] = JHtml::_('select.option', 574,'Norfolk Island');
		$options[] = JHtml::_('select.option', 580,'Northern Mariana Islands');
		$options[] = JHtml::_('select.option', 578,'Norway');
		$options[] = JHtml::_('select.option', 512,'Oman');
		$options[] = JHtml::_('select.option', 586,'Pakistan');
		$options[] = JHtml::_('select.option', 585,'Palau');
		$options[] = JHtml::_('select.option', 275,'Palestinian Territory, Occupied');
		$options[] = JHtml::_('select.option', 591,'Panama');
		$options[] = JHtml::_('select.option', 598,'Papua New Guinea');
		$options[] = JHtml::_('select.option', 600,'Paraguay');
		$options[] = JHtml::_('select.option', 604,'Peru');
		$options[] = JHtml::_('select.option', 608,'Philippines');
		$options[] = JHtml::_('select.option', 612,'Pitcairn');
		$options[] = JHtml::_('select.option', 616,'Poland');
		$options[] = JHtml::_('select.option', 620,'Portugal');
		$options[] = JHtml::_('select.option', 630,'Puerto Rico');
		$options[] = JHtml::_('select.option', 634,'Qatar');
		$options[] = JHtml::_('select.option', 638,'Réunion');
		$options[] = JHtml::_('select.option', 642,'Romania');
		$options[] = JHtml::_('select.option', 643,'Russian Federation');
		$options[] = JHtml::_('select.option', 646,'Rwanda');
		$options[] = JHtml::_('select.option', 652,'Saint Barthélemy');
		$options[] = JHtml::_('select.option', 654,'Saint Helena, Ascension and Tristan da Cunha');
		$options[] = JHtml::_('select.option', 659,'Saint Kitts and Nevis');
		$options[] = JHtml::_('select.option', 662,'Saint Lucia');
		$options[] = JHtml::_('select.option', 663,'Saint Martin (French part)');
		$options[] = JHtml::_('select.option', 666,'Saint Pierre and Miquelon');
		$options[] = JHtml::_('select.option', 670,'Saint Vincent and the Grenadines');
		$options[] = JHtml::_('select.option', 882,'Samoa');
		$options[] = JHtml::_('select.option', 674,'San Marino');
		$options[] = JHtml::_('select.option', 678,'Sao Tome and Principe');
		$options[] = JHtml::_('select.option', 682,'Saudi Arabia');
		$options[] = JHtml::_('select.option', 686,'Senegal');
		$options[] = JHtml::_('select.option', 688,'Serbia');
		$options[] = JHtml::_('select.option', 690,'Seychelles');
		$options[] = JHtml::_('select.option', 694,'Sierra Leone');
		$options[] = JHtml::_('select.option', 702,'Singapore');
		$options[] = JHtml::_('select.option', 534,'Sint Maarten (Dutch part)');
		$options[] = JHtml::_('select.option', 703,'Slovakia');
		$options[] = JHtml::_('select.option', 705,'Slovenia');
		$options[] = JHtml::_('select.option', 90,'Solomon Islands');
		$options[] = JHtml::_('select.option', 706,'Somalia');
		$options[] = JHtml::_('select.option', 710,'South Africa');
		$options[] = JHtml::_('select.option', 239,'South Georgia and the South Sandwich Islands');
		$options[] = JHtml::_('select.option', 728,'South Sudan');
		$options[] = JHtml::_('select.option', 724,'Spain');
		$options[] = JHtml::_('select.option', 144,'Sri Lanka');
		$options[] = JHtml::_('select.option', 729,'Sudan');
		$options[] = JHtml::_('select.option', 740,'Suriname');
		$options[] = JHtml::_('select.option', 744,'Svalbard and Jan Mayen');
		$options[] = JHtml::_('select.option', 748,'Swaziland');
		$options[] = JHtml::_('select.option', 752,'Sweden');
		$options[] = JHtml::_('select.option', 756,'Switzerland');
		$options[] = JHtml::_('select.option', 760,'Syrian Arab Republic');
		$options[] = JHtml::_('select.option', 158,'Taiwan, Province of China');
		$options[] = JHtml::_('select.option', 762,'Tajikistan');
		$options[] = JHtml::_('select.option', 834,'Tanzania, United Republic of');
		$options[] = JHtml::_('select.option', 764,'Thailand');
		$options[] = JHtml::_('select.option', 626,'Timor-Leste');
		$options[] = JHtml::_('select.option', 768,'Togo');
		$options[] = JHtml::_('select.option', 772,'Tokelau');
		$options[] = JHtml::_('select.option', 776,'Tonga');
		$options[] = JHtml::_('select.option', 780,'Trinidad and Tobago');
		$options[] = JHtml::_('select.option', 788,'Tunisia');
		$options[] = JHtml::_('select.option', 792,'Turkey');
		$options[] = JHtml::_('select.option', 795,'Turkmenistan');
		$options[] = JHtml::_('select.option', 796,'Turks and Caicos Islands');
		$options[] = JHtml::_('select.option', 798,'Tuvalu');
		$options[] = JHtml::_('select.option', 800,'Uganda');
		$options[] = JHtml::_('select.option', 804,'Ukraine');
		$options[] = JHtml::_('select.option', 784,'United Arab Emirates');
		$options[] = JHtml::_('select.option', 826,'United Kingdom');
		$options[] = JHtml::_('select.option', 840,'United States');
		$options[] = JHtml::_('select.option', 581,'United States Minor Outlying Islands');
		$options[] = JHtml::_('select.option', 858,'Uruguay');
		$options[] = JHtml::_('select.option', 860,'Uzbekistan');
		$options[] = JHtml::_('select.option', 548,'Vanuatu');
		$options[] = JHtml::_('select.option', 862,'Venezuela, Bolivarian Republic of');
		$options[] = JHtml::_('select.option', 704,'Viet Nam');
		$options[] = JHtml::_('select.option', 92,'Virgin Islands, British');
		$options[] = JHtml::_('select.option', 850,'Virgin Islands, U.S.');
		$options[] = JHtml::_('select.option', 876,'Wallis and Futuna');
		$options[] = JHtml::_('select.option', 732,'Western Sahara');
		$options[] = JHtml::_('select.option', 887,'Yemen');
		$options[] = JHtml::_('select.option', 894,'Zambia');
		$options[] = JHtml::_('select.option', 716,'Zimbabwe');

		return $options;
	}
}
