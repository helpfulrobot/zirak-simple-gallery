<?php

class SimpleGalleryImage extends DataObject {

	public static $db = array(
			'SortOrder' => 'Int',
			'Title' => 'Varchar',
			'SubTitle' => 'Varchar',
			'Text' => 'Text',
			'ButtonText' => 'Varchar(255)',
			'ButtonLink' => 'Varchar(255)',
			'CustomLink' => 'Varchar(255)',
			'Disabled' => 'Boolean'
	);
	public static $has_one = array(
			'Image' => 'Image',
			'Page' => 'Page',
			'Gallery' => 'SimpleGallery'
	);

	// tidy up the CMS by not showing these fields
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeFieldFromTab("Root.Main", "DataObjectID");
		$fields->removeFieldFromTab("Root.Main", "SortOrder");
		$fields->removeByName('PageID');
		$fields->removeByName('GalleryID');

		$folder = Config::inst()->get('SimpleGalleryExtension', 'folder_path');
		if (strlen($folder) == 0) {
			$folder = 'simplegallery';
		}

		$field = new UploadField('Image');
		$field->setFolderName($folder);
		$fields->insertAfter($field, 'Text');

		return $fields;
	}

	// Tell the datagrid what fields to show in the table
	public static $summary_fields = array(
			'ID' => 'ID',
			'Title' => 'Title',
			'Thumbnail' => 'Thumbnail'
	);

	// this function creates the thumnail for the summary fields to use
	public function getThumbnail() {
		return $this->Image()->CMSThumbnail();
	}

	/**
	 * Manipulate SortOrder adding $modifier. Templates sample (start from 0):
	 * <li data-target="#HomeCarousel" data-slide-to="$getModifiedSortOrder(-1)"></li>
	 * 
	 * @param Int $modifier
	 * @return Int
	 */
	public function getModifiedSortOrder($modifier) {
		return $this->SortOrder + $modifier;
	}

	/**
	 * Tutti possono visualizzare il DataObject
	 * @param type $member
	 * @return boolean
	 */
	public function canView($member = null) {
		return true;
	}

	public function canEdit($member = null) {
		if (Permission::check('SIMPLE_GALLERY_IMAGE_MANAGE'))
			return true;
	}

	public function canCreate($member = null) {
		if (Permission::check('SIMPLE_GALLERY_IMAGE_MANAGE'))
			return true;
	}

	public function canDelete($member = null) {
		if (Permission::check('SIMPLE_GALLERY_IMAGE_MANAGE'))
			return true;
	}

}

class SimpleGalleryImage_Controller extends ContentController implements PermissionProvider {

	public function providePermissions() {
		return array(
				"SIMPLE_GALLERY_IMAGE_MANAGE" => "Manage ZkGallery Images",
		);
	}

}
