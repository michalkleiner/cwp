<?php
/**
 * **BaseHomePage** is the basic home page.
 * By default it is hidden from the CMS - we rely on developers creating their own
 * `HomePage` class in the `mysite/code` which will extend from the **BaseHomePage**.
 */

class BaseHomePage extends Page {

	private static $icon = "cwp/images/icons/sitetree_images/home.png";
	public $pageIcon =  "images/icons/sitetree_images/home.png";

	private static $hide_ancestor = 'BaseHomePage';

	private static $db = array(
		'FeatureOneTitle' => 'Varchar(255)',
		'FeatureOneCategory' => "Enum('comments, group, news', 'comments')",
		'FeatureOneContent' => 'HTMLText',
		'FeatureOneButtonText' => 'Varchar(255)',
		'FeatureTwoTitle' => 'Varchar(255)',
		'FeatureTwoCategory' => "Enum('comments, group, news', 'group')",
		'FeatureTwoContent' => 'HTMLText',
		'FeatureTwoButtonText' => 'Varchar(255)'
	);

	private static $has_one = array(
		'LearnMorePage' => 'SiteTree',
		'FeatureOneLink' => 'SiteTree',
		'FeatureTwoLink' => 'SiteTree'
	);

	private static $has_many = array(
		'CarouselItems' => 'CarouselItem',
		'Quicklinks' => 'Quicklink'
	);

	function getCMSFields() {
		$fields = parent::getCMSFields();

		// Main Content tab
		$fields->addFieldToTab('Root.Main', new TreeDropdownField('LearnMorePageID', 'Page to link the "Learn More" button to:', 'SiteTree'), 'Metadata');
		// Carousel tab
		$gridField = new GridField(
			'CarouselItems',
			'Carousel',
			$this->CarouselItems()->sort('Archived'),
			GridFieldConfig_RelationEditor::create());
		$gridField->setModelClass('CarouselItem');
		$fields->addFieldToTab('Root.Carousel', $gridField);

		$gridField = new GridField(
			'Quicklinks',
			'Quicklinks',
			$this->Quicklinks(),
			GridFieldConfig_RelationEditor::create());
		$gridField->setModelClass('Quicklink');
		$fields->addFieldToTab('Root.Quicklinks', $gridField);

		$fields->removeByName('Translations');
		$fields->removeByName('Import');

		$fields->addFieldToTab('Root.Features', ToggleCompositeField::create('FeatureOne', _t('SiteTree.FeatureOne', 'Feature One'),
			array(
				new TextField('FeatureOneTitle', 'Title'),
				$dropdownField = new DropdownField('FeatureOneCategory', 'Category', singleton('BaseHomePage')->dbObject('FeatureOneCategory')->enumValues()),
				new HTMLEditorField('FeatureOneContent', 'Content'),
				new TreeDropdownField('FeatureOneLinkID', 'Page to link to', 'SiteTree'),
				new TextField('FeatureOneButtonText', 'Button text')
				)
			)->setHeadingLevel(3)
		);
		$dropdownField->setEmptyString('none');

		$fields->addFieldToTab('Root.Features', ToggleCompositeField::create('FeatureTwo', _t('SiteTree.FeatureTwo', 'Feature Two'),
			array(
				new TextField('FeatureTwoTitle', 'Title'),
				$dropdownField = new DropdownField('FeatureTwoCategory', 'Category', singleton('BaseHomePage')->dbObject('FeatureTwoCategory')->enumValues()),
				new HTMLEditorField('FeatureTwoContent', 'Content'),
				new TreeDropdownField('FeatureTwoLinkID', 'Page to link to', 'SiteTree'),
				new TextField('FeatureTwoButtonText', 'Button text')
				)
			)->setHeadingLevel(3)
		);
		$dropdownField->setEmptyString('none');

		return $fields;
	}

	function getVisibleCarouselItems() {
		return $this->CarouselItems()->filter('Archived', false);
	}
}

class BaseHomePage_Controller extends Page_Controller {

	public function getNewsPage() {
		return NewsHolder::get_one('NewsHolder');
	}

	/**
	 * @param int $amount The amount of items to provide.
	 */
	public function getNewsItems($amount = 2) {
		$newsHolder = $this->getNewsPage();
		if ($newsHolder) {
			$controller = new NewsHolder_Controller($newsHolder);
			return $controller->Updates()->limit($amount);
		}
	}
}