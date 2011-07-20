<?php
/** Controller for administering the terminology on the database
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_TerminologyController extends Pas_Controller_ActionAdmin {
	
	protected $_redirectUrl = '/admin/terminology/';
	/** Setup the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Display the index page
	*/
	public function indexAction(){
	}
	/** Display a list of activities for finders.
	*/
	public function activitiesAction() {
	$activities = new PrimaryActivities();
	$this->view->activities = $activities->getActivitiesListAdmin();
	}

	/** Add an activity
	*/	
	public function addactivityAction() {
	$form = new ActivityForm();
	$form->submit->setLabel('Add a new activity');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$activities = new PrimaryActivities();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $activities->update($insertData);
	$this->_redirect($this->_redirectUrl.'activities');
	$this->_flashMessenger->addMessage('Activity created!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit an activity
	*/	
	public function editactivityAction() {
	if($this->_getparam('id',false)) {
	$this->_flashMessenger->addMessage('Activity details updated');
	$form = new ActivityForm();
	$form->submit->setLabel('Update details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$activities = new PrimaryActivities();
	$where = array();
	$where[] = $activities->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $activities->update($updateData, $where);
	$this->_redirect($this->_redirectUrl.'activities');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$activities = new PrimaryActivities();
	$activity = $activities->fetchRow('id='.(int)$id);
	if(count($activity))
	{
	$form->populate($activity->toArray());
	} else {
	throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete an activity
	*/
	public function deleteactivityAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$activities = new PrimaryActivities();
	$where = 'id = ' . $id;
	$activities->delete($where);
	}
	$this->_redirect($this->_redirectUrl . 'activities');
	$this->_flashMessenger->addMessage('Finder\'s primary activity type deleted!');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ((int)$id > 0) {
	$activities = new PrimaryActivities();
	$this->view->activity = $activities->fetchRow('id=' . $id);
	}
	}
	}
	/** Display a list of discovery methods
	*/	
	public function methodsAction() {
	$methods = new DiscoMethods();
	$this->view->methods = $methods->getDiscMethodsListAdmin();
	}
	/** Edit a method of discovery
	*/
	public function editmethodAction() {
	if($this->_getParam('id',false)) {
	$form = new DiscoMethodsForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$methods = new DiscoMethods();
	$where = array();
	$where[] = $methods->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'method' => $form->getValue('method'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $methods->update($updateData, $where);
	$this->_flashMessenger->addMessage('Method of discovery information updated!');
	$this->_redirect($this->_redirectUrl.'methods');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$methods = new DiscoMethods();
	$method = $methods->fetchRow('id=' . $id);
	if(count($method))
	{
	$form->populate($method->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a method of discovery
	*/	
	public function deletemethodAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$methods = new DiscoMethods();
	$where = 'id = ' . $id;
	$methods->delete($where);
	}
	$this->_redirect($this->_redirectUrl.'methods');
	$this->_flashMessenger->addMessage('Method of discovery deleted.');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$methods = new DiscoMethods();
	$this->view->method = $methods->fetchRow('id=' . $id);
	}
	}
	}
	/** Add a method of discovery
	*/
	public function addmethodAction() {
	$form = new DiscoMethodsForm();
	$form->submit->setLabel('Add a new discovery method');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$methods = new DiscoMethods();
	$insertData = array(
	'method' => $form->getValue('method'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $methods->insert($updateData);
	$this->_redirect($this->_redirectUrl.'methods');
	$this->_flashMessenger->addMessage('Method of discovery created!');
	} else  {
	$this->_flashMessenger->addMessage('Please correct errors');
	$form->populate($formData);
	}
	}
	}
	
	/** List decorative methods
	*/
	public function decorationmethodsAction() {
	$decs = new Decmethods();
	$this->view->decs = $decs->getDecorationDetailsListAdmin();
	}
	/** Add a decorative method
	*/
	public function adddecorationmethodAction() {
	$form = new DecMethodsForm();
	$form->submit->setLabel('Add a new decoration method...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$decs = new Decmethods();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $decs->insert($insertData);
	$this->_redirect($this->_redirectUrl.'decorationmethods');
	$this->_flashMessenger->addMessage('A new decoration method has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a decorative method.
	*/	
	public function editdecorationmethodAction() {
	if($this->_getParam('id',false)) {
	$form = new DecMethodsForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$decs = new Decmethods();
	$where = array();
	$where[] = $decs->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $decs->update($updateData, $where);
	$this->_flashMessenger->addMessage('Decoration method information updated!');
	$this->_redirect($this->_redirectUrl.'decorationmethods');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$decs = new Decmethods();
	$dec = $decs->fetchRow('id=' . $id);
	if(count($decs)) {
	$form->populate($dec->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a decorative method
	*/	
	public function deletedecorationmethodAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$decs = new Decmethods();
	$where = 'id = ' . $id;
	$decs->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl.'decorationmethods');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$decs = new Decmethods();
	$this->view->dec = $decs->fetchRow('id='.$id);
	}
	}
	}
	/** List surface treatments
	*/	
	public function surfacesAction(){
	$surfaces = new Surftreatments();
	$this->view->surfaces = $surfaces->getSurfaceTreatmentsAdmin();
	}
	/** Add a surface treatment
	*/	
	public function addsurfaceAction() {
	$form = new SurfTreatmentsForm();
	$form->submit->setLabel('Add a new surface treatment to the system...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'), 
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$surfaces = new Surftreatments();
	$update = $surfaces->insert($insertData);
	$this->_flashMessenger->addMessage('A new surface treatment has been created on the system!');
	$this->_redirect($this->_redirectUrl . 'surfaces');
	} else  {
	$form->populate($formData);
	}
	}
	}
	/** Edit a surface treatment
	*/	
	public function editsurfaceAction() {
	if($this->_getParam('id',false)) {
	$form = new SurfTreatmentsForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$surfaces = new Surftreatments();
	$id = $this->_getParam('id'); 
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] = $surfaces->getAdapter()->quoteInto('id = ?', $id);
	$update = $surfaces->update($updateData, $where);
	$this->_flashMessenger->addMessage('Surface treatment information updated!');
	$this->_redirect($this->_redirectUrl.'surfaces/');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$surfaces = new Surftreatments();
	$surface = $surfaces->fetchRow('id='.$id);
	if(count($surface))
	{
	$form->populate($surface->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a surface treatment
	*/
	public function deletesurfaceAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$surfaces = new Surftreatments();
	$where = 'id = ' . $id;
	$surfaces->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'surfaces');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$surfaces = new Surftreatments();
	$this->view->surface = $surfaces->fetchRow('id=' . $id);
	}
	}
	}
	/** List periods in use
	*/	
	public function periodsAction(){
	$periods = new Periods();
	$this->view->periods = $periods->getPeriodsAll();
	}
	/** Edit a specific period
	*/	
	public function editperiodAction() {
	if($this->_getParam('id',false)) {
	$form = new PeriodForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$periods = new Periods();
	$where = array();
	$where[] = $periods->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$insertdata = array(
	'term' => $form->getValue('term'),
	'fromdate' => $form->getValue('fromdate'),
	'todate' => $form->getValue('todate'),
	'valid' => $form->getValue('valid'),
	'notes' => $form->getValue('notes'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	$periods->update($insertdata,$where);
	$this->_flashMessenger->addMessage('Period information updated!');
	$this->_redirect($this->_redirectUrl . 'periods');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$periods = new Periods();
	$period = $periods->fetchRow('id =' . $id);
	if(count($period))
	{
	$form->populate($period->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a period
	*/
	public function deleteperiodAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$periods = new Periods();
	$where = 'id = ' . $id;
	$rallies->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'periods/');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$periods = new Periods();
	$this->view->period = $periods->fetchRow('id=' . $id);
	}
	}
	}
	/** Add a new period - won't be used much!
	*/	
	public function addperiodAction() {
	$form = new PeriodForm();
	$form->submit->setLabel('Add a new period');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$periods = new Periods();
	$insertdata = array(
	'term' => $form->getValue('term'),
	'fromdate' => $form->getValue('fromdate'),
	'todate' => $form->getValue('todate'),
	'valid' => $form->getValue('valid'),
	'notes' => $form->getValue('notes'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	$periods->insert($insertData);
	$this->_flashMessenger->addMessage('Record created!');
	$this->_redirect($this->_redirectUrl.'periods');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** List ascribed cultures
	*/	
	public function culturesAction() {
	$cultures = new Cultures();
	$this->view->cultures = $cultures->getCulturesListAdmin();
	}
	/** Add an ascribed culture
	*/	
	public function addcultureAction(){
	$form = new CultureForm();
	$form->details->setLegend('Ascribed Culture details: ');
	$form->submit->setLabel('Add a new ascribed culture...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$cultures = new Cultures();
	$insertdata = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	$cultures->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'cultures');
	$this->_flashMessenger->addMessage('A new ascribed culture has been created! ');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit an ascribed culture
	*/	
	public function editcultureAction() {
	if($this->_getParam('id',false)) {
	$this->view->headTitle("Edit an ascribed culture ");
	$form = new CultureForm();
	$form->details->setLegend('Edit an ascribed culture');
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$cultures = new Cultures();
	$where = array();
	$where[] = $cultures->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	$cultures->update($updateData,$where);
	$this->_flashMessenger->addMessage('Ascribed Culture information updated!');
	$this->_redirect($this->_redirectUrl . 'cultures');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$cultures = new Cultures();
	$culture = $cultures->fetchRow('id = ' . $this->_getParam('id'));
	if(count($culture) != NULL )
	{
	$form->populate($culture->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
	/** Delete an ascribed culture
	*/
	public function deletecultureAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$cultures = new Cultures();
	$where = 'id = ' . $id;
	$cultures->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'cultures');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$cultures = new Cultures();
	$this->view->culture = $cultures->fetchRow('id=' . $id);
	}
	}
	}
	/** List workflows in use
	*/	
	public function workflowsAction(){
	$workflows = new Workflows();
	$this->view->workflows = $workflows->getStageNamesAdmin();
	}
	/** Add a new workflow stage
	*/
	public function addworkflowAction() {
	$form = new WorkflowForm();
	$form->submit->setLabel('Add a new workflow stage to the system...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$workflows = new Workflows();
	$insertData = array( 
	'workflowstage' => $form->getValue('workflowstage'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert= $workflows->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'workflows');
	$this->_flashMessenger->addMessage('A new workflow stage has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a workflow stage
	*/	
	public function editworkflowAction() {
	if($this->_getParam((int)'id',false)) {
	$form = new WorkflowForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$workflows = new Workflows();
	$where = array();
	$where[] = $workflows->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array( 
	'workflowstage' => $form->getValue('workflowstage'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update= $workflows->update($updateData,$where);
	$this->_flashMessenger->addMessage('Active workflow stage information updated!');
	$this->_redirect($this->_redirectUrl . 'workflows');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$workflows = new Workflows();
	$workflow = $workflows->fetchRow('id=' . $id);
	if(count($workflow))
	{
	$form->populate($workflow->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a workflow stage
	*/
	public function deleteworkflowAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$workflows = new Workflows();
	$where = 'id = ' . $id;
	$workflows->delete($where);
	}
	$this->_redirect($this->_redirectUrl . 'workflows');
	$this->_flashMessenger->addMessage('Record deleted!');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$workflows = new Workflows();
	$this->view->workflow = $workflows->fetchRow('id=' . $id);
	}
	}
	}
	/** List preservation states
	*/	
	public function preservationsAction() {
	$preserves = new Preservations();
	$this->view->preserves = $preserves->getPreservationTermsAdmin();
	}
	/** Add a new preservation state
	*/
	public function addpreservationAction() {
	$form = new PreservationsForm();
	$form->submit->setLabel('Add a new discovery method');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$preserves = new Preservations();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $preserves->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'preservations');
	$this->_flashMessenger->addMessage('Preservation state created!');
	} else {
	$this->_flashMessenger->addMessage('Please correct errors');
	$form->populate($formData);
	}
	}
	}
	/** Edit a preservation state
	*/
	public function editpreservationAction() {
	if($this->_getParam('id',false)) {
	$form = new PreservationsForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$preserves = new Preservations();
	$where = array();
	$where[] = $preserves->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $preserves->update($updateData, $where);
	$this->_flashMessenger->addMessage('Preservation state information updated!');
	$this->_redirect($this->_redirectUrl . 'preservations');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$preserves = new Preservations();
	$preserve = $preserves->fetchRow('id=' . $id);
	if(count($preserve)) {
	$form->populate($preserve->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a preservation state
	*/
	public function deletepreservationAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$preserves = new Preservations();
	$where = 'id = ' . $id;
	$preserves->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'preservations');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$preserves = new Preservations();
	$this->view->preserve = $preserves->fetchRow('id=' . $id);
	}
	}
	}
	/** List the types of grid reference origins
	*/	
	public function maporiginsAction() {
	$origins = new MapOrigins();
	$this->view->origins = $origins->getOrigins();
	}	
	/** Add a map origin statement
	*/
	public function addmaporiginAction() {
	$form = new OriginForm();
	$form->details->setLegend('Grid reference origin details: ');
	$form->submit->setLabel('Add a grid ref. origin term.');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$origins = new MapOrigins();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	$origins->insert($insertData);
	$this->_flashMessenger->addMessage('A new grid reference origin has been entered');
	$this->_redirect($this->_redirectUrl . 'maporigins');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a map origin statement
	*/	
	public function editmaporiginAction() {
	if($this->_getParam('id',false)) {
	$form = new OriginForm();
	$form->details->setLegend('Edit an origin term');
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$origins = new MapOrigins();
	$where = array();
	$where[] = $origins->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'),
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	$origins->update($updateData,$where);
	$this->_redirect($this->_redirectUrl . 'maporigins');
	$this->_flashMessenger->addMessage('Grid reference origin updated!');
	} else {
	$form->populate($formData);
	}
	} else  {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$origins = new MapOrigins();
	$origin = $origins->fetchRow('id = ' . $this->_getParam('id'));
	if(count($origin) != NULL )
	{
	$form->populate($origin->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParamter);
	}
	}
	/** Delete a map origin statement
	*/
	public function deletemaporiginAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$origins = new MapOrigins();
	$where = 'id = ' . $id;
	$origins->delete($where);
	}
	$this->_redirect($this->_redirectUrl . 'maporigins/');
	$this->_flashMessenger->addMessage('Map origin deleted!');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$origins = new MapOrigins();
	$this->view->origin = $origins->fetchRow('id =' . $id);
	}
	}
	}
	/** List finds of note methods
	*/	
	public function notesAction(){
	$notes = new Findofnotereasons();
	$this->view->notes = $notes->getReasonsListAdmin();
	}
	/** Add a find of note reasoning
	*/	
	public function addnoteAction() {
	$form = new FindNoteReasonForm();
	$form->submit->setLabel('Add a new reason...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$notes = new Findofnotereasons();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $notes->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'notes');
	$this->_flashMessenger->addMessage('Preservation state created!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit find of note statement
	*/	
	public function editnoteAction() {
	if($this->_getParam('id',false)) {
	$form = new FindNoteReasonForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$notes = new Findofnotereasons();
	$where = array();
	$where[] = $notes->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $notes->update($updateData, $where);
	$this->_flashMessenger->addMessage('Find of note reasoning information updated!');
	$this->_redirect($this->_redirectUrl . 'notes');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$notes = new Findofnotereasons();
	$note = $notes->fetchRow('id=' . $id);
	if(count($note))
	{
	$form->populate($note->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParamter);
	}
	}
	/** Delete a find of note statement
	 * 
	 */
	public function deletenoteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$notes = new Findofnotereasons();
	$where = 'id = ' . $id;
	$notes->delete($where);
	$this->_flashMessenger->addMessage('Find of note reason deleted!');
	}
	$this->_redirect($this->_redirectUrl . 'notes');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$notes = new Findofnotereasons();
	$this->view->note = $notes->fetchRow('id=' . $id);
	}
	}
	}
	/** List primary materials
	*/	
	public function materialsAction() {
	$materials = new Materials();
	$this->view->materials = $materials->getMaterialsAdmin($this->_getParam('page'));
	}
	/** Add a new primary material
	*/
	public function addmaterialAction() {
	$form = new MaterialForm();
	$form->submit->setLabel('Add a new materials...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$materials = new Materials();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $materials->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'materials');
	$this->_flashMessenger->addMessage('A new material has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a material type
	*/	
	public function editmaterialAction() 	{
	if($this->_getParam('id',false)) {
	$form = new MaterialForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$materials = new Materials();
	$where = array();
	$where[] = $materials->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $materials->update($updateData, $where);
	$this->_flashMessenger->addMessage('Material information updated!');
	$this->_redirect($this->_redirectUrl . 'materials');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$materials = new Materials();
	$material = $materials->fetchRow('id=' . $id);
	if(count($material)) {
	$form->populate($material->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
	/** Delete a material
	*/	
	public function deletematerialAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$materials = new Materials();
	$where = 'id = ' . $id;
	$materials->delete($where);
	}
	$this->_flashMessenger->addMessage('You have deleted this material term');
	$this->_redirect($this->_redirectUrl . 'materials');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$materials = new Materials();
	$this->view->material = $materials->fetchRow('id=' . $id);
	}
	}
	}
	/** List decorative styles
	*/	
	public function decorationstylesAction() {
	$decs = new Decstyles();
	$this->view->decs = $decs->getDecStylesAdmin();
	}
	/** Add a decorative style
	*/
	public function adddecorationstyleAction() {
	$form = new DecStylesForm();
	$form->submit->setLabel('Add a new term');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$decs = new Decstyles();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $decs->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'decorationstyles');
	$this->_flashMessenger->addMessage('A new decoration style has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a decorative style
	*/
	public function editdecorationstyleAction() { 
	if($this->_getParam('id',false)) {
	$form = new DecStylesForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$decs = new Decstyles();
	$where = array();
	$where[] = $decs->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $decs->update($updateData, $where);
	$this->_flashMessenger->addMessage('Decoration style information updated!');
	$this->_redirect($this->_redirectUrl . 'decorationstyles');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$decs = new Decstyles();
	$dec = $decs->fetchRow('id=' . $id);
	if(count($dec)){
	$form->populate($dec->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Delete a decorative style
	*/	
	public function deletedecorationstyleAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$decs = new Decstyles();
	$where = 'id = ' . $id;
	$decs->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'decorationstyles');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$decs = new Decstyles();
	$this->view->dec = $decs->fetchRow('id=' . $id);
	}
	}
	}
	/** List manufacture methods
	*/	
	public function manufacturesAction() {
	$manufactures = new Manufactures();
	$this->view->manufactures = $manufactures->getManufacturesListedAdmin();
	}

	/** Add a manufacture methods
	*/
	public function addmanufactureAction() {
	$form = new ManufacturesForm();
	$form->submit->setLabel('Add a new method of manufacture...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$manufactures = new Manufactures();
	$insertData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $manufactures->insert($insertData);
	$this->_redirect($this->_redirectUrl . 'manufactures');
	$this->_flashMessenger->addMessage('A new manufacturing method has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a manufacture method
	*/	
	public function editmanufactureAction() {
	if($this->_getParam('id',false)) {
	$form = new ManufacturesForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$manufactures = new Manufactures();
	$where = array();
	$where[] = $manufactures->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $manufactures->update($updateData, $where);
	$this->_flashMessenger->addMessage('Manufacture information updated!');
	$this->_redirect($this->_redirectUrl . 'manufactures');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$manufactures = new Manufactures();
	$manufacture = $manufactures->fetchRow('id=' . $id);
	if(count($manufacture))
	{
	$form->populate($manufacture->toArray());
	} else {
	throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
	throw new Exception($this->_missingParameter);
	}
	}
	/** Delete a manufacture method
	*/	
	public function deletemanufactureAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$manufactures = new Manufactures();
	$where = 'id = ' . $id;
	$manufactures->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl.'manufactures');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$manufactures = new Manufactures();
	$this->view->manufacture = $manufactures->fetchRow('id='.$id);
	}
	}
	}

	/** List landuses
	*/	
	public function landusesAction() {
	$landuses = new Landuses();
	$this->view->landuses = $landuses->getLandusesAdmin();
	}

	/** Add a new landuse
	*/
	public function addlanduseAction() {
	$form = new LanduseForm();
	$form->details->setLegend('Add landuse');
	$form->submit->setLabel('Add a new landuse to the system...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$landuses = new Landuses();
	$updateData = array(
	'oldID' => $form->getValue('oldID'),
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'belongsto' => $form->getValue('belongsto'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$update = $landuses->insert($updateData);
	$this->_redirect($this->_redirectUrl . 'landuses');
	$this->_flashMessenger->addMessage('A new landuse has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a landuse
	*/	
	public function editlanduseAction() {
	if($this->_getParam('id',false)) {
	$form = new LanduseForm();
	$form->details->setLegend('Edit landuse');
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$landuses = new Landuses();
	$where = array();
	$where[] = $landuses->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$updateData = array(
	'oldID' => $form->getValue('oldID'),
	'term' => $form->getValue('term'),
	'termdesc' => $form->getValue('termdesc'), 
	'valid' => $form->getValue('valid'),
	'belongsto' => $form->getValue('belongsto'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$update = $landuses->update($updateData, $where);
	$this->_flashMessenger->addMessage('Active landuse information updated!');
	$this->_redirect($this->_redirectUrl . 'landuses');
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$landuses = new Landuses();
	$landuse = $landuses->fetchRow('id=' . $id);
	if(count($landuse))
	{
	$form->populate($landuse->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
	/** Delete a landuse
	*/	
	public function deletelanduseAction() {
	$this->_flashMessenger->addMessage($this->_noChange);
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$landuses = new Landuses();
	$where = 'id = ' . $id;
	$landuses->delete($where);
	}
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect($this->_redirectUrl . 'landuses');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$landuses = new Landuses();
	$this->view->landuse = $landuses->fetchRow('id=' . $id);
	}
	}
	}	

}
