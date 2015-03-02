<?php
/** Controller for displaying site maps
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Zend_Config_Xml
 * @uses Zend_Navigation
 *
 */
class Datalabs_SitemapController extends Pas_Controller_Action_Admin
{

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow(null);
        $this->_helper->layout->disableLayout();
        $this->getResponse()->setHeader('Content-type', 'application/xml');
    }

    /** The default sitemap
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $xml = $this->checkXml($this->view->serverUrl() . '/sitemap/configurations/datalabs', 'datalabs.xml');
        $config = new Zend_Config_Xml($xml, 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
    }

    public function databaseAction()
    {

    }

    public function publicationsAction()
    {
        $xml = $this->checkXml($this->view->serverUrl() . '/sitemap/configurations/publications', 'publications.xml');
        $config = new Zend_Config_Xml($xml, 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
    }

    public function ralliesAction()
    {
        $xml = $this->checkXml($this->view->serverUrl() . '/sitemap/configurations/rallies', 'rallies.xml');
        $config = new Zend_Config_Xml($xml, 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
    }

    public function checkXml($url, $filename)
    {
        $key = md5($url);
        $name = APPLICATION_PATH . '/config/sitemaps/' . $filename;
        if(!$this->getCache()->test($key)) {
            $file = file_get_contents($url);
            file_put_contents($name, $file);
        }
        return $name;
    }
}