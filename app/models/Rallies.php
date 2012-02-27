<?php

/** Model for interacting with rallies database table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/
class Rallies extends Pas_Db_Table_Abstract {

	protected $_name = 'rallies';
	protected $_primary = 'id';

	/**
     * Retrieves dropdown list array for rallies (cached)
     * @return array
	*/
	public function getRallies() {
	if (!$options = $this->_cache->load('rallydds')) {
	$select = $this->select()
		->from($this->_name, array('id', 'rally_name'))
		->order('rally_name');
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'rallydds');
	}
	return $options;
    }

    /**
     * Retrieves list array for rallies (cached) and paginated
     * @param array $params
     * @return array
	*/
	public function getRallyNames($params)  {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id', 'rally_name', 'date_from',
		'date_to', 'latitude', 'longitude',
		'county', 'district', 'easting',
		'parish', 'map10k', 'map25k',
  		'created', 'updated'))
		->joinLeft('users','users.id = ' . $this->_name.'.createdBy', array('fullname','personid' => 'id'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->order('date_from DESC');
	if(isset($params['year'])){
	$select->where('EXTRACT(YEAR FROM date_to)= ?',$params['year']);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']);
	}
	return $paginator;
    }

    /**
     * Retrieves rally details
     * @param integer $id
     * @return array
	*/
	public function getRally($id) {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")','comments','parish',
		'county','gridref','district',
		'latitude','longitude','easting',
		'northing','fourFigure','created',
		'updated','map25k','map10k'))
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->joinLeft('people',$this->_name.'.organiser = people.secuid',array('organiser' => 'fullname'))
		->joinLeft('finds','finds.rallyID = rallies.id',array('finds' => 'SUM(quantity)'))
		->where('rallies.id = ?',(int)$id)
		->order('date_from DESC')
		->group($this->_primary);
	return $rallies->fetchAll($select);
    }

     /**
     * Retrieves rally names by id
     * @param integer $id
     * @return array
	*/
	public function getFindRallyNames($id) {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")'))
		->where('finds.id = ?', (int)$id)
		->limit('1');
	return $rallies->fetchAll($select);
    }

     /**
     * Retrieves rally names by id
     * @param integer $id
     * @return array
	*/
	public function getFindToRallyNames($id) {
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")'))
		->joinLeft('finds','rallies.id = finds.rallyID',array())
		->where('finds.id = ?',(int)$id)
		->limit('1');
	return $rallies->fetchAll($select);
    }

     /**
     * Retrieves rally names for mapping xml view
     * @param integer $year
     * @return array
	*/
	public function getMapdata($year = NULL){
	$rallies = $this->getAdapter();
	$select = $rallies->select()
		->from($this->_name, array(
		'id','name' => 'rally_name','df' => 'DATE_FORMAT(date_from,"%D %M %Y")',
		'dt' => 'DATE_FORMAT(date_to,"%D %M %Y")','lat' => 'latitude', 'lng' => 'longitude'))
		->where('latitude > ?',0);
	if(isset($year)){
	$select->where('EXTRACT(YEAR FROM date_to)= ?', (int)$year);
	}
	return $rallies->fetchAll($select);
    }

}


