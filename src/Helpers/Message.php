<?php namespace CI4Xpander_Dashboard\Helpers;

class Message
{
    /**
     * message type : info, danger, success, warning
     *
     * @var string
     */
    public $type = null;

    public $value = null;

    const INFO = 'info';
    const DANGER = 'danger';
    const SUCCESS = 'success';
    const WARNING = 'warning';

    public function __construct($type = null, $value = null)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function setType($type = null)
    {
        $this->type = $type;
        return $this;
    }

    public function setValue($value = null)
    {
        $this->value = $value;
        return $this;
    }

    public function render()
    {
        if (!is_null($this->type) && in_array($this->type, [
            self::INFO, self::DANGER, self::SUCCESS, self::WARNING
        ])) {
            $message = '<div class="alert alert-' . $this->type . ' alert-dismissable" role="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' . $this->value . '</div>';

            $this->type = null;
            $this->value = null;

            return $message;
        }

        $this->type = null;
        $this->value = null;

        return null;
    }

    public static function create($type = null, $value = null)
    {
        return new self($type, $value);
    }
}