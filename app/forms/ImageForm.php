<?php
/** Form for uploading images
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ImageForm extends Pas_Form
{
	protected $_auth = NULL;

	protected $_copyright = NULL;

	
public function __construct($options = null) {
	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();
	
	$copyrights = new Copyrights();
	$copy = $copyrights->getStyles();

	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth; 
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$this->_copyright = $user->copyright;
	} else {
	$this->_copyright = 'The Portable Antiquities Scheme';
	}	
	
	parent::__construct($options);

	
	$this->setName('imagetofind');
	

	$image = new Zend_Form_Element_File('image');
	$image->setLabel('Upload an image: ')
	->setRequired(true)
	->setAttrib('size',20)
	->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif,tiff,JPG,JPEG,GIF,PNG,TIFF,TIF') 
	->setDescription('Filename should not include spaces,commas,( or )')
	->addErrorMessage('You must upload a file with the correct file extension in this array - jpeg,tif,jpg,png,gif');

	$imagelabel = new Zend_Form_Element_Text('label');
	$imagelabel->setLabel('Image label: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addErrorMessage('You must enter a label')
	->setDescription('This must be descriptive text about the image - NOT THE FILE or FIND NUMBER/NAME')
	->addFilters(array('StripTags','StringTrim'));
		
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
	->setRequired(true)
	->addErrorMessage('You must enter a period for the image')
	->addMultiOptions(array(NULL => 'Select a period', 'Valid periods' => $period_options))
	->addValidator('inArray', false, array(array_keys($period_options)));
		
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->setRequired(true)
	->addErrorMessage('You must enter a county of origin')
	->addMultiOptions(array(NULL => 'Select a county of origin','Valid counties' => $county_options))
	->addValidator('inArray', false, array(array_keys($county_options)));
	
	$copyright = new Zend_Form_Element_Select('copyrighttext');
	$copyright->setLabel('Image copyright: ')
	->setRequired(true)
	->addErrorMessage('You must enter a licence holder')
	->addMultiOptions(array(NULL => 'Select a licence holder','Valid copyrights' => $copy))
	->setValue($this->_copyright);
		
	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Image type: ')
	->setRequired(true)
	->addMultiOptions(array(NULL => 'Select the type of image',
	'Image types' => array('digital' => 'Digital image','illustration' => 'Scanned illustration')))
	->setValue('digital');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$image, $imagelabel, $county,
	$period, $copyright, $type,
	$submit));
	$this->setMethod('post');
	$this->addDisplayGroup(array(
	'image', 'label', 'county',
	'period', 'copyrighttext', 'type'),
	'details');
	
	
	$this->addDisplayGroup(array('submit'), 'submit')->removeDecorator('HtmlTag');
	$this->details->setLegend('Attach an image');

	parent::init();
	}
}