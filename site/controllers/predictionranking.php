<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');


/**
 * sportsmanagementControllerPredictionRanking
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementControllerPredictionRanking extends JControllerLegacy
{
	/**
	 * sportsmanagementControllerPredictionRanking::display()
	 * 
	 * @return void
	 */
	function display()
	{
	  // Get the view name from the query string
        $viewName = JRequest::getVar( "view", "predictionranking" );

        // Get the view
        $view = & $this->getView( $viewName );

        // Get the joomleague model
        $jl = $this->getModel( "joomleague", "JoomleagueModel" );
        $jl->set( "_name", "joomleague" );
        if (!JError::isError( $jl ) )
        {
            $view->setModel ( $jl );
        }
    // Get the joomleague model
		$sr = $this->getModel( 'prediction', 'JoomleagueModel' );
		$sr->set( '_name', 'prediction' );
		if ( !JError::isError( $sr ) )
		{
			$view->setModel ( $sr );
		}
		
		// Get the joomleague model
		$jl = $this->getModel( 'project', 'JoomleagueModel' );
		$jl->set( '_name', 'project' );
		if ( !JError::isError( $jl ) )
		{
			$view->setModel ( $jl );
		}
		
		$this->showprojectheading();
		$view->display();
		$this->showbackbutton();
		$this->showfooter();
	}

	/**
	 * sportsmanagementControllerPredictionRanking::selectprojectround()
	 * 
	 * @return void
	 */
	function selectprojectround()
	{
		JRequest::checkToken() or jexit(JText::_('JL_PRED_INVALID_TOKEN_REFUSED'));
		$post	= JRequest::get('post');
		//echo '<br /><pre>~' . print_r($post,true) . '~</pre><br />';
		$pID	= JRequest::getVar('prediction_id',	'',	'post',	'int');
		$pggroup	= JRequest::getVar('pggroup',	null,	'post',	'int');
        $pggrouprank= JRequest::getVar('pggrouprank',null,	'post',	'int');
        $pjID	= JRequest::getVar('p',	'',	'post',	'int');
        
		$rID	= JRequest::getVar('round_id',		'',	'post',	'int');
		$set_pj	= JRequest::getVar('set_pj',		'',	'post',	'int');
		$set_r	= JRequest::getVar('set_r',			'',	'post',	'int');

		$link = JSMPredictionHelperRoute::getPredictionRankingRoute($pID,$pjID,$rID,'',$pggroup,$pggrouprank);
        
		//echo '<br />' . $link . '<br />';
		$this->setRedirect($link);
	}

}
?>