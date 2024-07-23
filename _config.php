<?php

use SilverStripe\Admin\CMSMenu;
use Fromholdio\NoEditMessage\Controllers\NoEditCMSPageHistoryViewerController;

CMSMenu::remove_menu_class(NoEditCMSPageHistoryViewerController::class);
