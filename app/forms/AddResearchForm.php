<?php
class AddResearchForm extends Zend_Form
{

public function __construct($options = null)
{
$auth = Zend_Auth::getInstance();
if($auth->hasIdentity())
{
$user = $auth->getIdentity();
{
$research = new MyResearch();
$research_topics = $research->getMyProjects($user->id);
}
}


parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
       
$this->clearDecorators();
$decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'div')),
			
        );	  
$this->setName('addresearch');

$title = new Zend_Form_Element_Select('researchtitle');
$title->setLabel('My research group: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addMultiOptions(array(NULL => NULL,'Choose an agenda' => $research_topics))
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setDecorators($decorators);

$findID = new Zend_Form_Element_Hidden('findID');
$findID->removeDecorator('label')
   ->removeDecorator('HtmlTag');

//Submit button 

$submit = new Zend_Form_Element_Submit('submitit');
$submit->setAttrib('id', 'submitit');
$submit->clearDecorators();
        $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
$this->addElements(array($title,$findID,$submit));

$this->setLegend('Add to research');
  $this->addDecorator('FormElements')
	 ->addDecorator('Form')
	 ->addDecorator('Fieldset');

  

       

}
}