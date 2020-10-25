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
    case 'deleteit':
        global $xoopsDB;
        //move any orphaned content items to the same level as the deleted item
        $result = $xoopsDB->query('SELECT parent_id FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE storyid=' . (int)$id);
        [$parent] = $xoopsDB->fetchRow($result);
        $result = $xoopsDB->query('UPDATE ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' SET parent_id = ' . (int)$parent . ' WHERE parent_id=' . (int)$id);
        $result = $xoopsDB->query('DELETE FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE storyid=' . (int)$id);
        xoops_comment_delete($xoopsModule->getVar('mid'), $id);
        if (isset($return) && 1 == $return) {
            echo "<script>window.opener.location.href='/';window.close();</script>";
        } else {
            redirect_header('manage_content.php', 1, _C_DBUPDATED);
        }

        break;
    // ------------------------------------------------------------------------- //
    // Delete Content - Confirmation Question                                    //
    // ------------------------------------------------------------------------- //
    default:
        print_header();
        $confirm_params = [];
        $confirm_params['id'] = (int)$id;
        $confirm_params['op'] = 'deleteit';
        if (isset($return) && 1 == $return) {
            $confirm_params['return'] = $return;
        }
        if (1 == $showshort) {
            $action = 'window.close()';
        }
        ct_xoops_confirm($confirm_params, 'delete_content.php', _C_RUSUREDEL, _YES, true, $action);
        print_footer();
        break;
}
