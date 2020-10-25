<?php

/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2006 Frederico Caldeira Knabben
 *
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 *
 * For further information visit:
 * 		http://www.fckeditor.net/
 *
 * "Support Open Source software. What about a donation today?"
 *
 * File Name: util.php
 * 	This is the File Manager Connector for ASP.
 *
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

function RemoveExtension($fileName)
{
    return mb_substr($fileName, 0, mb_strrpos($fileName, '.'));
}

function GetRootPath()
{
    $sRealPath = realpath('./');

    $sSelfPath = $_SERVER['PHP_SELF'];

    $sSelfPath = mb_substr($sSelfPath, 0, mb_strrpos($sSelfPath, '/'));

    return mb_substr($sRealPath, 0, mb_strlen($sRealPath) - mb_strlen($sSelfPath));
}
