<?php
/**
* Data model for accessing treasure actions in the database
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @version 1
* @since 22 October 2010, 17:12:34
*/
class TreasureActionTypes extends Zend_Db_Table_Abstract {

	protected $_cache;
	
	protected $_primary = 'id';
	
	protected $_name = 'treasureActionTypes';
	
	/** Construct the auth and config objects
	* @return object
	*/
	public function init() {
		$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/** Get list of all treasure actions
	* @return array
	*/
	public function getActions(){
		if (!$data = $this->_cache->load('tactions')) {
    	$actions = $this->getAdapter();
		$select = $actions->select()
				->from($this->_name)
				->where('valid = ?',(int)1)
				->order('action');
        $data =  $actions->fetchAll($select);
		$this->_cache->save($data, 'tactions');
		}
        return $data;
	}
	/** Get key value pair list of actions for treasure
	* @return array
	*/
	public function getList(){
		if (!$data = $this->_cache->load('tactionslist')) {
    	$actions = $this->getAdapter();
		$select = $actions->select()
				->from($this->_name,array('id','action'))
				->where('valid = ?',(int)1)
				->order('action');
        $data =  $actions->fetchPairs($select);
		$this->_cache->save($data, 'tactionslist');
		}
        return $data;
	}
}

