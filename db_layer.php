<?php


class DB {
    
    function DB($host,$user,$pass,$database,$link = null) {
    
        $this->host = $host; 
        $this->db = $database;
        $this->user = $user; 
        $this->pass = $pass; 
        
        $this->last_query = '';
        $this->last_error = '';
        
        if($link != null) $this->link = $link;
        else {
          $this->link = @mysql_connect($this->host, $this->user, $this->pass);
          if(!$this->link) {
              die('Can\'t connect db!');
          }
          if(!@mysql_select_db($this->db, $this->link)){
              die('Can\'t select db');
          }

          @mysql_query('set names utf8', $this->link);
          @mysql_query('set character set utf8', $this->link);
        }
        
        return $this->link;


    }
    
    
    function query($query='')
    {
        if (!$query) return false;
        else {
        
			@mysql_select_db($this->db, $this->link);
			@mysql_query('set names utf8', $this->link);
			@mysql_query('set character set utf8', $this->link);
            
            $this->last_query = $query;
			
            $result = mysql_query($query, $this->link);
            
            if(!$result) {
                $this->last_error = mysql_error($this->link);
            }
            else {
                $this->last_error = '';
            }
            
            return $result;
        }
    }
    
    // returns an array of records
    function fetchArray($query='', $key='')
    {
		// key determines which query result column to use as key
		
        if ($result = $this->query($query)) {
            if (mysql_num_rows($result) > 0) {
                while ($arr = mysql_fetch_assoc($result)) {
				
					if($key != '') {
						$key_name = $arr[$key];
						
						unset($arr[$key]);
						
						$rows[$key_name] = $arr;
					}
					else {
						$rows[] = $arr;
					}
					
				}
				
                return $rows;
            }
            else return 0;
        }
        return false;
    }
    
    // returns a single record
    function fetchRow($query='')
    {	
        if ($row = $this->query($query)) {
            if (mysql_num_rows($row) > 0) {
                return mysql_fetch_assoc($row);
            }
            else return 0;
        }
        return false;
    }
	
	// returns last id from INSERT query
	function insertId() {
	
		return mysql_insert_id($this->link);
	
	}

}


function is_num($int) {
	if (preg_match("/^([0-9]+)$/", $int)) { return true; }
	else { return false; }
}


// removes magic quotes added by php
function removeMagicQuotes($post)
{

	if (get_magic_quotes_gpc()) {
		
		if (is_array($post)) {
			return array_map('stripslashes',$post);
		}
		else {
			return stripslashes($post);
		}
		
	} else {
		return $post; // magic quotes are not ON so we do nothing
	}

}

// escapes and quotes string for sql if necessary
function escq($str) {

	if (phpversion() >= '4.3.0') {
		$str = mysql_real_escape_string($str);
	} else {
		$str = mysql_escape_string($str);		
	}

	if (!is_numeric($str)) {
        $str = "'" . $str . "'";
    }

    return $str;

}

define('NEW_LINE', "\r\n");


?>