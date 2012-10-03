<?php
/**
 * A view helper to display the meta data for a page
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_MetaBase
	extends Zend_View_Helper_Abstract {

    /** View helper to produce metadata for the head section
     * @access public
     * @param $description
     * @param $subject
     * @param $keywords array
     */
    public function metabase($description, $subject = 'archaeology', $keywords) {
    $date = new Zend_Date();
    $date->add('72',Zend_Date::HOUR);
    $this->view->headMeta()
        ->appendHttpEquiv('expires',$date->get(Zend_Date::RFC_1123))
        ->appendHttpEquiv('Content-Type','text/html; charset=UTF-8')
        ->appendHttpEquiv('Content-Language', 'en-GB')
        ->appendHttpEquiv('imagetoolbar', 'no')
        ->headMeta($this->view->title(),'title')
        ->headMeta($this->view->curUrl(),'dc.identifier')
        ->headMeta($this->view->curUrl(),'og:url')
        ->headMeta($keywords,'keywords')
        ->headMeta('The Portable Antiquities Scheme and the British Museum',
                'dc.publisher')
        ->headMeta($this->view->ellipsisstring(strip_tags($description),170),'description')
        ->headMeta()->setProperty('dc.subject',strip_tags($subject))
        ->headMeta()->setProperty('og:site_name','Portable Antiquities')
        ->headMeta()->setProperty('dc.rights','Creative Commons BY-SA');
    $this->view->headMeta('CC BY-SA','dc.rights');
    $this->view->headRdf($this->view->curUrl(),'og:url');
    $this->view->headRdf($this->view->ellipsisstring(strip_tags($description),170),'og:description');
    $this->view->headRdf('http://profile.ak.fbcdn.net/hprofile-ak-snc4/41813_6875839761_3304_q.jpg','og:image');
    $this->view->headRdf('non_profit','og:type');
    $this->view->headRdf($this->view->title(),'og:title');
    $this->view->headRdf('The Portable Antiquities Scheme','og:site_name');
    $this->view->headRdf('688811070','fb:admins');
    $this->view->headRdf('166174266729252','fb:app_id');
    $this->view->headLink(array(
            'rel' => 'foaf:primaryTopic',
            'href' => $this->view->curUrl() . '#this',
            'APPEND'));
    $this->view->headLink(array(
            'rel' => 'canonical',
            'href' => $this->view->curUrl(),
            'APPEND'));
    }

}