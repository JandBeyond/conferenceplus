<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 **/


namespace Conferenceplus\Date;

trait Helper {
	/**
	 * checks if a Date is valid
	 *
	 * @param   string  $what  what date type
	 *
	 * @return  string         the date on sucess or false on fail
	 */
	public function checkDate($what='sdate')
	{
		$result	= trim($this->input->get($what));

		if ($result != '')
		{
			$datesplited = explode('.', $result);

			if (checkdate($datesplited[1], $datesplited[0], $datesplited[2]))
			{
				return $result;
			}
		}

		return false;
	}

	/**
	 * checks if a time is valid
	 *
	 * @param   string  $what  what date type
	 *
	 * @return  string         the time on sucess or false on fail
	 */
	public function checkTime($what='stime')
	{
		$result = false;
		$hh 	= trim($this->input->get($what . 'hh', '--'));
		$mm 	= trim($this->input->get($what . 'mm', '--'));

		if ($hh != '--' && $mm != '--')
		{
			$hint = (int) $hh;
			$mint = (int) $mm;

			if (($hint >= 0) && ($hint <= 23) && ($mint >= 0) && ($hint <= 59))
			{
				$hh = str_pad($hint, 2, "0", STR_PAD_LEFT);
				$mm = str_pad($mint, 2, "0", STR_PAD_LEFT);
				$result	= $hh . ':' . $mm;
			}
		}

		return $result;
	}

	/**
	 * format a date so that it can be used in queries
	 *
	 * @param   string  $date    the date
	 * @param   string  $format  the source format
	 *
	 * @return  string         the date or false
	 */
	public function formatDate($date, $format='auto')
	{
		if ($format == 'auto')
		{
			// Guessing the source format
			if (strpos($date, '.'))
			{
				// Guess it is DAY.MONTH.YEAR
				list($d, $m, $y) = explode('.', $date);

				return $y . '-' . $m . '-' . $d;
			}

			if (strpos($date, '-'))
			{
				// Guess it is YEAR-MONTH-DAY
				return $date;
			}
		}
		else
		{
			$ndate = date_create_from_format($format, $date);

			if ($ndate === false)
			{
				return false;
			}

			return date_format($ndate, 'Y-m-d');
		}

		return false;
	}

}