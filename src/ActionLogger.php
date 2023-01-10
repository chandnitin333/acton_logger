<?php 
namespace Jet\JetStream;
use DateTime;
use Throwable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
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
    
    static protected $logTable = 'users_activity_logs';
    

    public static function userActivity($subject,$type ='file'){

        try {
            $type = (ACTIVITY_LOG_TYPE != '') ? ACTIVITY_LOG_TYPE : 'file';
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

            if (strtolower($type) == 'file') {
                $fileName = '../storage/logs/' . gethostname() . '-UserActivity-' . date('Y-m-d') . '.log';
                $loggerLine = '[' . $timestamp . ']' . ' - ' . request()->ip() . ' - ' .gethostname().' - '. $subject . ' ' . $userId . ' ' . $userName;
                file_put_contents($fileName, $loggerLine . PHP_EOL, FILE_APPEND);
                chmod($fileName, 0777);
            }else{
                
                DB::table(self::$logTable)->insert([
                    'user_name'    => $userName,
                    'user_id'   => $userId,
                    'ip' => request()->ip(),
                    'hostname' => gethostname(),
                    'subject'   => $subject,
                    'timesstamp'       => $timestamp
                ]);
            }
            
        } catch (Throwable $th) {
           
             throw $th;
        }
       
    }

   


}
