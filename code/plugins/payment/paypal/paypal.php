<?php
/**
 * Conferenceplus
 *
 * @package    Conferenceplus
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  2014 JandBeyond
 * @license    GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

/**
 * Conferenceplus User plugin
 *
 * @package     CONFERENCEPLUS
 * @since       1.0
 */
class PlgPaymentPaypal extends JPlugin
{
	/**
	 * Database object
	 *
	 * @var    JDatabaseDriver
	 * @since  3.2
	 */
	protected $db;

	private $errorLog = array();

	private $paymentmethod = 'paypal';

	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);


	}

	/**
	 * checks if the configuration is valid, we need a merchant id at least
	 *
	 * @return bool
	 */
	public function isConfigured()
	{
		return ! empty($this->getMerchantID());
	}

	/**
	 * getter for the error log
	 *
	 * @param $paymentmethod
	 *
	 * @return array
	 */
	public function onErrors($paymentmethod)
	{
		if (strtolower($paymentmethod) != $this->paymentmethod)
		{
			return false;
		}

		return $this->errorLog;
	}

	/**
	 * renders the payment form
	 *
	 * @param array $params
	 *
	 * @return string
	 */
	public function onPaymentGetForm($params = array())
	{
		if ( ! $this->isConfigured())
		{
			return '';
		}

		$baseLayoutPath = __DIR__ . '/layouts';
		$displayData    = new stdClass;

		$displayData->title       = 'Paypal';
		$displayData->url         = $this->getPaymentURL();
		$displayData->cmd         = '_xclick';
		$displayData->merchant    = $this->getMerchantID();
		$displayData->success     = '';
		$displayData->cancel      = '';
		$displayData->postback    = $this->getPostbackURL();
		$displayData->custom      = "";
		$displayData->item_number = "";
		$displayData->item_name   = "";
		$displayData->currency    = "";
		$displayData->firstname   = "";
		$displayData->lastname    = "";
		$displayData->email       = "";
		$displayData->net_amount  = 0;
		$displayData->tax_amount  = 0;

		foreach($params as $key => $value)
		{
			$displayData->$key = $value;
		}

		return JLayoutHelper::render('form', $displayData, $baseLayoutPath);
	}

	public function onPaymentCallback($paymentmethod, $data, $params)
	{
		if ( ! $this->isConfigured())
		{
			return false;
		}

		if (strtolower($paymentmethod) != $this->paymentmethod)
		{
			return false;
		}

		// Check IPN data for validity (i.e. protect against fraud attempt)
		$isValid = $this->isValidIPN($data);

		if( ! $isValid)
		{
			$this->errorLog[] = 'PayPal reports transaction as invalid';

			return false;
		}

		// Check that receiver_email / receiver_id is what the site owner has configured
		$receiver_email = $data['receiver_email'];
		$receiver_id = $data['receiver_id'];
		$valid_id = $this->getMerchantID();
		$isValid =
			($receiver_email == $valid_id)
			|| (strtolower($receiver_email) == strtolower($receiver_email))
			|| ($receiver_id == $valid_id)
			|| (strtolower($receiver_id) == strtolower($receiver_id));

		if( ! $isValid)
		{
			$this->errorLog[] = 'Merchant ID does not match receiver_email or receiver_id';

			return false;
		}

		// Check that mc_currency is correct
		$mc_currency = strtoupper($data['mc_currency']);
		$currency    = strtoupper($params['currency']);
		$isValid     = $mc_currency == $currency;

		if( ! $isValid)
		{
			$this->errorLog[] = "Invalid currency; expected $currency, got $mc_currency";

			return false;
		}

		$isPartialRefund = false;

		// Check that mc_gross is correct
		$mc_gross = floatval($data['mc_gross']);
		$gross    = $params['net_amount'];

		if($mc_gross > 0)
		{
			// A positive value means "payment". The prices MUST match!
			// Important: NEVER, EVER compare two floating point values for equality.
			$isValid = ($gross - $mc_gross) < 0.01;
		}
		else
		{
			// $mc_gross is negative
			$isPartialRefund = ($gross + $mc_gross) > 0.01;
		}

		if(!$isValid)
		{
			$this->errorLog[] = 'Paid amount does not match the subscription amount';

			return false;
		}

		$newState = $this->getNewState($data['payment_status'], $isPartialRefund);

		return json_encode(['processkey' => $data['txn_id'],'state' => $newState]);
	}


	private function getNewState($paymentStatus, $isPartialRefund=false)
	{
		// Check the payment_status
		switch($paymentStatus)
		{
			case 'Canceled_Reversal':
			case 'Completed':
				$newStatus = 'C';
				break;

			case 'Created':
			case 'Pending':
			case 'Processed':
				$newStatus = 'P';
				break;

			case 'Denied':
			case 'Expired':
			case 'Failed':
			case 'Refunded':
			case 'Reversed':
			case 'Voided':
			default:
				$newStatus = 'X';

				// Partial refunds can only by issued by the merchant. In that case,
				// we don't want the subscription to be cancelled. We have to let the
				// merchant adjust its parameters if needed.
				if ($isPartialRefund)
				{
					$newStatus = 'C';
				}
				break;
		}

		return $newStatus;
	}


	/**
	 * Gets the form action URL for the payment
	 */
	private function getPaymentURL()
	{
		return 'https://' . $this->getHostname() . '/cgi-bin/webscr';
	}

	/**
	 * Gets the form action URL for the payment
	 */
	private function getHostname()
	{
		if ($this->isSandboxMode())
		{
			return 'www.sandbox.paypal.com';
		}

		return 'www.paypal.com';
	}


	/**
	 * Gets the PayPal Merchant ID (usually the email address)
	 */
	private function getMerchantID()
	{
		if ($this->isSandboxMode())
		{
			return $this->params->get('sandbox_merchant', '');
		}

		return $this->params->get('merchant', '');
	}

	/**
	 * Creates the callback URL based on the plugins configuration.
	 */
	private function getPostbackURL() {

		$url = JURI::base().'index.php?option=com_conferenceplus&view=callback&type=payment&paymentmethod=paypal';

		return $url;
	}

	/**
	 * Validates the incoming data against PayPal's IPN to make sure this is not a
	 * fraudelent request.
	 */
	private function isValidIPN(&$data)
	{
		$hostname = $this->getHostname();

		$url = 'ssl://'.$hostname;
		$port = 443;

		$req = 'cmd=_notify-validate';
		foreach($data as $key => $value)
		{
			$value = urlencode($value);
			$req .= "&$key=$value";
		}
		$header = '';
		$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
		$header .= "Host: $hostname:$port\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n";
		$header .= "Connection: Close\r\n\r\n";

		$fp = fsockopen ($url, $port, $errno, $errstr, 30);

		if (!$fp)
		{
			// HTTP ERROR
			$this->errorLog[] = 'Could not open SSL connection to ' . $hostname . ':' . $port;
			return false;
		}

		fputs ($fp, $header . $req);

		while (!feof($fp))
		{
			$res = fgets ($fp, 1024);

			if (stristr($res, "VERIFIED"))
			{
				return true;
			}

			if (stristr($res, "INVALID"))
			{
				$this->errorLog[] = 'Connected to ' . $hostname . ':' . $port . '; returned data was "INVALID"';
				return false;
			}
		}

		fclose ($fp);
	}

	/**
	 * Checks if we are operating in sandbox mode
	 *
	 * @return bool
	 */
	private function isSandboxMode()
	{
		return $this->params->get('sandbox', 0) == 1;
	}
}
