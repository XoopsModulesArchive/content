<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
if (file_exists('../../../mainfile.php')) {
    require_once '../../../mainfile.php';
}
require_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
require_once XOOPS_ROOT_PATH . '/include/cp_functions.php';
require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

require XOOPS_ROOT_PATH . '/modules/content/admin/include/admin_functions.php';
require XOOPS_ROOT_PATH . '/include/cp_header.php';

if (file_exists(XOOPS_ROOT_PATH . '/modules/content/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    require XOOPS_ROOT_PATH . '/modules/content/language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    require XOOPS_ROOT_PATH . '/modules/content/language/english/modinfo.php';
}

$groupPermHandler = xoops_getHandler('groupperm');
$moduleHandler = xoops_getHandler('module');
$module = $moduleHandler->getByDirname('content');
($xoopsUser) ? $groups = $xoopsUser->getGroups() : $groups = XOOPS_GROUP_ANONYMOUS;

if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        $$k = $v;
    }
}

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        $$k = $v;
    }
}

if (!$xoopsUser->isAdmin($module->getVar('mid'))
    && !$groupPermHandler->checkRight('content_page_write', $id, $groups, $module->getVar('mid')) && !$groupPermHandler->checkRight('content_page_add', $id, $groups, $module->getVar('mid'))
    && !$groupPermHandler->checkRight('content_admin', null, $groups, $module->getVar('mid'))) {
    //redirect_header(XOOPS_URL."/", 3, _NOPERM);

    exit();
}
