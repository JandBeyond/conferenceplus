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

	public function prepareItemTimeFields(&$record)
	{
		$record->addKnownField('stimehh');
		$record->addKnownField('stimemm');
		$record->addKnownField('etimehh');
		$record->addKnownField('etimemm');

		if ($record->sdate == '0000-00-00')
		{
			$record->sdate = '';
		}

		if ($record->edate == '0000-00-00')
		{
			$record->edate = '';
		}

		if (strpos($record->sdate, '-') !== false)
		{
			list($y, $m, $d) = explode('-', $record->sdate);
			$record->sdate = $d . '.' . $m . '.' . $y;
		}

		if (strpos($record->edate, '-') !== false)
		{
			list($y, $m, $d) = explode('-', $record->edate);
			$record->edate = $d . '.' . $m . '.' . $y;
		}

		if ($record->stimeset == 1)
		{
			list($stimehh, $stimemm) = explode(":", $record->stime);
			$record->stimehh = (int) $stimehh;
			$record->stimemm = (int) $stimemm;
		}

		if ($record->etimeset == 1)
		{
			list($etimehh, $etimemm) = explode(":", $record->etime);
			$record->etimehh = (int) $etimehh;
			$record->etimemm = (int) $etimemm;
		}

	}

	/**
	 * it merges date and time fields and allows to separate between time not set and 00:00:00
	 *
	 * @param  array   $data                Form data
	 * @param  array   $dateFieldPrefixes   prefixes for the date/time fileds
	 * @param  array   $combinedDateFields  set these fields with the merged data
	 *
	 * @return bool    true on success, false otherwise
	 */
	public function manageDateFields(&$data, $dateFieldPrefixes, $combinedDateFields = [])
	{

		$dtFields = (array) $dateFieldPrefixes;

		$results = [];

		foreach ($dtFields as $dtf)
		{
			// Preset default values
			$data[$dtf . 'time']    = '00:00:00';
			$data[$dtf . 'timeset'] = '0';

			// Check time and date
			$result = $this->checkDate($dtf . 'date');

			if ($result !== false)
			{
				// format the date so that it is in the right format for saving in the DB
				$result = $this->formatDate($result);

				if ($result === false)
				{
					$this->setError('Date format not valid: ' . $dtf . 'date');

					return false;
				}

				$combined = $result;
				$data[$dtf . 'date'] = $result;
				$result              = $this->checkTime($dtf . 'time');

				if ($result !== false)
				{
					$data[$dtf . 'time']    = $result . ':00';
					$data[$dtf . 'timeset'] = 1;
					$combined = $combined . ' ' . $result . ':00';
				}
				$results[] = $combined;
			}
		}

		if ( ! empty($combinedDateFields) && ! empty($results))
		{
			foreach($combinedDateFields as $field)
			{
				if (! list(, $val) = each($results))
				{
					break;
				}

				$data[$field] = $val;
			}
		}

		return true;
	}

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
