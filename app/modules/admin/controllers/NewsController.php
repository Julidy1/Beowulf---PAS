<?php
/** Controller for managing latest news on the website
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_NewsController extends Pas_Controller_Action_Admin {	
	
	protected $_news;
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_news = new News();
	}
    
	const REDIRECT = '/admin/news/';
	
	/** Display an index of news stories
	*/		
	public function indexAction(){
	$this->view->news = $this->_news->getAllNewsArticlesAdmin($this->_getAllParams());
	}
	/** Add and geocode a news story
	*/		
	public function addAction(){
	$form = new NewsStoryForm();
	$form->submit->setLabel('Add story');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$this->_news->addNews($form->getValues());
	$this->_flashMessenger->addMessage('News story created!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	}
	}
	/** Edit a news story
	*/			
	public function editAction(){
	$form = new NewsStoryForm();
	$form->submit->setLabel('Update story');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$this->_news->updateNews($form->getValues(), $this->_getParam('id'));
    $this->_helper->solrUpdater->update('beocontent', $this->_getParam('id'));  
	$this->_flashMessenger->addMessage('News story information updated!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($form->getValues());
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$form->populate($this->_news->fetchRow('id=' . $id)->toArray());
	}
	}
	}
	/** Delete a news story
	*/		
	public function deleteAction(){
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_news->delete($where);
	$this->_flashMessenger->addMessage('Record deleted!');
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->new = $this->_news->fetchRow('id='.$id);
	}
	}
	}

}