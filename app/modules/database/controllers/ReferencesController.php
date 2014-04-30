<?php 
/** Controller for CRUD of references on database
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_ReferencesController extends Pas_Controller_Action_Admin {
	
	protected $_bibliography;
	
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$publicActions = array('index');
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('member',array('add','edit','delete'));
	$this->_helper->_acl->allow('public',$publicActions);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_bibliography = new Bibliography();
	}
	
	/** Constant for redirect url
	*/	
	const REDIRECT = 'database/artefacts/record/id/';
	
	/** No direct access to the references controller, redirect applied.
	*/
	public function indexAction(){
	$this->_redirect('/database/publications');
	}
	
	
	/** Adding a reference
	*/
	public function addAction(){
	$form = new ReferenceFindForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = $form->getValues();
	$insertData['findID'] = $this->_getParam('secID');
	unset($insertData['authors']);
	$insert = $this->_bibliography->add($insertData);
	$this->_flashMessenger->addMessage('A new reference work has been added to this record');
	$this->_redirect(self::REDIRECT . $this->_getParam('findID'));
	} else {
	$form->populate($formData);
	}
	}
	}
	
	/** Edit a reference entity
	*/
	public function editAction() {
	$form = new ReferenceFindForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
			unset($formData['authors']);
			unset($formData['submit']);
	$where = array();
	$where =  $this->_bibliography->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_bibliography->update($formData, $where);
	$this->_flashMessenger->addMessage('Reference details updated!');
	$this->_redirect(self::REDIRECT . $this->_getParam('findID'));	
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$bib = $this->_bibliography->fetchFindBook($id);
	$form->populate($bib['0']);
	$pubs = new Publications();
	$titles = $pubs->getTitlesPairs($bib[0]['authors']);
	$form->pubID->addMultiOptions($titles);
	$form->pubID->setValue($bib[0]['pubID']);
	}
	}
	}

	/** Delete a reference
	*/
	public function deleteAction() {
	if($this->_getParam('id',false)) {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$findID = (int)$this->_request->getPost('findID');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = array();
	$where =  $this->_bibliography->getAdapter()->quoteInto('id = ?', $id);
	$this->_bibliography->delete($where);
	$this->_flashMessenger->addMessage('Reference deleted!');
	$this->_redirect(self::REDIRECT . $findID);	
	}
	} else {
	$id = (int)$this->_getParam('id');
	if ($id > 0) {
	$this->view->id = $id;
	$this->view->bib = $this->_bibliography->fetchFindBook($id);
	}
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
}