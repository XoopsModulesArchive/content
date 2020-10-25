<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
require_once 'admin_header.php';
print_header();
echo '<h4>' . _C_ADMINTITLE . '</h4>' . showMenu(); //<table border='0' cellpadding='0' cellspacing='1' class='outer'>";
echo 'Page title:&nbsp;';
if (!FieldExists('ptitle', $xoopsDB->prefix('content'))) {
    $sql = sprintf('ALTER TABLE ' . $xoopsDB->prefix('content') . ' ADD `ptitle` VARCHAR(255) default NULL AFTER `title`;');

    $xoopsDB->queryF($sql);

    echo '<font color="#800000">' . _C_CREATED . '</font>';
} else {
    echo '<font color="#339933">' . _C_YES . '</font>';
}
echo '<br>';
echo 'Error page:&nbsp;';
if (!FieldExists('epage', $xoopsDB->prefix('content'))) {
    $sql = sprintf('ALTER TABLE ' . $xoopsDB->prefix('content') . " ADD `epage` tinyint(1) default '0' AFTER `homepage`;");

    $xoopsDB->queryF($sql);

    echo '<font color="#800000">' . _C_CREATED . '</font>';
} else {
    echo '<font color="#339933">' . _C_YES . '</font>';
}

echo '<br>';
echo 'Last modified:&nbsp;';
if (!FieldExists('date', $xoopsDB->prefix('content'))) {
    $sql = sprintf('ALTER TABLE ' . $xoopsDB->prefix('content') . ' ADD `date` DATETIME DEFAULT NULL AFTER `newwindow`;');

    $xoopsDB->queryF($sql);

    echo '<font color="#800000">' . _C_CREATED . '</font>';
} else {
    echo '<font color="#339933">' . _C_YES . '</font>';
}

echo '<br>';
echo 'Associate Module:&nbsp;';
if (!FieldExists('assoc_module', $xoopsDB->prefix('content'))) {
    $sql = sprintf('ALTER TABLE ' . $xoopsDB->prefix('content') . ' ADD `assoc_module` int(8) unsigned default NULL AFTER `date`;');

    $xoopsDB->queryF($sql);

    echo '<font color="#800000">' . _C_CREATED . '</font>';
} else {
    echo '<font color="#339933">' . _C_YES . '</font>';
}

echo '<br>';
echo 'Header Image:&nbsp;';
if (!FieldExists('header_img', $xoopsDB->prefix('content'))) {
    $sql = sprintf('ALTER TABLE ' . $xoopsDB->prefix('content') . ' ADD `header_img` VARCHAR(255) default NULL AFTER `assoc_module`;');

    $xoopsDB->queryF($sql);

    echo '<font color="#800000">' . _C_CREATED . '</font>';
} else {
    echo '<font color="#339933">' . _C_YES . '</font>';
}

echo '<br><br>' . _C_DBUPGRADED . ': <strong>1-rc1</strong>';

print_footer();
