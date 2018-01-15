<?php
	class fetch_data implements Iterator
	{
		protected $_query;
		protected $_sql;
		protected $_pointer=0;
		protected $_numResult=0;
		protected $_result=array();
		protected $_db;
		
		function __construct($sql,$db)
		{
			$this->_sql=$sql;
			$this->_db=$db;
		}

		function rewind()
		{
			$this->_pointer=0;
		}

		function key()
		{
			return $this->_pointer;
		}

		protected function _getQuery()
		{
			if(!$this->_query){				
				$this->_query = $this->_db->Execute($this->_sql);
				if(!$this->_query){
					throw new Exception('Gagal membaca data dari database:'.$this->_db->ErrorMsg());
				}
			}
			return $this->_query;
		}

		public function _getNumResult()
		{

			if(!$this->_numResult){
				$this->_numResult=$this->_getQuery()->RecordCount();
			}
			return $this->_numResult;
		}

		protected function _getRow($pointer)
		{
			if(isset($this->_result[$pointer]))
			{
				return $this->_result[$pointer];
			}

			$row=$this->_getQuery()->FetchRow();

			if($row){
				$this->_result[$pointer]=$row;
			}
			return $this->_result[$pointer];
		}

		function valid()
		{
			if(($this->_pointer >= 0) && ($this->_pointer < $this->_getNumResult()))
			{
				return true;
			}
			return false;
		}

		function next()
		{

			$row = $this->_getRow($this->_pointer);
			if($row)
			{
				$this->_pointer++;
			}
			
			return $row;
		}

		function current()
		{
			return $this->_getRow($this->_pointer);
		}
		
	}
?>