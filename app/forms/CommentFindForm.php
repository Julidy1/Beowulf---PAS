<?php
/**
* Form for commenting on a find
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class CommentFindForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);

	$decorators = array(
            array('ViewHelper'),
            array('Description', array('tag' => '','placement' => 'append')),
            array('Errors',array('placement' => 'append','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
        );


	$this->setName('comments');


	/**
	 * Form hash to prevent CSRF
     */
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(1800);
	$this->addElement($hash);

	/**
	 * The name of the author
     */
	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Please enter a valid name!')
	->setDecorators($decorators);

	/**
	 * Email address element
     */
	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
	->setDecorators($decorators)
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addValidator('EmailAddress')
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('* This will not be displayed to the public.');

	/**
	 * url of the commenter
     */
	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
	->setDecorators($decorators)
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addErrorMessage('Please enter a valid address!')
	->setDescription('* Not compulsory');


	$comment_content = new Zend_Form_Element_Textarea('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->addFilters(array('StringTrim','BasicHtml','EmptyParagraph'))
	->setAttrib('rows',10)
	->setAttrib('cols',80)
	->addErrorMessage('Please enter something in the comments box.')
	->setDescription('The following HTML tags can be used - a,p,ul,li,em,strong,br,img,a - and
	paragraphs will be automatically created');


	$privateKey = $this->_config->webservice->recaptcha->privatekey;
	$pubKey = $this->_config->webservice->recaptcha->pubkey;

	$captcha = new Zend_Form_Element_Captcha('captcha', array(
                        	'captcha' => 'ReCaptcha',
				'label' => 'Prove you are not a robot/spammer',
                                'captchaOptions' => array(
                                'captcha' => 'ReCaptcha',
                                'privKey' => $privateKey,
                                'pubKey' => $pubKey,
				'theme'=> 'clean')
                        ));


	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class', 'large');

	$auth = Zend_Auth::getInstance();
	if(!$auth->hasIdentity()) {
	$this->addElements(array( $comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$captcha, $submit));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content', 'captcha'), 'details');

	$this->addDisplayGroup(array('submit'), 'submit');
	} else {
	$user = $auth->getIdentity();
	$comment_author->setValue($user->fullname);
	$comment_author_email->setValue($user->email);

	$this->addElements(array(

        $comment_author,
	$comment_author_email,
        $comment_content, $comment_author_url, $submit));

	$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url',
	'comment_content'), 'details');
	}
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Enter your comments: ');

	$this->addDisplayGroup(array('submit'), 'submit');
	}
}