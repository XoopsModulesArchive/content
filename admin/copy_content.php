<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
require_once 'admin_header.php';

// ------------------------------------------------------------------------- //
// Switch Statement for the different operations                             //
// ------------------------------------------------------------------------- //
$xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
global $op;
switch ($op) {
    // ------------------------------------------------------------------------- //
    // Delete it definitely                                                      //
    // ------------------------------------------------------------------------- //
    case 'copy':
        global $xoopsDB;
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE storyid=' . (int)$id);
        $oldrecord = $xoopsDB->fetchArray($result);

        foreach ($oldrecord as $key => $value) {
            if ('storyid' != $key) {
                if (isset($dbFields)) {
                    $dbFields .= ', ';

                    $dbValues .= ', ';
                }

                $dbFields .= '`' . $key . '`';

                $dbValues .= "'" . (('title' == $key) ? 'Copy of ' . addslashes($value) : addslashes($value)) . "'";
            }
        }

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' (' . $dbFields . ') VALUES (' . $dbValues . ')');

        $newId = $xoopsDB->getInsertId();

        $moduleHandler = xoops_getHandler('module');
        $groupPermHandler = xoops_getHandler('groupperm');
        $module = $moduleHandler->getByDirname('content');
        $allowedGroups = $groupPermHandler->getGroupIds('content_page_view', $id, $module->getVar('mid'));

        foreach ($allowedGroups as $group) {
            $groupPermHandler->addRight('content_page_view', $newId, $group, $module->getVar('mid'));
        }

        redirect_header('edit_content.php?id=' . $newId . '&return=' . $return, 2, _C_DBUPDATED);

        break;
    // ------------------------------------------------------------------------- //
    // Delete Content - Confirmation Question                                    //
    // ------------------------------------------------------------------------- //
    default:
        print_header();
        if (1 == $showshort) {
            $action = 'window.close()';
        }
        ct_xoops_confirm(['id' => (int)$id, 'op' => 'copy'], 'copy_content.php', _C_COPYCONTENT, _YES, true, $action);
        print_footer();
        break;
}
