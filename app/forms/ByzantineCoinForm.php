<?php
/**
* Form for adding and editing Byzantine coin data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ByzantineCoinForm extends Pas_Form
{
public function __construct($options = null)
{
	// Construct the select menu data
	$rulers = new Rulers();
	$ruler_options = $rulers->getRulersByzantine();

	$denominations = new Denominations();
	$denomination_options = $denominations->getDenomsByzantine();
	$mints = new Mints();
	$mint_options = $mints->getMintsByzantine();


	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new Dieaxes;
	$die_options = $dies->getAxes();

	$wears = new Weartypes;
	$wear_options = $wears->getWears();

	parent::__construct($options);


	$this->setName('romancoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->addValidators(array('NotEmpty','Int'))
	->addMultiOptions(array(NULL => NULL,'Choose denomination' => $denomination_options))
	->setRegisterInArrayValidator(false)
	->addValidator();

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->setOptions(array('separator' => ''));

	$ruler= new Zend_Form_Element_Select('ruler_id');
	$ruler->setLabel('Ruler: ')
	->addValidators(array('NotEmpty','Int'))
	->addMultiOptions(array(NULL => NULL,'Choose denomination' => $ruler_options))
	->setRegisterInArrayValidator(false);

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Integer'));

	$mint_ID= new Zend_Form_Element_Select('mint_id');
	$mint_ID->setLabel('Issuing mint: ')
	->addValidators(array('NotEmpty','Integer'))
	->addMultiOptions(array(NULL => NULL,'Choose denomination' => $mint_options))
	->setRegisterInArrayValidator(false);

	$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
	$mint_qualifier->setLabel('Mint qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Integer'));

	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Integer'))
	->setValue(1)
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $status_options));

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Integer'));


	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Integer'))
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $wear_options));

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
	->setAttrib('size',60)
	->addFilters(array('StripTags','StringTrim'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60);

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',8)
	->setAttrib('cols',80)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class','expanding');

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',8)
	->setAttrib('cols',80)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class','expanding');

	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Int'))
	->addMultiOptions(array(NULL => NULL,'Choose die axis' => $die_options));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('NotEmpty','Int'))
	->setOptions(array('separator' => ''));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$ruler,	$denomination, $mint_ID,
	$status, $degree_of_wear, $obverse_description,
	$obverse_inscription, $reverse_description, $reverse_inscription,
	$die_axis_measurement, $die_axis_certainty, $mint_qualifier,
	$ruler_qualifier, $denomination_qualifier,$status_qualifier,
	$submit, $hash));

	$this->addDisplayGroup(array('denomination', 'denomination_qualifier', 'ruler_id',
	'ruler_qualifier', 'mint_id', 'mint_qualifier',
	'status', 'status_qualifier', 'degree_of_wear',
	'obverse_description', 'obverse_inscription', 'reverse_description',
	'reverse_inscription', 'die_axis_measurement', 'die_axis_certainty'), 'details');


	$this->addDisplayGroup(array('submit'),'submit');
        parent::init();
	}

}