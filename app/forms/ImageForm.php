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
	
	$licenses = new LicenseTypes();
	$license = $licenses->getList();

	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth;
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	
	if(!is_null($user->copyright)){
	$this->_copyright = $user->copyright;
		} elseif(!is_null($user->fullname)) {
			$this->_copyright = $user->forename . ' ' . $user->surname;
		} else {
			$this->_copyright = $user->fullname;
		}
	} 
		
	
	
	$copyList = array_filter(array_merge(array($this->_copyright => $this->_copyright), $copy));
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
	->addMultiOptions(array(NULL => 'Select a licence holder','Valid copyrights' => $copyList))
	->setDescription('You can set the copyright of your image here to your institution. If you are a public recorder, it 
	should default to your full name. For institutions that do not appear contact head office for getting it added.')
	->setValue($this->_copyright);
	
	$licenseField = new Zend_Form_Element_Select('ccLicense');
	$licenseField->setDescription('Our philosophy is to make our content available openly, by default we set the license as
	use by attribution to gain the best public benefit. You can choose a different license if you wish.');
	$licenseField->setRequired(true)
	->setLabel('Creative Commons license:')
	->addMultiOptions(array(NULL => 'Select a license', 'Available licenses' => $license))
	->setValue(5)
	->addValidator('Int');

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
	$licenseField, $submit));
	$this->setMethod('post');
	$this->addDisplayGroup(array(
	'image', 'label', 'county',
	'period', 'copyrighttext', 'ccLicense',
	'type'),'details');


	$this->addDisplayGroup(array('submit'), 'submit')->removeDecorator('HtmlTag');
	$this->details->setLegend('Attach an image');

	parent::init();
	}
}