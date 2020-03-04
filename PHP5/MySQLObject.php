<?php
namespace BoostPHP{
    require_once __DIR__ . '/internal/BoostPHP.internal.php';
    require_once __DIR__ . '/MySQLFunction.php';
    class MySQLConn{
        private $MySQLiConn;
        private $isConnected = false;
        
        public $Host = "127.0.0.1";
        public $Port = 3306;
        public $Database = "";
        public $Username = "";
        public $Password = "";

        
        public function __construct(){
            $this->isConnected = false;
        }
        
        public function __destruct(){
            $this->disconnect();
        }

        public function connect(){
            if($this->isConnected){
                return;
            }
            $this->MySQLiConn = MySQL::connectDB($this->Username,$this->Password, $this->Database, $this->Host, $this->Port);
            $connectSuccess = $this->MySQLiConn ? true : false;
            $this->isConnected = $connectSuccess;
        }

        public function disconnect(){
            if(!($this->isConnected)){
                return;
            }
            MySQL::closeConn($this->MySQLiConn);
            $this->isConnected = false;
		}
		
		public function reconnect(){
            $this->disconnect();
            $this->connect();
		}
		
		public function isAlive() : bool{
            $keepAliveStatement = mysqli_ping($this->MySQLiConn);
            return $keepAliveStatement;
        }

        public function keepAlive() : void{
            $isAlive = $this->isAlive();
            if(!$isAlive){
                $this->reconnect();
            }
        }
        
        /**
		 * Query an SQL Statement
		 * returns false on failure
		 * @param string The statement you want to query
		 * @access public
		 * @return bool
		 */
		public function querySQL($SQLStatement) {
			if(!$this->isConnected){
                return false;
            }
            return MySQL::querySQL($this->MySQLiConn,$SQLStatement);
		}

		/**
		 * Select datas from the Database
		 * returns array that has a count of 0 on failure
		 * @param string The statement you want to query for selection, need to be prevented from SQL Injection
		 * @access public
		 * @return array
		 * @returnKey count[int] - how many results can be shown
		 * @returnKey result[array] - the result of the selection(only when count > 0)
		 */
		public function selectIntoArray_FromStatement($SelectStatement){
			if(!$this->isConnected){
                return array('count'=>0);
            }
            return MySQL::selectIntoArray_FromStatement($this->MySQLiConn,$SelectStatement);
		}
		
		/**
		 * Select datas from the Database
		 * returns an array that has a count of 0 on failure
		 * @param string Table name,  need to be prevented from SQL Injection
		 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
		 * @param array The array of keys to be the key for ordering
		 * @param int The limit number you want to select, -1 means to select all
		 * @param int the offset you want to start with, by default it is 0, which means from the start.
		 * @access public
		 * @return array
		 * @returnKey count[int] - how many results can be shown
		 * @returnKey result[array] - the result of the selection(only when count > 0)
		 */
		public function selectIntoArray_FromRequirements($Table, $SelectRequirement = array(), $OrderByArray = array(), $NumLimit = -1, $OffsetNum = 0){
			if(!$this->isConnected){
                return array('count'=>0);
            }
            return MySQL::selectIntoArray_FromRequirements($this->MySQLiConn,$Table,$SelectRequirement,$OrderByArray,$NumLimit,$OffsetNum);
		}

		/**
		 * Check data exists that fits requirements from the Database
		 * returns 0 on failure
		 * @param string The table you want to query for selection, need to be prevented from SQL Injection
		 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
		 * @access public
		 * @return int - how many results can be shown
		 */
		public function checkExist($Table,$SelectRequirement){
			if(!$this->isConnected){
                return 0;
            }
            return MySQL::checkExist($this->MySQLiConn,$Table,$SelectRequirement);
		}

		/**
		 * Insert data into the DB
		 * returns false on failure
		 * @param string The table you want to query for selection, need to be prevented from SQL Injection
		 * @param array The array that you want the insert value to be, should be like array(Key=>Value, Key1=>Value1)
		 * @access public
		 * @return bool - true if successful
		 */
		public function insertRow($Table, $InsertArray){
			if(!$this->isConnected){
                return false;
            }
            return MySQL::insertRow($this->MySQLiConn,$Table,$InsertArray);
		}
		
		/**
		 * Update the Table of the MYSQL DB
		 * returns false on failure
		 * @param string The table you want to query for selection, need to be prevented from SQL Injection
		 * @param array The array that you want to update your value, like array(Key=>Value, Key1=>Value1)
		 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
		 * @access public
		 * @return bool - if succeed, return true.
		 */
		public function updateRows($Table, $UpdateArray, $SelectRequirement){
			if(!$this->isConnected){
                return false;
            }
            return MySQL::updateRows($this->MySQLiConn,$Table,$UpdateArray,$SelectRequirement);
		}

		/**
		 * Delete Rows from MYSQL DB
		 * returns false on failure
		 * @param string The table you want to query for selection, need to be prevented from SQL Injection
		 * @param array The array that requirements should fit, should be like array(Key=>Value, Key1=>Value1)
		 * If the third param is empty, it will clear the entire table.
		 * @access public
		 * @return bool - if succeed, return true.
		 */
		public function deleteRows($Table, $SelectRequirement){
			if(!$this->isConnected){
                return false;
            }
            return MySQL::deleteRows($this->MySQLiConn,$Table,$$SelectRequirement);
		}
    }
}
?>