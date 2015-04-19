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

namespace Conferenceplus\Composer;

/**
 * Class Number
 * @package  Conferenceplus\Composer
 * @since   0.1
 */
class Number
{
    /**
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (array_key_exists('params', $config))
        {
            $this->params = $config['params'];
        }
        else
        {
            $this->params = \JComponentHelper::getParams('com_conferenceplus');
        }
    }

    /**
     * @param $fee
     *
     * @return string
     */
    public function money($fee)
    {
        $currency = explode('|', $this->params->get('currency'))[0];
        $numbers  = $this->params->get('numbers', 0);
        $decimals = 2;
        $output   = '';

        if (round($fee) == $fee)
        {
            $decimals = 0;
        }

        switch ($numbers)
        {
            default:
            case 0:
                $output .= number_format($fee, $decimals, ',', '.');
                $output .= ' ' . $currency;
                break;
            case 1:
                $output .= number_format($fee, $decimals, ',', '');
                $output .= ' ' . $currency;
                break;
            case 2:
                $output .= number_format($fee, $decimals, '.', ',');
                $output .= ' ' . $currency;
                break;
            case 3:
                $output .= number_format($fee, $decimals, '.', '');
                $output .= ' ' . $currency;
                break;
            case 4:
                $output .= $currency . ' ';
                $output .= number_format($fee, $decimals, ',', '.');
                break;
            case 5:
                $output .= $currency . ' ';
                $output .= number_format($fee, $decimals, ',', '');
                break;
            case 6:
                $output .= $currency . ' ';
                $output .= number_format($fee, $decimals, '.', ',');
                break;
            case 7:
                $output .= $currency . ' ';
                $output .= number_format($fee, $decimals, '.', '');
                break;
        }

        return $output;
    }
}
