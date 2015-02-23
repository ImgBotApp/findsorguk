<?php

/** A controller for manipulating content for the Secret Treasure module
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 */
class Sitemap_IndexController extends Pas_Controller_Action_Admin
{

    /** The init function
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->acl->allow(null);
//        $this->_helper->layout->disableLayout();
//        $this->getResponse()->setHeader('Content-type', 'application/xml');
    }

    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $config = new Zend_Config_Xml('http://finds.org.uk/sitemap/configurations/database/', 'configdata');
        Zend_Debug::dump($config);
//        $navigation = new Zend_Navigation($config);
//        $this->view->navigation($navigation);
//        $this->view->navigation()->sitemap()->setFormatOutput(true);
    }
}