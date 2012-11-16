<?php
/** Model for displaying coroner details
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class Coroners extends Pas_Db_Table_Abstract {
	
	protected $_geoPlanet;
	
	protected $_geocoder;
	
	protected $_name = 'coroners';
	
	protected $_primary = 'id';
	
	public function init(){
		$this->_geoPlanet = new Pas_Service_Geo_Geoplanet($this->_config->webservice->ydnkeys->appid);
		$this->_geocoder = new Pas_Service_Geo_Coder($this->_config->webservice->googlemaps->apikey);
	}
	
	/** retrieve all coroners on the system
	* @param integer $params['page'] 
	* @return object
	*/
	public function getAll($params) {
	$coroners = $this->getAdapter();
	$select = $coroners->select()
		->from($this->_name);
	$data = $coroners->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setCache($this->_cache);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']);
	}
	$paginator->setItemCountPerPage(20)
		->setPageRange(10);
	return $paginator;
	}

	/** Retrieve individual coroner details
	* @param integer $id] 
	* @return object
	*/
	
	public function getCoronerDetails($id) {
	if (!$data = $this->_cache->load('coroner' . $id)) {
	$coroners = $this->getAdapter();
	$select = $coroners->select()
		->from($this->_name)
		->where('id = ?',(int)$id);
	$data = $coroners->fetchAll($select);
	$this->_cache->save($data, 'coroner' . $id);
	}
	return $data;
	}
	
	public function addCoroner($data){
	if(is_array($data)){
	$address = $data['address_1'] . ',' . $data['address_2'] . ','
	. $data['town'] . ',' . $data['county'] . ',' 
	. $data['postcode'] . ',' . $data['country'];
	$coords = $this->_geocoder->getCoordinates($address);	
	if($coords){
		$data['latitude'] = $coords['lat'];
		$data['longitude'] = $coords['lon']; 
		$place = $this->_geoPlanet->reverseGeoCode($coords['lat'], $coords['lon']);
		$data['woeid'] = $place['woeid'];
	} else {
		$data['latitude'] = NULL;
		$data['longitude']  = NULL;
		$data['woeid'] = NULL;
	}
	return parent::insert($data);
	} else {
		throw new Exception('Data must be in array format');
	}
	}
	
	public function updateCoroner($data, $id){
	if(is_array($data)){
	$address = $data['address_1'] . ',' . $data['address_2'] . ','
	. $data['town'] . ',' . $data['county'] . ',' 
	. $data['postcode'] . ',' . $data['country'];
	$coords = $this->_geocoder->getCoordinates($address);
	
	if($coords){
		$data['latitude'] = $coords['lat'];
		$data['longitude'] = $coords['lon']; 
		$place = $this->_geoPlanet->reverseGeoCode($coords['lat'],$coords['lon']);
		$data['woeid'] = $place['woeid'];
	} else {
		$data['latitude'] = NULL;
		$data['longitude']  = NULL;
		$data['woeid'] = NULL;
	}
	
	$where = $this->getAdapter()->quoteInto($this->_primary . '= ?', (int) $id );
	return parent::update($data, $where);
	} else {
		throw new Exception('Data must be in array format');
	}	
	}
}
