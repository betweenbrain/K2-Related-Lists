<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 * Created    6/20/13 2:14 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

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
		$this->params = $params;
	}

	function getLists() {

		$itemID = JRequest::getVar('id');

		/**
		 * Parse numeric ID if the alias is appended to the ID variable
		 */
		if (strstr($itemID, ':')) {
			$itemID = strstr($itemID, ':', TRUE);
		}

		/**
		 * Use http://docs.joomla.org/API15:JModel/getInstance instead of direct file includes
		 * http://getk2.org/community/New-to-K2-Ask-here-first/176561-How-to-show-extra-fields-for-related-items#178009
		 */
		//components/com_k2/models/item.php
		$itemModel = K2Model::getInstance('Item', 'K2Model');
		//components/com_k2/models/itemlist.php
		$listModel = K2Model::getInstance('Itemlist', 'K2Model');
		//components/com_k2/helpers/utilities.php
		$utilities = K2Model::getInstance('Utilities', 'K2Helper');
		// Get K2 parameters
		$k2Params  = $utilities->getParams('com_k2');
		// Get tags by item ID
		$tags      = $itemModel->getItemTags($itemID);

		/**
		 * When passing a value of 0 for $itemID, getRelatedItems() will include the current item.
		 */
		$includeSelf = $this->params->get('includeSelf');

		if ($includeSelf) {
			$itemID = '0';
		}

		/**
		 * Limit the number of lists based on how many tags to use
		 */
		$tagLimit = htmlspecialchars($this->params->get('tagLimit'));

		/**
		 * Limit the number of items to retrieve by manually setting the itemRelatedLimit parameter
		 */
		$itemLimit = htmlspecialchars($this->params->get('itemLimit'));

		$k2Params->_registry['_default']['data']->itemRelatedLimit = $itemLimit;

		if ($tagLimit == '' || $tagLimit == '0') {
			$tagLimit = count($tags);
		}

		foreach ($tags as $key => $tag) {

			if ($key == $tagLimit) {
				break;
			}

			/**
			 * Re-create (clear) temp array for each tag to avoid false positive matches of related content.
			 * The current tag being tested for relation needs to be a node of the temp array to satisfy getRelatedItems().
			 */
			$temp   = array();
			$temp[] = $tag;

			$items = $listModel->getRelatedItems($itemID, $temp, $k2Params);

			foreach ($items as $item) {

				/**
				 * Check for and attach extra field names and values to $item object.
				 */
				$extraFields = $itemModel->getItemExtraFields($item->extra_fields, $item);

				if ($extraFields) {
					foreach ($extraFields as $extraField) {
						$alias                            = $extraField->alias;
						$item->extraFields->$alias->name  = $extraField->name;
						$item->extraFields->$alias->value = $extraField->value;
					}
				}

				/**
				 * Check for and attach plugin data to $item object as stdClass.
				 *
				 * parse_ini_string set to INI_SCANNER_RAW so that option values are not parsed (http://php.net/manual/en/function.parse-ini-string.php)
				 */
				if ($item->plugins) {
					$plugins       = (object) parse_ini_string($item->plugins, FALSE, INI_SCANNER_RAW);
					$item->plugins = $plugins;
				}

				$lists[$tag->name][] = $item;
			}
		}

		return $lists;
	}
}
