<?php 
/** Get submitted messages on the system
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
*/
class Messages extends Pas_Db_Table_Abstract {

	protected $_name = 'messages';

	protected $_primary = 'id';
	
	const SPAM = '{SPAM: Akismet checked}';
	
	const NOTSPAM = 'Akismet checked  - clean';
	
	
	protected $_akismet;
	
	public function init(){
	$akismetkey = $this->_config->webservice->akismet->apikey;
	$this->_akismet = new Zend_Service_Akismet($akismetkey, 'http://www.finds.org.uk');
	}
	
	/** get a count of messages
	* @return array 
	*/
	public function getCount(){
	$messages = $this->getAdapter();
	$select = $messages->select()
		->from($this->_name,array('total' => 'COUNT(id)'))
		->where('replied != ?',(int)1);
	return $messages->fetchAll($select);	
	}

	/** Get a paginated list of messages 
	* @return array $paginator
	*/
	public function getMessages($params){
	$messages = $this->getAdapter();
	$select = $messages->select()
		->from($this->_name)
		->order($this->_primary.' DESC');
    $paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}
	
	/** Add a new help request message and send email to Scheme in box.
	*/
	public function addRequest($data){
	if(!empty($data['csrf'])){
		unset($data['csrf']);
	}
	if(empty($data['comment_date'])){
		$data['comment_date'] = $this->timeCreation();
	}
	if(empty($data['updatedBy'])){
		$data['updatedBy'] = $this->userNumber();
	}
	if ($this->_akismet->isSpam($data)) { 
		$data['comment_approved'] = self::SPAM;
	} else  {
		$data['comment_approved'] = self::NOTSPAM;
	}
	$mail = new Zend_Mail();
	$mail->setBodyText('You submitted this comment/ query: ' . strip_tags($data['comment_content']));
	$mail->setFrom($data['comment_author_email'], $data['comment_author']);
	$mail->addTo('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addCC($data['comment_author_email'], $data['comment_author']);
	$mail->setSubject('Contact us submission');
	$mail->send();	
	return parent::insert($data);
	}

}