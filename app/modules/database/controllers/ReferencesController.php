<?php

/** Controller for CRUD of references on database
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Bibliography
 * @uses ReferenceFindForm
 * @uses Pas_Exception_Param
 * @uses Publications
 */
class Database_ReferencesController extends Pas_Controller_Action_Admin
{

    /** The bibliography model
     * @access protected
     * @var \Bibliography
     */
    protected $_bibliography;

    /** Initialise the ACL and contexts
     */
    public function init()
    {
        $publicActions = array('index');
        $this->_helper->_acl->allow('flos', null);
        $this->_helper->_acl->allow('member', array('add', 'edit', 'delete'));
        $this->_helper->_acl->allow('public', $publicActions);
        $this->setController($this->getParam('recordtype', 'artefacts'));
        $this->setRedirect($this->getController());
        $this->_bibliography = new Bibliography();
    }

    /** Constant for redirect url
     */
    const REDIRECT = 'database/artefacts/record/id/';

    /** The controller to redirect to on completion of action
     * @access protected
     * @var \Findspots
     */
    protected $_controller;

    /** The redirect URL to go to on completion of action
     * @access protected
     * @var \Findspots
     */
    protected $_redirect;

    /** Set the controller to redirect to on completion of action
     * @access public
     * @param string $recordtype
     * @return \Findspots
     */
    public function setController($recordtype)
    {
        $this->_controller = $recordtype;
        return $this;
    }

    /** Set the redirect URL to go to on completion of action
     * @access public
     * @return \Findspots
     */
    public function setRedirect($controller)
    {
        $module = '/database/';
        $this->_redirect = $module . $controller . '/';
        return $this;
    }

    /** Get the controller to redirect to on completion of action
     * @access public
     * @return string
     */
    public function getController()
    {
        return $this->_controller;
    }

    /** Get the redirect URL to go to on completion of action
     * @access public
     * @return string
     */
    public function getRedirect()
    {
        return $this->_redirect;
    }

    /** No direct access to the references controller, redirect applied.
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('No access to root file for reference');
        $this->getResponse()->setHttpResponseCode(301)->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('/database/publications');
    }


    /** Adding a reference
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new ReferenceFindForm();
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                $insertData = $form->getValues();
                $insertData['findID'] = $this->getParam('secID');
                unset($insertData['authors']);
                $this->_bibliography->add($insertData);
                $this->getFlash()->addMessage('A new reference work has been added to this record');
                $this->redirect($this->getRedirect() . 'record/id/' . $this->getParam('findID'));
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Edit a reference entity
     * @access public
     * @return void
     */
    public function editAction()
    {
        $form = new ReferenceFindForm();
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {
                unset($formData['authors']);
                unset($formData['submit']);
                $where = array();
                $where[] = $this->_bibliography->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                $this->_bibliography->update($formData, $where);
                $this->getFlash()->addMessage('Reference details updated!');
                $this->redirect($this->getRedirect() . 'record/id/' . $this->getParam('findID'));
            } else {
                $form->populate($this->_request->getPost());
            }
        } else {
            $id = (int)$this->_request->getParam('id', 0);
            if ($id > 0) {
                $bib = $this->_bibliography->fetchFindBook($id, $this->getController());
                $form->populate($bib['0']);
                $pubs = new Publications();
                $titles = $pubs->getTitlesPairs($bib[0]['authors']);
                $form->pubID->addMultiOptions($titles);
                $form->pubID->setValue($bib[0]['pubID']);
            }
        }
    }

    /** Delete a reference
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if ($this->getParam('id', false)) {
            if ($this->_request->isPost()) {
                $id = (int)$this->_request->getPost('id');
                $findID = (int)$this->_request->getPost('findID');
                $this->setController($this->_request->getPost('controller'));
                $this->setRedirect($this->getController());
                $del = $this->_request->getPost('del');
                if ($del == 'Yes' && $id > 0) {
                    $where = array();
                    $where[] = $this->_bibliography->getAdapter()->quoteInto('id = ?', $id);
                    $this->_bibliography->delete($where);
                    $this->getFlash()->addMessage('Reference deleted!');
                    $this->redirect($this->getRedirect() . 'record/id/' . $findID);
                }
            } else {
                $id = (int)$this->getParam('id');
                if ($id > 0) {
                    $this->view->id = $id;
                    $this->view->bib = $this->_bibliography->fetchFindBook($id, $this->getController());
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

}