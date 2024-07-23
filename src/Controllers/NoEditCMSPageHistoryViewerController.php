<?php

namespace Fromholdio\NoEditMessage\Controllers;

use SilverStripe\CMS\Controllers\CMSMain;
use SilverStripe\Forms\CompositeField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\ORM\DataObject;
use SilverStripe\VersionedAdmin\Controllers\CMSPageHistoryViewerController;

class NoEditCMSPageHistoryViewerController extends CMSPageHistoryViewerController
{
    public function getEditForm($id = null, $fields = null)
    {
        /** @var DataObject $record */
        $record = $this->getRecord($id ?: $this->currentPageID());

        /** @var Form $form */
        $form = CMSMain::getEditForm($id);
        if ($record->hasMethod('doShowNoEditMessage') && $record->doShowNoEditMessage())
        {
            $paddedWrapper = class_exists('UncleCheese\DisplayLogic\Forms\Wrapper')
                ? \UncleCheese\DisplayLogic\Forms\Wrapper::create()
                : CompositeField::create();
            $paddedFieldsList = FieldList::create($paddedWrapper);
            $paddedWrapper->addExtraClass('panel--padded');

            $noEditFields = $record->getNoEditCMSFields();
            $mainTab = $noEditFields->fieldByName('Root.MainTabSet.MainTab');
            foreach ($mainTab->Fields() as $mainTabField) {
                $paddedWrapper->push($mainTabField);
            }
            $form->setFields($paddedFieldsList);
            return $form;
        }
        return parent::getEditForm($id);
    }
}
