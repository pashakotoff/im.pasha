<?php
//Namespace - имя папки с обратным слешем
namespace core\base\exceptions;

//Класс обработки исключений наследует базовый класс языка php
use core\base\controller\BaseMethods;

class RouteException extends \Exception
{
    use BaseMethods;
    protected $messages;

    public function __construct($message = "", $code = 0) {
        parent::__construct($message, $code);

        $this->messages = include 'messages.php';

        $error = $this->getMessage() ? $this->getMessage() : $this->messages[$this->getCode()];
        $error .= PHP_EOL . "file " . $this->getFile() . PHP_EOL . "In line " . $this->getLine() . PHP_EOL;

        if($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];

        $this->writeLog($error);

    }

}