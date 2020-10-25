<?php

/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 *
 * For further information visit:
 * 		http://www.fckeditor.net/
 *
 * File Name: connector.php
 * 	This is the File Manager Connector for PHP.
 *
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

require __DIR__ . '/config.php';
require __DIR__ . '/util.php';
require __DIR__ . '/io.php';
require __DIR__ . '/basexml.php';
require __DIR__ . '/commands.php';

// Get the "UserFiles" path.
$GLOBALS['UserFilesPath'] = '';

if (isset($Config['UserFilesPath'])) {
    $GLOBALS['UserFilesPath'] = $Config['UserFilesPath'];
} elseif (isset($_GET['ServerPath'])) {
    $GLOBALS['UserFilesPath'] = $_GET['ServerPath'];
} else {
    $GLOBALS['UserFilesPath'] = '/UserFiles/';
}

if (!preg_match('/$', $GLOBALS['UserFilesPath'])) {
    $GLOBALS['UserFilesPath'] .= '/';
}

// Map the "UserFiles" path to a local directory.
//$GLOBALS["UserFilesDirectory"] = GetRootPath() . str_replace( '/', '\\', $GLOBALS["UserFilesPath"] ) ;
$GLOBALS['UserFilesDirectory'] = GetRootPath() . $GLOBALS['UserFilesPath'];

DoResponse();

function DoResponse()
{
    if (!isset($_GET['Command']) || !isset($_GET['Type']) || !isset($_GET['CurrentFolder'])) {
        return;
    }

    // Get the main request informaiton.

    $sCommand = $_GET['Command'];

    $sResourceType = $_GET['Type'];

    $sCurrentFolder = $_GET['CurrentFolder'];

    // Check if it is an allowed type.

    if (!in_array($sResourceType, ['File', 'Image', 'Flash', 'Media'], true)) {
        return;
    }

    // Check the current folder syntax (must begin and start with a slash).

    if (!preg_match('/$', $sCurrentFolder)) {
        $sCurrentFolder .= '/';
    }

    if (0 !== mb_strpos($sCurrentFolder, '/')) {
        $sCurrentFolder = '/' . $sCurrentFolder;
    }

    // File Upload doesn't have to Return XML, so it must be intercepted before anything.

    if ('FileUpload' == $sCommand) {
        FileUpload($sResourceType, $sCurrentFolder);

        return;
    }

    // Prevent the browser from caching the result.

    // Date in the past

    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

    // always modified

    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');

    // HTTP/1.1

    header('Cache-Control: no-store, no-cache, must-revalidate');

    header('Cache-Control: post-check=0, pre-check=0', false);

    // HTTP/1.0

    header('Pragma: no-cache');

    // Set the response format.

    header('Content-Type:text/xml; charset=utf-8');

    CreateXmlHeader($sCommand, $sResourceType, $sCurrentFolder);

    // Execute the required command.

    switch ($sCommand) {
        case 'GetFolders':
            GetFolders($sResourceType, $sCurrentFolder);
            break;
        case 'GetFoldersAndFiles':
            GetFoldersAndFiles($sResourceType, $sCurrentFolder);
            break;
        case 'CreateFolder':
            CreateFolder($sResourceType, $sCurrentFolder);
            break;
    }

    CreateXmlFooter();

    exit;
}
