<?php
/** Form for entering and editing post medieval coin data 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoinForm extends Pas_Form {
	
public function __construct($options = null) {

	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodPostMed();
	
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsPostMedieval();
	
	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();
	
	$dies = new Dieaxes;
	$die_options = $dies->getAxes();
	
	$wears = new Weartypes;
	$wear_options = $wears->getWears();
	
	$rulers = new Rulers();
	$ro = $rulers->getPostMedievalRulers();
	
	$mints = new Mints();
	$mo = $mints->getPostMedievalMints();


parent::__construct($options);
       
	$this->setName('postmedievalcoin');

		Zend_Debug::dump($denomination_options);
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose denomination' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)));

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags','StringTrim'))
		->setOptions(array('separator' => ''))
		->addValidator('Digits');

	$categoryID = new Zend_Form_Element_Select('categoryID');
	$categoryID->setLabel('Category of coin: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose category' => $cat_options))
		->addValidator('InArray', false, array(array_keys($cat_options)));
	
	$ruler_id= new Zend_Form_Element_Select('ruler_id');
	$ruler_id->setLabel('Ruler: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose ruler' => $ro))
		->addValidator('InArray', false, array(array_keys($ro)))
		->addValidator('Digits');
	
	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addValidator('Digits')
		->addFilters(array('StripTags','StringTrim'));

	$mint_id= new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
		->addMultiOptions(array(NULL => NULL,'Choose mint' => $mo))
		->addValidator('InArray', false, array(array_keys($mo)))
		->addValidator('Digits')
		->addFilters(array('StripTags','StringTrim'));
	
	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
		->setValue(1)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose coin status' => $status_options))
		->addValidator('InArray', false, array(array_keys($status_options)));
	
	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->setValue(1)
		->addFilters(array('StripTags','StringTrim'))
		->setOptions(array('separator' => ''));
	
	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose coin status' => $wear_options))
		->addValidator('InArray', false, array(array_keys($wear_options)));
	
	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
		->addFilters(array('StripTags','StringTrim','EmptyParagraph'))
		->setAttrib('size',60);
	
	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
		->addFilters(array('StripTags','StringTrim','EmptyParagraph'))
		->setAttrib('size',60);
	
	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
		->setAttrib('rows',5)
		->setAttrib('cols',40)
		->addFilters(array('StripTags','StringTrim','BasicHtml','EmptyParagraph'));
	
	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
		->addValidators(array('NotEmpty'))
		->setAttrib('rows',5)
		->setAttrib('cols',40)
		->addFilters(array('StripTags','StringTrim','BasicHtml','EmptyParagraph'));
	
	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose coin status' => $die_options))
		->addValidator('InArray', false, array(array_keys($die_options)));
	
	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
		->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
		->addFilters(array('StripTags','StringTrim'))
		->setOptions(array('separator' => ''));
	
	$typeID = new Zend_Form_Element_Select('typeID');
	$typeID->setLabel('Coin type: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'));
	
	$rev_mm = new Zend_Form_Element_Textarea('reverse_mintmark');
	$rev_mm->setLabel('Reverse mintmark: ')
		->setAttrib('rows',3)
		->setAttrib('cols',80)
		->addFilters(array('StripTags','StringTrim','BasicHtml','EmptyParagraph'));;
	
	$initial = new Zend_Form_Element_Textarea('initial_mark');
	$initial->setLabel('Initial mark: ')
		->setAttrib('rows',3)
		->setAttrib('cols',80)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class','expanding');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array(
	$ruler_id, $ruler_qualifier, $denomination,
	$denomination_qualifier, $mint_id, $typeID,
	$status, $categoryID, $status_qualifier, $degree_of_wear,
	$obverse_description, $obverse_inscription,	$reverse_description,
	$reverse_inscription, $die_axis_measurement, $die_axis_certainty,
	$submit, $rev_mm, $initial,
	$hash));
	
	$this->addDisplayGroup(array(
	'categoryID', 'ruler_id', 'typeID',
	'ruler_qualifier', 'denomination', 'denomination_qualifier',
	'mint_id', 'status', 'status_qualifier',
	'degree_of_wear', 'obverse_description', 'obverse_inscription',
	'reverse_description', 'reverse_inscription', 'reverse_mintmark',
	'initial_mark', 'die_axis_measurement', 'die_axis_certainty',
	'submit'), 'details');

	parent::init();
	
}
}