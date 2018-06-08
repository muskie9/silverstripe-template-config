<?php

namespace Dynamic\TemplateConfig\ORM;

use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldDeleteAction;
use Dynamic\TemplateConfig\Model\NavigationColumn;

/**
 * Class FooterNavigationManager.
 */
class FooterNavigationManager extends DataExtension
{
    /**
     * @var array
     */
    private static $has_many = array(
      'NavigationColumns' => NavigationColumn::class,
    );

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // footer navigation
        if ($this->owner->ID) {
            $config = GridFieldConfig_RecordEditor::create();
            $config->addComponent(new GridFieldOrderableRows('SortOrder'));
            $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
            $config->removeComponentsByType(GridFieldDeleteAction::class);
            $config->addComponent(new GridFieldDeleteAction(false));
            $footerLinks = GridField::create(
                'NavigationColumns',
                'Columns',
                $this->owner->NavigationColumns()->sort('SortOrder'),
                $config
            );

            $fields->addFieldsToTab('Root.Footer', array(
                HeaderField::create('FooterHD', 'Footer', 1),
                LiteralField::create(
                    'FooterDescrip',
                    '<p>Add columns to the footer area of your template. After you create a column, 
                        you\'ll be able to add groups of links to the footer navigation.</p>'
                ),
                $footerLinks
                    ->setDescription('Add a column to the layout of the footer of your theme'),
            ));
        }
    }
}
