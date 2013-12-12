<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );


class sportsmanagementViewDatabaseTool extends JView
{
	function display( $tpl = null )
	{
		$db		= JFactory::getDBO();
		$uri	= JFactory::getURI();
        $model	= $this->getModel();
        $option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();
        $document = JFactory::getDocument();
        
        $this->task = JRequest::getCmd('task');
        //$post = JRequest::get( 'post' );
        $this->step = $mainframe->getUserState( "$option.step", '0' );
        
        if ( !$this->step )
        {
            $this->step = 0;
        }

		$this->assign('request_url',$uri->toString() );
        
        switch ($this->task)
        {
            case 'truncate':
            case 'optimize':
            case 'repair':
            $this->assign('sm_tables',$model->getSportsManagementTables() );
            $this->assign('totals',count($this->sm_tables) );
            if ( $this->step < count($this->sm_tables) )
            {
            $successTable = $model->setSportsManagementTableQuery($this->sm_tables[$this->step], $this->task);    
            $this->work_table = $this->sm_tables[$this->step];
            $this->bar_value = round( ( $this->step * 100 / $this->totals ), 0);
            }
            else
            {
            $this->step = 0;    
            $this->bar_value = 100;
            $this->work_table = '';
            }
            
            
            
            
$javascript = "\n";            
$javascript .= '            jQuery(function() {' . "\n"; 
$javascript .= '    var progressbar = jQuery( "#progressbar" ),' . "\n"; 

$javascript .= '      progressLabel = jQuery( ".progress-label" );' . "\n"; 



$javascript .= '     progressbar.progressbar({' . "\n"; 
//$javascript .= '      value: false,' . "\n"; 
$javascript .= '      value: '.$this->bar_value.',' . "\n";

$javascript .= '      create: function() {' . "\n"; 
$javascript .= '        progressLabel.text( "'.$this->task.' -> " + progressbar.progressbar( "value" ) + "%" );' . "\n"; 
$javascript .= '      },' . "\n";

$javascript .= '      change: function() {' . "\n"; 
$javascript .= '        progressLabel.text( progressbar.progressbar( "value" ) + "%" );' . "\n"; 
$javascript .= '      },' . "\n"; 

$javascript .= '      complete: function() {' . "\n"; 
$javascript .= '        progressLabel.text( "Complete!" );' . "\n"; 
$javascript .= '      }' . "\n"; 

$javascript .= '    });' . "\n"; 
$javascript .= '     function progress() {' . "\n"; 
$javascript .= '      var val = progressbar.progressbar( "value" ) || 0;' . "\n"; 
$javascript .= '       progressbar.progressbar( "value", '.$this->bar_value.' );' . "\n";
$javascript .= '       if ( val < 99 ) {' . "\n"; 

$javascript .= '        setTimeout( progress, 100 );' . "\n"; 
$javascript .= '      }' . "\n"; 
$javascript .= '    }' . "\n"; 
$javascript .= '     setTimeout( progress, 3000 );' . "\n"; 
$javascript .= '  });' . "\n"; 
$document->addScriptDeclaration( $javascript );            
            
            $this->step++;
            $mainframe->setUserState( "$option.step", $this->step); 
            break;
        }
        
        // Load mooTools
		//JHtml::_('behavior.framework', true);
        
        // Load our Javascript
        $document->addStylesheet(JURI::base().'components/'.$option.'/assets/css/progressbar.css');
        //$document->addScript(JURI::base().'components/'.$option.'/assets/js/progressbar.js');

/*        
        // Load our Javascript
		$document = JFactory::getDocument();
		$document->addScript('../media/com_joomlaupdate/json2.js');
		$document->addScript('../media/com_joomlaupdate/encryption.js');
		$document->addScript('../media/com_joomlaupdate/update.js');
		JHtml::_('script', 'system/progressbar.js', true, true);
        JHtml::_('stylesheet', 'media/mediamanager.css', array(), true);
*/
		//$this->addToolbar();		
		parent::display( $tpl );
	}
    
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{
		// Set toolbar items for the page
		JToolBarHelper::title( JText::_( 'COM_SPORTSMANAGEMENT_ADMIN_DBTOOLS_TITLE' ), 'config.png' );
		JToolBarHelper::back();
		JToolBarHelper::divider();
		sportsmanagementHelper::ToolbarButtonOnlineHelp();
        JToolBarHelper::preferences(JRequest::getCmd('option'));
	}	
	
}
?>