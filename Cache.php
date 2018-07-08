<?php
namespace BoostPHP\Cache\AutoMode{
    require_once __DIR__ . 'BoostPHP.internal.php';
    class AutoMode{
    
        public static $m_CacheInfos = array(); //it will be set into array(array("cachefullpath",needUpdate T/F), array(...), array(...))

        /**
         * Start automode caching block
         * @param int cacheAvailableDuration - how long should your cache file last?
         * @param string cacheFolder - where should we put your file(please put a / or \\ in the end)?
         * @param bool differentForPostData - should the post data differ the cache result?
         * @access public
         * @return bool do I still need to execute my code?
         */
        public static function cacheStart(int $cacheAvailableDuration, string $cacheFolder = "", bool $differentForPostData = true) : bool{
            ob_start();
            $cacheFileName = "";
            if(differentForPostData){
                $cacheFileName = md5($_SERVER['REQUEST_URI']) . md5(file_get_contents("php://input")) . '.html';//php://input is the POST data
            }else{
                $cacheFileName = md5($_SERVER['REQUEST_URI']) . '.html';
            }
            $cacheFullPath = $cacheFolder . $cacheFileName;
            if(file_exists($cacheFullPath)){
                if(time()-filemtime($cacheFullPath) < $cacheAvailableDuration){
                    \BoostPHP::output(file_get_contents($cacheFullPath));
                    AudoMode::$m_CacheInfos[count(AudoMode::$m_CacheInfos)] = array($cacheFullPath,false);
                    return false;
                }
            }
            AudoMode::$m_CacheInfos[count(AudoMode::$m_CacheInfos)] = array($cacheFullPath, true);
            return true;
        }

        /**
         * End the cache Block
         * @access public
         * @return void
         */
        public static function cacheEnd() : void{
            $cacheCount = count(AudoMode::$m_CacheInfos);
            $blockInfo = AudoMode::$m_CacheInfos[$cacheCount-1];
            if($blockInfo[1]){
                $fp = fopen($blockInfo[0],'w');
                fwrite($fp,ob_get_contents());
                fclose($fp);
            }
            ob_end_flush();
            unset(AudoMode::$m_CacheInfos[$cacheCount-1]); //delete the cache block info
        }
    }
    class ManualMode{
        public static $m_CacheInfos = array(); //it will be set into array(array("cachefullpath",needUpdate T/F), array(...), array(...))
        
        /**
         * Start manualmode caching block
         * @param int cacheAvailableDuration - how long should your cache file last?
         * @param string cacheFullPath - where should we put your file?
         * @access public
         * @return bool do I still need to execute my code?
         */
        public static function cacheStart(int $cacheAvailableDuration, string $cacheFullPath) : bool{
            ob_start();
            if(file_exists($cacheFullPath)){
                if(time()-filemtime($cacheFullPath) < $cacheAvailableDuration){
                    \BoostPHP::output(file_get_contents($cacheFullPath));
                    AudoMode::$m_CacheInfos[count(AudoMode::$m_CacheInfos)] = array($cacheFullPath,false);
                    return false;
                }
            }
            AudoMode::$m_CacheInfos[count(AudoMode::$m_CacheInfos)] = array($cacheFullPath, true);
            return true;
        }

        /**
         * End the cache Block
         * @access public
         * @return void
         */
        public static function cacheEnd() : void{
            $cacheCount = count(AudoMode::$m_CacheInfos);
            $blockInfo = AudoMode::$m_CacheInfos[$cacheCount-1];
            if($blockInfo[1]){
                $fp = fopen($blockInfo[0],'w');
                fwrite($fp,ob_get_contents());
                fclose($fp);
            }
            ob_end_flush();
            unset(AudoMode::$m_CacheInfos[$cacheCount-1]); //delete the cache block info
        }
    }   
}