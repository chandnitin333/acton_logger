<?php 
namespace Jet\JetStream;
// use GuzzleHttp\Psr7\Request;
use Throwable;
use Illuminate\Support\Facades\Session;
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
    
    public function __construct()
    {
         
    }

    public static function userActivity($subject):void{

        try {
            $log = [];
            $log['subject']     = $subject;
            $log['url']         = url()->current();            
            $log['ip']          = request()->ip();
            $log['agent']       = request()->userAgent();
            $log['user_id']     = auth()->check() ? auth()->user()->id : 1;
            $log['timestamp']   = date('Y-m-d H:i:s.u', time());   
            
            if(auth()->check()){
               $userId =  auth()->user()->id  ?? auth()->user()->_id ;
               $userName =  auth()->user()->username  ?? '' ; 
            }else{
                $userDetails = Session::get('user_details');
                $userName =  $userDetails['first_name']?? '' . ' ' . $userDetails['last_name'] ?? '';
                $userId      = $userDetails['_id'] ? $userDetails['_id'] : $userDetails['id'] ?? '';
            }
            $userName ='';
            $userId='';
            $fileName = '../storage/logs/' . gethostname() . '-UserActivity-' . date('Y-m-d') . '.log';

            $loggerLine = [date('Y-m-d H:i:s.u', time())] . '-' . request()->ip() . '-' . $subject . '' . $userId . ' ' . $userName;

            file_put_contents($fileName, $loggerLine .PHP_EOL, FILE_APPEND);
            
        } catch (Throwable $th) {
            throw $th;
        }
       
    }

   


}
