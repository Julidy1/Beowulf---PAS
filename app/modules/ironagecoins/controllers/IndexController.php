<?php
/** Controller for Iron Age period's index page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_IndexController extends Pas_Controller_Action_Admin {
	
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
 	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
    
	/** Internal period ID number for the Iron Age
	*/    
	protected $_period = '16';

	/** Set up data for the index page
	*/
	public function indexAction() {
	$content = new Content();
	$this->view->content =  $content->getFrontContent('ironagecoins');
	$images = new Slides();
	$this->view->images = $images->getExamplesCoinsPeriod('iron age',4);
	}
}
