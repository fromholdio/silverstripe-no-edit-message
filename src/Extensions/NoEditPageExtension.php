<?php

namespace Fromholdio\NoEditMessage\Extensions;

use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;

class NoEditPageExtension extends SiteTreeExtension
{
    private static $is_no_edit_message_enabled = true;

    private static $field_labels = [
        'NoEditMessage' => 'Sorry, you do not have access to manage this {pagetype}.',
        'NoEditHelp' => 'Please contact your site manager if you believe this in error.'
    ];

    public function isNoEditMessageEnabled(): bool
    {
        $is = (bool) $this->getOwner()->config()->get('is_no_edit_message_enabled');
        $this->getOwner()->invokeWithExtensions('updateIsNoEditMessageEnabled', $is);
        return $is;
    }

    public function doShowNoEditMessage(): bool
    {
        $do = $this->getOwner()->isNoEditMessageEnabled()
            && !$this->getOwner()->canEdit();
        $this->getOwner()->invokeWithExtensions('updateDoShowNoEditMessage', $do);
        return $do;
    }

    public function getNoEditCMSFields(): FieldList
    {
        $message = $this->getOwner()->fieldLabel('NoEditMessage');
        if (!empty($message)) {
            $message = str_replace(
                '{pagetype}',
                strtolower($this->getOwner()->i18n_singular_name()),
                $message
            );
        }
        $messageField = HeaderField::create(
            'NoEditMessage',
            $message,
            1
        );

        $helpField = HeaderField::create(
            'NoEditHelp',
            $this->getOwner()->fieldLabel('NoEditHelp')
        );

        $fields = FieldList::create(
            TabSet::create('Root',
                TabSet::create(
                    'MainTabSet',
                    $this->getOwner()->fieldLabel('MainTabSet'),
                    Tab::create(
                        'MainTab',
                        $this->getOwner()->fieldLabel('MainTab'),
                        $messageField,
                        $helpField
                    )
                )
            )
        );
        $this->getOwner()->invokeWithExtensions('updateNoEditCMSFields', $fields);
        return $fields;
    }
}
