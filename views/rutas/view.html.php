<?php

/**
 * @version     1.0.0
 * @package     com_flota
 * @copyright   Copyright (C) 2015. Todos los derechos reservados.
 * @license     Licencia Pública General GNU versión 2 o posterior. Consulte LICENSE.txt
 * @author      Ricardo Casas <contacto@influenciaweb.com> - http://www.influenciaweb.com
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Flota.
 */
class FlotaViewRutas extends JViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;
    protected $params;

    /**
     * Display the view
     */
    public function display($tpl = null) {
        $this->resultados_ida     = null;
        $this->resultados_regreso = null;

        $app     = JFactory::getApplication();
        $session = JFactory::getSession();

        if($app->input->get->get('tipo_viaje', '', 'INT')){
            $tiquete['origen']        = $app->input->get->get('origen', '', 'INT');;
            $tiquete['destino']       = $app->input->get->get('destino', '', 'INT');
            $tiquete['fecha_salida']  = $app->input->get->get('fecha_salida', '', 'CMD');
            $tiquete['fecha_regreso'] = $app->input->get->get('fecha_regreso', '', 'CMD');
            $tiquete['tipo_viaje']    = $app->input->get->get('tipo_viaje', '', 'INT');
            $tiquete['pasajeros']     = $app->input->get->get('pasajeros','','INT');
            $session->set('tiquete', $tiquete);
        }elseif($session->get('tiquete')){
            $tiquete = $session->get('tiquete');
        }

        if($app->input->getCmd('layout','default')!="login"){
            $ObRuta  = $this->getModel();
        }

        if($app->input->getCmd('layout','default')=="default"){
             $ObRuta  = $this->getModel();
            $this->municipio_origen  = $ObRuta->getMunicipio($tiquete['origen']);
            $this->municipio_destino = $ObRuta->getMunicipio($tiquete['destino']); 
            $this->resultados_ida    = $ObRuta->getRutasDisponibles(1,$tiquete);
            if($tiquete['tipo_viaje']==2){
                $this->resultados_regreso = $ObRuta->getRutasDisponibles(2,$tiquete);
            }
        }elseif($app->input->getCmd('layout','default')=="sillas"){
            
            if($app->input->post->get('tiquete_ida','','INT')){
                $this->ruta_ida     = $ObRuta->getRuta($app->input->post->get('tiquete_ida','','INT'),$tiquete['fecha_salida']);
                $this->ruta_regreso = ($tiquete['tipo_viaje']==2) ? $ObRuta->getRuta($app->input->post->get('tiquete_regreso','','INT'),$tiquete['fecha_regreso']) : null;

                $tiquete['ruta_ida']      = $app->input->post->get('tiquete_ida','','INT');
                $tiquete['ruta_regreso']  = ($tiquete['tipo_viaje']==2) ? $app->input->post->get('tiquete_regreso','','INT') : null;
                $tiquete['dispositivo']   = ($app->input->post->get('dispositivo','','INT')) ? $app->input->post->get('dispositivo','','INT') : 0;
                            
                $precio_ida = $this->displayPrice($this->ruta_ida,$tiquete['fecha_salida'] ,2);

                if($this->ruta_regreso)
                    $precio_reg = $this->displayPrice($this->ruta_regreso,$tiquete['fecha_regreso'],2 );
                else
                    $precio_reg = 0;

                $total_ida = $precio_ida*$tiquete['pasajeros'];
                $tiquete['total_ida'] = $total_ida; 
                $total_reg = ($this->ruta_regreso) ? ($precio_reg*$tiquete['pasajeros'])  : 0;
                $tiquete['total_reg'] = $total_reg; 

                $tiquete['total']       = $total_ida + $total_reg;
                $tiquete['subtotal']    = $tiquete['total'];
                $session->set('tiquete', $tiquete);
            }else{
                $this->ruta_ida     = $ObRuta->getRuta($tiquete['ruta_ida'], $tiquete['fecha_salida']);
                $this->ruta_regreso = ($tiquete['tipo_viaje']==2) ? $ObRuta->getRuta($tiquete['ruta_regreso'], $tiquete['fecha_regreso']) : null;
            }
            

        }elseif($app->input->getCmd('layout','default')=="login"){
            $this->item   = $app->getUserStateFromRequest( "com_flota.edit.cliente.data","data" );
            if($app->input->post->get('sillas','','ARRAY')){
                $tiquete = $session->get('tiquete');
                $tiquete['sillas_ida'] = implode(",",$app->input->post->get('sillas','','ARRAY'));
                $tiquete['sillas_regreso'] = (($tiquete['fecha_regreso'] != null)&& ($app->input->post->get('sillasr','','ARRAY')!=null)) ? implode(",", $app->input->post->get('sillasr','','ARRAY')) : null;
                
                $session->set('tiquete', $tiquete );
            }
        }

        
        $this->tiquete       = $tiquete;
        $this->state         = $this->get('State');
        $this->pagination    = $this->get('Pagination');
        $this->params        = $app->getParams('com_flota');
        $this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
;
            throw new Exception(implode("\n", $errors));
        }

        $this->_prepareDocument();
        parent::display($tpl);
    }

    public function asignarSilla($tiquete,$tipo=1){
        $ruta  = $tiquete['ruta_ida'];
        $fecha = $tiquete['fecha_salida'];
        if($tipo!=1){
            $fecha = $tiquete['fecha_regreso'];
            $ruta  = $tiquete['ruta_regreso'];
        } 
        $ObRuta             = $this->getModel();           
       
        $sillas             = ($ObRuta->getSillasRuta($ruta)) ? explode(",",$ObRuta->getSillasRuta($ruta)->sillas) : array();
        $sillas_ocupadas    = explode(",",$ObRuta->getSillasOcupadas($ruta,$fecha));
        $sillas_bloqueadas  = ($ObRuta->getSillasBloqueadas($ruta,$fecha)) ? explode(",",$ObRuta->getSillasBloqueadas($ruta,$fecha)->sillas) : array();
        $sillas_no_dis      = array_merge($sillas_ocupadas,$sillas_bloqueadas);
        $sillas_disponibles = array();
        $sillas_asignadas   = array();
        $i                  = 0;
        
        foreach ($sillas as $value) {
            $sillas_disponibles[$i] = $value;
            foreach ($sillas_no_dis as $ocupadas) {
                if($value==$ocupadas)
                    array_splice($sillas_disponibles, $i,1);
            }
            $i++;
        }

        for($j=0;$j<$tiquete['pasajeros'];$j++){
            $sillas_asignadas[] = array_pop($sillas_disponibles);
        }

        return implode(",",$sillas_asignadas);

    }

    public function getSillasDisponibles($tiquete,$tipo=1){
        $ruta  = $tiquete['ruta_ida'];
        $fecha = $tiquete['fecha_salida'];
        if($tipo!=1){
            $fecha = $tiquete['fecha_regreso'];
            $ruta  = $tiquete['ruta_regreso'];
        }
        $ObRuta             = $this->getModel();           
       
        $sillas             = ($ObRuta->getSillasRuta($ruta)) ? explode(",",$ObRuta->getSillasRuta($ruta)->sillas_disponibles) : array();
        $sillas_ocupadas    = explode(",",$ObRuta->getSillasOcupadas($ruta,$fecha));
        $sillas_bloqueadas  = ($ObRuta->getSillasBloqueadas($ruta,$fecha)) ? explode(",",$ObRuta->getSillasBloqueadas($ruta,$fecha)->sillas) : array();
        $sillas_no_dis      = array_merge($sillas_ocupadas,$sillas_bloqueadas);
        $sillas_disponibles = array();
        $i                  = 0;
        //echo '<pre>';
        //echo "ruta".$ruta." fecha".$fecha;
       // var_dump($sillas_bloqueadas);
      //  echo '</pre>';
       
        foreach ($sillas as $value) {
            $sillas_disponibles[$i] = $value;
            foreach ($sillas_no_dis as $ocupadas) {
                if($value==$ocupadas){
                               
                    array_splice($sillas_disponibles, $i,1);
                    $i--;

                }
            }
            $i++;
        }

        return $sillas_disponibles;
    }

    public function getSillas($ruta = null, $orientacion,$fila){
        $sillas = array();
        if($ruta!=null){
            $ObRuta  = $this->getModel();           
            if($orientacion=="I"){
                if($fila==1){
                    $sillas  = ($ObRuta->getSillasRuta($ruta)) ? explode(",",$ObRuta->getSillasRuta($ruta)->sillas_izquierda) : array();
                }else{
                    $sillas  = ($ObRuta->getSillasRuta($ruta)) ? explode(",",$ObRuta->getSillasRuta($ruta)->sillas_izquierda2) : array();
                }
            }else{
                if($fila==1){
                    $sillas  = ($ObRuta->getSillasRuta($ruta)) ? explode(",",$ObRuta->getSillasRuta($ruta)->sillas_derecha) : array();
                }else{
                   $sillas  = ($ObRuta->getSillasRuta($ruta)) ? explode(",",$ObRuta->getSillasRuta($ruta)->sillas_derecha2) : array(); 
                }
                
            }
        }
        
        return $sillas;
    }

    public function getCantidadSillas($ruta=null, $fecha=null){
        if(($ruta!=null)&&($fecha!=null)){
            $ObRuta = $this->getModel();
            $sillas_ocupadas = $ObRuta->getCountSillasDisponibles($ruta, $fecha)->sillas;
            $sillas_disabled = $ObRuta->getCountSillasBloqueadas($ruta, $fecha)->sillas;
            return $sillas_ocupadas+$sillas_disabled;
        }
        return 0;
    }

    public function displayPrice($item,$fecha, $format=1){
        if($item->precio_especial>0){
            return  ($format==2) ? $item->precio_especial : '$'.number_format($item->precio_especial,0,"",".");
        }elseif(date("N", strtotime($fecha))>=5 ){
            return ($format==2) ? $item->precio_finsemana : '$'.number_format($item->precio_finsemana,0,"",".");
        }else{
            return ($format==2) ? $item->precio : '$'.number_format($item->precio,0,"",".");
        }
    }

    public function displaySilla($silla,$seleccionadas,$tiquete,$pre=null){
        $checked = (in_array($silla, $seleccionadas)) ? 'checked="checked"' : '';
        if(in_array($silla, $this->getSillasDisponibles($tiquete))){
            return '<div class="checkbox"><input type="checkbox" class="radio sillai" '.$checked.' name="sillas[]" value="'.$silla.'" id="'.$silla.'" />
                        <label for="'.$silla.'">'.$silla.'</label></div>';
        }else{
            return '<div class="checksilla"><label>'.$silla.'</label></div>';
        }
    }

    public function displaySillaReg($silla,$seleccionadas,$tiquete){
        $checked = (in_array($silla, $seleccionadas)) ? 'checked="checked"' : '';
        if(in_array($silla, $this->getSillasDisponibles($tiquete,2))){
            return '<div class="checkbox"><input type="checkbox" class="radio sillareg" '.$checked.' name="sillasr[]" value="'.$silla.'" id="r'.$silla.'" />
                        <label for="r'.$silla.'">'.$silla.'</label></div>';
        }else{
            return '<div class="checksilla"><label>'.$silla.'</label></div>';
        }
    }

    /**
     * Prepares the document
     */
    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $title = null;

        // Because the application sets a default page title,
        // we need to get it from the menu item itself
        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_FLOTA_DEFAULT_PAGE_TITLE'));
        }
        $title = $this->params->get('page_title', '');
        if (empty($title)) {
            $title = $app->get('sitename');
        } elseif ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }
        $this->document->setTitle($title);

        if ($this->params->get('menu-meta_description')) {
            $this->document->setDescription($this->params->get('menu-meta_description'));
        }

        if ($this->params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        }

        if ($this->params->get('robots')) {
            $this->document->setMetadata('robots', $this->params->get('robots'));
        }
    }
    
    public function getState($state) {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }

}