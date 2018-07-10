<?php
class Application_Model_Mapper_Base extends stdClass
{

	public $model = "";
	public $dbTableClassName = "Application_Model_DbTable_";
	public $modelClassName = "Application_Model_";
	
    protected $_dbTable;
	protected $_ApplicationModel;
    
	function __construct() {
       $this->dbTableClassName .= $this->model; 
       $this->modelClassName .=  $this->model;
	   
	   $this->_applicationModel = new $this->modelClassName;
   }
   
    public function setDbTable ($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        
        if (! $dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        
        $this->_dbTable = $dbTable;
        
        return $this;
    }
    
    public function getDbTable ()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable($this->dbTableClassName);
        }
        
        return $this->_dbTable;
    }
    
    public function save (stdClass $model)
    {

		$data = $model->toArray();
       
        unset($data['id']);
        
        if (NULL == $model->id) {
            return $this->getDbTable()->insert($data);
        } else {
            return $this->getDbTable()->update($data, array('id = ?' => $model->id));
        }
    }
    
    public function find ($id, stdClass $model)
    {
        $result = $this->getDbTable()->find($id);
        
        if (0 == count($result)) {
            return;
        }
        
        $row = $result->current();
        
        $model->setOptions($row->toArray());
       
    }
    
    public function select ($id)
    {
        $result = $this->getDbTable()->find($id);
        
        if (0 == count($result)) {
            return;
        }
        
        $row = $result->current();
        
        return $row;
    }
    
    public function fetchAll ($params = array())
    {
        $select = $this->getDbTable()->select();
        
        foreach ($params as $key=>$value)
        {
            $select->where($key." = ? ", $value);
        }
        
        $select->order('id desc');
        
        $resultSet = $this->getDbTable()->fetchAll($select);
        $modelList = array();
        
        foreach ($resultSet as $row) {
            $model = new $this->modelClassName;
            $model->setOptions($row->toArray());
            $modelList[] = $model;
        }
        
        return $modelList;
    }

    public function delete ($id)
    {
        $this->getDbTable()->delete(array('id = ?' => $id));
    }
    
    public function getCount ($params = array())
    {
        $select = $this->getDbTable()->select();
    
        foreach ($params as $key=>$value)
        {
            $select->where($key." = ? ", $value);
        }
    
        $resultCount = $this->getDbTable()->fetchAll($select)->count();
       
        return intval($resultCount);
    }
    
}