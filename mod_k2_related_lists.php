<?php defined('_JEXEC') or die;

/**
 * File       mod_k2_related_lists.php
 * Created    6/20/13 2:30 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

// Include the helper file
require_once(dirname(__FILE__) . DS . 'helper.php');

// Application object
$app = JFactory::getApplication();

// Global document object
$doc = JFactory::getDocument();

// Instantiate our class
$listsHelper = new modK2RelatedListsHelper($params);

// Retrieve current tags
$lists = $listsHelper->getLists();

// Current component Name
$component = JRequest::getCmd('option');

// Get current view
$view = JRequest::getVar('view');

// Check if there are tags and K2 is the current component
if ($component == "com_k2" && $view == "item" && $lists) {

	// Render module output
	require JModuleHelper::getLayoutPath('mod_k2_related_lists');
	/**
	 * Load CSS files, first checking for template override of CSS.
	 *
	 * JPATH_SITE: Absolute path to the installed Joomla! site Checking absolute path helps security.
	 *
	 * JURI::base():  Base URI of the Joomla site. If TRUE, then only the path, trailing "/" omitted, to the Joomla site is returned;
	 * otherwise the scheme, host and port are prepended to the path.
	 */
	/*
	if (file_exists(JPATH_SITE . '/templates/' . $app->getTemplate() . '/css/mod_k2_item_filter/tags.css')) {
		$doc->addStyleSheet(JURI::base(TRUE) . '/templates/' . $app->getTemplate() . '/css/mod_k2_item_filter/tags.css');
	} elseif (file_exists(JPATH_SITE . '/modules/mod_k2_item_filter/tmpl/css/tags.css')) {
		$doc->addStyleSheet(JURI::base(TRUE) . '/modules/mod_k2_item_filter/tmpl/css/tags.css');
	}
	*/
}