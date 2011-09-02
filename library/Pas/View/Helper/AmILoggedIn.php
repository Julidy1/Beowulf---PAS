<?php
/**
 * A view helper for checking if a user has logged in
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_Url
 * @uses Zend_View_Helper_Gravatar
 * @uses Zend_View_Helper_HeadMeta
 */

class Pas_View_Helper_AmILoggedIn extends Zend_View_Helper_Abstract {

	protected $_auth;
	
	public function __construct(){
	$this->_auth = Zend_Auth::getInstance();
	}
	
	public function AmILoggedIn() {
	if($this->_auth->hasIdentity()) {
	
	$logoutUrl = $this->view->url(array('module' => 'users','controller'=>'account',
	'action'=>'logout'),'default', true);
	
	$user = $this->_auth->getIdentity();
	
	$gravatar = $this->view->gravatar()
     ->setEmail($user->email)
     ->setImgSize(30)
     ->setDefaultImg(Zend_View_Helper_Gravatar::DEFAULT_MONSTERID)
     ->setSecure(true)
     ->setAttribs(array('class' => 'avatar'));
	
     $fullname = $this->view->escape(ucfirst($user->fullname));
	$id = $this->view->escape(ucfirst($user->id));
	$string = '<div id="gravatar">' . $gravatar . '</div>';
	
	$string .= '<p>
	 <a href="'.$this->view->url(array('module' => 'users','controller' => 'account'),
	 'default',true).'" title="View your user profile">' . $fullname .  '</a> &raquo; <a href="' 
	. $logoutUrl . '">Log out</a></p><p>Assigned role: ' . ucfirst($user->role) . '</p>';
	
	$this->view->headMeta(ucfirst($user->fullname),'page-user-screen_name');
	
	$allowed =  array('admin' , 'fa');
	if(in_array($user->role,$allowed)) {
	$string .= '<p><a href="' .$this->view->url(array('module'  => 'admin'),'default',true) 
	. '">Administer site</a></p>';
	}
	} else {
	$loginUrl = $this->view->url(array('module' => 'users'),'default', true);
	$register = $this->view->url(array('module' => 'users','controller' => 'account',
	'action'=> 'register'),'default',true);
	$string = '<p><a href="'.$loginUrl.'" title="Login to our database">Log in</a> | <a href="'
	. $register . '" title="Register with us">Register</a></p>';
	$this->view->headMeta('Public User','page-user-screen_name');
	}
	return $string;
	
	}

}