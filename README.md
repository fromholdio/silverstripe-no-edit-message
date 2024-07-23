# silverstripe-no-edit-message

Display friendly message in CMS on pages where user has no edit perm, rather than read-only form.

Includes replacing page History interface with message too.

To implement:

```yml
Page:
  is_no_edit_message_enabled: true    # default: true
```

And the following must be added to your `Page::getCMSFields()`

```php
public function getCMSFields()
{
    if ($this->doShowNoEditMessage() && Controller::curr() instanceof LeftAndMain) {
        return $this->getNoEditCMSFields();
    }
    
    # ... any other regular code.
}
```

Hooks are in place to amend the message, the tab path and fields, and even the trigger for when to display this (OOTB this applies when canEdit is false).
