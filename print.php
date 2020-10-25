<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
require dirname(__DIR__, 2) . '/mainfile.php';

if (file_exists('language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    include 'language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    include 'language/english/modinfo.php';
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (empty($id)) {
    redirect_header('index.php');
}

global $xoopsConfig, $xoopsModule, $xoopsDB;
echo '<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">';
echo '<html>';
echo '<head>';
echo '	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">';
echo '	<title>' . $xoopsConfig['sitename'] . '</title>';
echo '	<meta name="AUTHOR" content="' . $xoopsConfig['sitename'] . '">';
echo '	<meta name="COPYRIGHT" content="Copyright (c) 2005' . $xoopsConfig['sitename'] . '">';
echo '	<meta name="DESCRIPTION" content="' . $xoopsConfig['slogan'] . '">';
echo '	<meta name="GENERATOR" content="' . XOOPS_VERSION . '">';
echo '	<link rel="stylesheet" type="text/css" media="screen" href="' . XOOPS_URL . '/css/print.css">';
echo '</head>';

$result = $xoopsDB->queryF('SELECT storyid, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . " WHERE storyid=$id");
[$storyid, $title, $text, $visible, $nohtml, $nosmiley, $nobreaks, $nocomments, $link, $address] = $xoopsDB->fetchRow($result);
echo '<body bgcolor="#FFFFFF" text="#000000" topmargin="10" style="font:12px arial, helvetica, san serif;" onLoad="window.print()">';
echo '	<table border="0" width="640" cellpadding="10" cellspacing="1" style="border: 1px solid #000000;" align="center">';
echo '		<tr>';
echo '			<td align="left"><img src="' . XOOPS_URL . '/images/logo.gif" border="0" alt=""><br><br>';
echo '				<strong>' . $title . '</strong></td>';
echo '		</tr>';
echo '		<tr valign="top">';
echo '			<td style="padding-top:0px;">';

if (1 == $link) {
    $includeContent = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/content/' . $address;

    if (file_exists($includeContent)) {
        ob_start();

        include $includeContent;

        $content = ob_get_contents();

        ob_end_clean();
    }

    echo $content;
} else {
    echo $text;
}

echo '</td>';
echo '		</tr>';
echo '	</table>';
echo '	<table border="0" width="640" cellpadding="10" cellspacing="1" align="center"><tr><td>';
printf(_C_THISCOMESFROM, $xoopsConfig['sitename']);
echo '<br><a href="' . XOOPS_URL . '/">' . XOOPS_URL . '</a><br><br>' . _C_URLFORSTORY . '<br><a href="' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/index.php?id=' . $id . '">' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/index.php?id=' . $id . '</a>';
echo '</td></tr></table></body>';
echo '</html>';
