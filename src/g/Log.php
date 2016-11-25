<?php
namespace g;
class Log{
    protected static $LogDir = "logs";
    protected static $LogName = "g";
    public static $console = true;
    public static function debug($s){
        if(is_array($s)) $s = "array: ".json_encode($s);
        else if(is_object($s)) $s = "object: ".json_encode($s);
        $s = "DEBUG\t".self::get_caller_info().$s."\n";
        self::_putdata($s);

    }
    protected static function _putdata($s){
        if(self::$console)print($s);
        file_put_contents(self::$LogDir."/".self::$LogName."-".date("Y-m-d").'.log',$s,FILE_APPEND);
    }
    protected static function get_caller_info() {
        $c = '';
        $line = '';
        $file = '';
        $func = '';
        $class = '';
        $trace = debug_backtrace();
        /*if (isset($trace[2])) {
            $file = $trace[1]['file'];
            $func = $trace[2]['function'];
            $line = $trace[1]['line'];
            if ((substr($func, 0, 7) == 'include') || (substr($func, 0, 7) == 'require')) {
                $func = '';
            }
        }
        else if (isset($trace[1])) {
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
            $func = '';
        }*/
        if (isset($trace[2]['class'])) {
            $class = $trace[2]['class'];
            $func = $trace[2]['function'];
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
        } else if (isset($trace[2]['class'])) {
            $class = $trace[2]['class'];
            $func = $trace[2]['function'];
            $file = $trace[1]['file'];
            $line = $trace[1]['line'];
        }
        if ($file != '') $file = basename($file);
        $c = date("H:i:s")." File:".$file ;
        $c .= " Line:".$line;
        $c .= ($class != '') ? " " . $class . "->" : "";
        $c .= ($func != '') ? $func . "()" : "";
        $c .= "\t";
        return($c);
    }
};
?>
