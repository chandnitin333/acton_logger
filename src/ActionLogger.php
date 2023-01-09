<?php 
namespace Jet\JetStream;
// use GuzzleHttp\Psr7\Request;
use DateTime;
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

    public static function userActivity($subject,$type ='file'){

        try {
          
            $userName ='';
            $userId='';
            $now = DateTime::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''));
            $timestamp =  $now->format("m-d-Y H:i:s.u");
            if(auth()->check()){

               $userId =  auth()->user()->id  ?? auth()->user()->_id ;
               $userName =  auth()->user()->username  ?? '' ; 
            }else{
                $userDetails = Session::get('user_details') ?? [];
                
                if (!empty($userDetails)) {
                    $firstName  = $userDetails['first_name'] ?? '';
                    $lastName =  $userDetails['last_name'] ?? '';
                    $userName = $firstName . ' ' . $lastName;
                    $userId = $userDetails['_id'] ? $userDetails['_id'] : $userDetails['id'] ?? '';
                }
            }
            
            $fileName = '../storage/logs/' . gethostname() . '-UserActivity-' . date('Y-m-d') . '.log';

            $loggerLine = '['.$timestamp.']' . ' - ' . request()->ip() . ' - ' . $subject . ' ' . $userId . ' ' . $userName;
            
            file_put_contents($fileName, $loggerLine .PHP_EOL, FILE_APPEND);
            chmod($fileName, 0777);
            
        } catch (Throwable $th) {
           
             throw $th;
        }
       
    }

   


}
