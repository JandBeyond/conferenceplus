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

namespace Conferenceplus\Task;

/**
 * Class TemplateTrait
 * @package  Conferenceplus\Task
 * @since   1.0
 */
trait TemplateTrait
{

    /*
    * holds the template
    */
    public $template = null;

    /**
     * get the mailtext for the email
     *
     * @param   array   $data   the data
     * @param   string  $field  the data
     *
     * @return string
     */
    protected function getTextFromTemplate($data, $field = 'title')
    {
        $text = '';
        $et   = $this->getTemplate();

        if ( ! empty($et))
        {
            $text = $this->replacePlaceHolders($et->$field, $data);
        }

        return $text;
    }

    /**
     * get the Template
     *
     * @param int $event_id
     *
     * @return mixed
     */
    protected function getTemplate($event_id = 0)
    {
        if (is_null($this->template))
        {
            $query = $this->db->getQuery(true);

            $query->select('*')
                ->from('#__conferenceplus_templates')
                ->where($this->db->qn('taskname') . ' =' . $this->db->q($this->taskname))
                ->where($this->db->qn('enabled') . ' = 1');

            if ($event_id != 0)
            {
                $query->where($this->db->qn('event_id') . ' =' . (int) $event_id);
            }

            $this->db->setQuery($query);
            $this->template = $this->db->loadObject();
        }

        return $this->template;
    }

    /**
     * replace tags with data within the text
     *
     * @param   string  $text  the text
     * @param   mixed   $data  the data
     *
     * @return  string
     */
    protected function replacePlaceHolders($text, $data)
    {
        foreach ($data as $placeHolder => $value)
        {
            if (is_string($value))
            {
                $text = str_replace('{' . $placeHolder . '}', $value, $text);
            }
        }

        return $text;
    }
}