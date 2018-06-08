<?php

namespace Dynamic\TemplateConfig\ORM;

use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\CMS\Model\SiteTree;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;

/**
 * Class UtilityNavigationManager.
 */
class UtilityNavigationManager extends DataExtension
{
    /**
     * @var array
     */
    private static $many_many = array(
        'UtilityLinks' => SiteTree::class,
    );

    /**
     * @var array
     */
    private static $many_many_extraFields = array(
        'UtilityLinks' => array(
            'SortOrder' => 'Int',
        ),
    );

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        if ($this->owner->ID) {
            $config = GridFieldConfig_RelationEditor::create()
                ->addComponent(new GridFieldOrderableRows('SortOrder'))
                ->removeComponentsByType(GridFieldAddExistingAutocompleter::class)
                ->addComponent(new GridFieldAddExistingSearchButton())
                ->removeComponentsByType(GridFieldAddNewButton::class);
            $promos = $this->owner->UtilityLinks()->sort('SortOrder');
            $linksField = GridField::create(
                'UtilityLinks',
                'Links',
                $promos,
                $config
            );

            $fields->addFieldsToTab('Root.Utility', array(
                HeaderField::create('UtilityHD', 'Utility Navigation', 2),
                LiteralField::create(
                    'UtilityDescrip',
                    '<p>Add links to the utility navigation area of your template.</p>'
                ),
                $linksField
                    ->setDescription('Add links to the utility navigation area'),
            ));
        }
    }
}
