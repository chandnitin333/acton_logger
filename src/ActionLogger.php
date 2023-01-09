<?php 
namespace Jet\JetStream;
// use GuzzleHttp\Psr7\Request;
use Throwable;
use Illuminate\Http\Request;
final class ActionLogger{
    /**
     * ActionLogger Class
     * @libary Actionlogger
     * @author Nitin Chandekar <nitin.chandekar@jetsynthesys.com>
     * Summary of $loggerParams
     * @since            Jan 06, 2023
     * @copyright        2023 Jetsynthesys Pvt Ltd.
     * @version          v1.0.0     
     */
    protected $loggerParams = [];
    
    public function __construct(array $params)
    {
        $this->loggerParams = $params;        
    }

    public static function userActivity($subject):void{

        try {
           
            $log = [];
            $log['subject'] = $subject;
            $log['url'] = Request::fullUrl();
            $log['method'] = Request::method();
            $log['ip'] = Request::ip();
            $log['agent'] = Request::header('user-agent');
            $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
            // $log  = "----------Uer Activity---------------".PHP_EOL;
            $fileName = '../storage/logs/' . gethostname() . '-User-' . date('Y-m-d') . '.log';
            file_put_contents($fileName, json_encode($log).PHP_EOL, FILE_APPEND);
        } catch (Throwable $th) {
            throw $th;
        }
       
    }

    function myErrorLog($message = 'no message') {
        $date = date('Y-m-d H:i:s');
        $fp   = fopen('/var/log/moodle-errors.log', 'a');
        if (!$fp) {
             throw new \Exception("Could not open log file! Permission error?");
        }
        fwrite($fp, $date . ' ' . $message . "\n");
        fclose($fp);
    }
    
    

}
