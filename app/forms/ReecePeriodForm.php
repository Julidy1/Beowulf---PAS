<?php
/** Form for adding and editing Reece period data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ReecePeriodForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);

 	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
	$this->setName('reeceperiods');

	$period_name = new Zend_Form_Element_Text('period_name');
	$period_name->setLabel('Reece Period name: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->setAttrib('size',60)
		->addErrorMessage('You must enter a period name')
		->setDecorators($decorators);

	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Description of period: ')
		->setRequired(true)
		->setAttrib('cols',70)
		->setAttrib('rows',20)
		->addFilters(array('BasicHtml', 'EmptyParagraph', 'StringTrim'))
		->addErrorMessage('You must enter a description');

	$date_range = new Zend_Form_Element_Text('date_range');
	$date_range->setLabel('Date range of period: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a date range for this period')
		->setDecorators($decorators);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array(
	$period_name, $description, $date_range,
	$submit));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);

	$this->removeDecorator('HtmlTag');
	$this->addDisplayGroup(array('period_name','description','date_range','submit'), 'details');
	$this->details->addDecorators(array(
	    'FormElements',
	    array('HtmlTag', array('tag' => 'ul'))
	));
	
	$this->details->setLegend('Reece Periods');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');

	}
}
