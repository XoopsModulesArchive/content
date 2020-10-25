<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

require dirname(__DIR__, 2) . '/mainfile.php';
$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;
if ($com_itemid > 0) {
    // Get link title

    $sql = 'SELECT title FROM ' . $xoopsDB->prefix('content') . ' WHERE storyid=' . $com_itemid . '';

    $result = $xoopsDB->query($sql);

    $row = $xoopsDB->fetchArray($result);

    $com_replytitle = $row['title'];

    require XOOPS_ROOT_PATH . '/include/comment_new.php';
}
