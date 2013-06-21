<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 * Created    6/20/13 2:14 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

//require_once(JPATH_SITE . '/components/com_k2/helpers/utilities.php');
require_once(JPATH_SITE . '/components/com_k2/models/itemlist.php');
require_once(JPATH_SITE . '/components/com_k2/models/item.php');

class modK2RelatedListsHelper {
	/**
	 * Module parameters
	 *
	 * @var    boolean
	 * @since  0.0
	 */
	protected $params;

	/**
	 * Constructor
	 *
	 * @param   JRegistry $params  The module parameters
	 *
	 * @since  0.0
	 */
	public function __construct($params) {
		// Store the module params
		$this->params = $params;
	}

	function getLists($id = NULL) {
		$itemID = JRequest::getVar('id');

		if (strstr($itemID, ':')) {
			$itemID = strstr($itemID, ':', TRUE);
		}

		$tags   = K2ModelItem::getItemTags($itemID);
		$model = K2Model::getInstance('Item', 'K2Model');
		$params = K2HelperUtilities::getParams('com_k2');

		foreach ($tags as $tag) {
			$tags[]            = $tag;
			$items = K2ModelItemlist::getRelatedItems($itemID, $tags, $params);

			foreach ($items as $item) {
				//components/com_k2/models/item.php:1270
				$extraFields = $model->getItemExtraFields($item->extra_fields, $item);
				foreach ($extraFields as $extraField) {
					$alias                            = $extraField->alias;
					$item->extraFields->$alias->name  = $extraField->name;
					$item->extraFields->$alias->value = $extraField->value;
				}

				$lists[$tag->name][]= $item;
			}
		}

		return $lists;
	}
}