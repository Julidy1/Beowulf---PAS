<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinFormLoader
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_Controller_Action_Helper_FindspotFormOptions
    extends Zend_Controller_Action_Helper_Abstract {

  	protected $_view;

    public function preDispatch()
    {

	$this->_view = $this->_actionController->view;
    }

    public function __construct() {
    }

    public function direct(){

    return $this->optionsAddClone();
    }


    protected function _getIdentity(){
    $user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    	return $person->id;
    } else {
    	throw new Pas_Exception_BadJuJu('No user credentials found', 500);
    }
    }



    public function optionsAddClone(){
  	$findspots = new Findspots();
    $findspot = $findspots->getLastRecord($this->_getIdentity());
    $data = $findspot[0];
    $this->_view->form->populate($data);
    Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
    	->addMessage('Your last record data has been cloned');
    if(array_key_exists('county', $data)) {
    $districts = new Places();
    $district = $districts->getDistrictList($data['county']);
    if($district) {
    $this->_view->form->districtID->addMultiOptions(array(NULL => 'Choose district',
    	'Available districts' => $district));
    }
    if(array_key_exists('district', $data)) {
    $parishes = $districts->getParishList($data['district']);
    $this->_view->form->parishID->addMultiOptions(array(NULL => 'Choose parish',
    	'Available parishes' => $parishes));
    }
     if(array_key_exists('county' , $data)) {
    $cnts = new Counties();
    $region_list = $cnts->getRegionsList($data['county']);
    $this->_view->form->regionID->addMultiOptions(array(NULL => 'Choose region',
    	'Available regions' => $region_list));
    }
    }
     if(array_key_exists('landusevalue', $data)) {
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($data['landusevalue']);
    $this->_view->form->landusecode->addMultiOptions(array(NULL => 'Choose code',
    	'Available landuses' => $landusecode_options));
     }
    if(array_key_exists('landowner', $data)) {
    $finders = new Peoples();
    $finders = $finders->getName($findspot['landowner']);
    foreach($finders as $finder) {
    $form->landownername->setValue($finder['term']);
    }
    }
    }

}


