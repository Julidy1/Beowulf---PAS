<?php
/** Form for editing and adding hoards information
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class HoardForm extends Pas_Form
{

	public function __construct($options = null) {
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	parent::__construct($options);
	

	$this->setName('hoard');


	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Hoard title: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('You must enter a title for this hoard');

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of hoard: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars')) 
	->addErrorMessage('You must enter a description for this hoard');
	
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Broad period attributed to: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL,'Choose reason' => $period_options))
	->addValidator('inArray', false, array(array_keys($period_options)))
	->addErrorMessage('You must choose a period');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$term,$termdesc,$period,$submit)
	);

	$this->addDisplayGroup(array('term','termdesc','period'), 'details');
	$this->addDisplayGroup(array('submit'), 'submit');

	$this->setLegend('Hoards: ');

	$this->addDisplayGroup(array('submit'), 'submit');
	parent::init();
	}
}