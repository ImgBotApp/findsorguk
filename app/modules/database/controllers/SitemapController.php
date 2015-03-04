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
class Database_SitemapController extends Pas_Controller_Action_Admin
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
        $xml = $this->checkXml($this->view->serverUrl() . '/sitemap/configurations/database', 'database.xml');
        $config = new Zend_Config_Xml($xml, 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
    }

    public function databaseAction()
    {

    }

    public function recordsAction()
    {
        $xml = $this->checkXml($this->view->serverUrl() . '/sitemap/configurations/records/page/' . $this->getParam('page'), 'records' . $this->getParam('page') .  '.xml');
        $config = new Zend_Config_Xml($xml, 'nav');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
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

    public function imagesAction()
    {
        $xml = $this->checkXml($this->view->serverUrl() . '/sitemap/configurations/images', 'images.xml');
        $config = new Zend_Config_Xml($xml, 'locations');
        $navigation = new Zend_Navigation($config);
        $this->view->navigation($navigation);
    }

    public function checkXml($url, $filename)
    {
        $key = md5($filename);
        if(!is_dir(SITEMAP_PATH)){
            mkdir(SITEMAP_PATH, 0777);
        }
        $name = SITEMAP_PATH . $filename;
        if(!$this->getCache()->test($key) || !file_exists($name)) {
            $file = file_get_contents($url);
            file_put_contents($name, $file);
            $this->getCache()->save($file);
        }
        return $name;
    }
}