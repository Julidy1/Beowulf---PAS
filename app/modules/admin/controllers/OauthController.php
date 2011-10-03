<?php
/** Controller for administering oauth and setting up tokens
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_OauthController extends Pas_Controller_Action_Admin {
	
	/** Set up the ACL and resources
	*/		
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->view->messages = $this->_flashMessenger->getMessages();
    }
    
	/** List available Oauth tokens that have been generated for use.
	*/	
    public function indexAction() {
    $tokens = new OauthTokens();
    $this->view->tokens = $tokens->fetchAll();
    }
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooAction() {
    $yahoo = new Yahoo();
    $this->_redirect($yahoo->request());
	}
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooaccessAction(){
	$yahoo = new Yahoo();
	$yahoo->access();
	$this->_flashMessenger->addMessage('Token created');
	$this->_redirect('/admin/oauth/');
	}
	
	public function twitterAction(){
	$twitter = new Twitter($this->_config->webservice->twitter->consumerkey, 
		$this->_config->webservice->twitter->consumerSecret);
	$this->_redirect($twitter->request());
	}
	
}