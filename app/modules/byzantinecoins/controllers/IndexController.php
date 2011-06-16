<?php
/** Controller for displaying byzantine coins index pages with recent examples
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ByzantineCoins_IndexController extends Pas_Controller_ActionAdmin extends {
	
	/** Initialise the ACL and contexts
	*/ 
	public function init(){
 		$this->_helper->_acl->allow(null);
	}

	/** Set up the view for index page
	*/ 
	public function indexAction() {
		$content = new Content();
		$this->view->content =  $content->getFrontContent('byzantinecoins');
		
		$images = new Slides();
		$this->view->images = $images->getExamplesCoinsPeriod('BYZANTINE',4);
	}

}
