<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/

class Faqs extends Zend_Db_Table_Abstract {
	
	protected $_name = 'faqs';
	
	protected $_primary = 'id';
	
	/** get all frequently asked questions and their answers
	* @return array
	*/
	public function getAll() {
		$faqs = $this->getAdapter();
		$select = $faqs->select()
            ->from($this->_name, array('id','question','answer'))
			->where('valid = ?', (int)1)
			->order($this->_primary);
	   return $faqs->fetchAll($select);
	}		
}