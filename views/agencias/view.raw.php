<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class FlotaViewAgencias extends JViewLegacy{

    function display($tpl = null){
        $this->agencias = $this->get('Items');
        $this->setLayout('agencias');
        parent::display($tpl);
    }
}