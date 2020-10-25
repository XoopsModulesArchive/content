<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <https://www.xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Tobias Liegl (AKA CHAPI)                                          //
// Site: http://www.chapi.de                                                 //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
require_once 'admin_header.php';
print_header();
// ------------------------------------------------------------------------- //
// Switch Statement for the different operations                             //
// ------------------------------------------------------------------------- //
$xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
global $op;
switch ($op) {
    // ------------------------------------------------------------------------- //
    // Delete File - Confirmation Question                                    //
    // ------------------------------------------------------------------------- //
    case 'delfile':
        if (1 == $showshort) {
            $action = 'window.close()';
        }
        ct_xoops_confirm(['address' => $address, 'op' => 'delfileok', 'loc' => $loc, 'showshort' => $showshort], 'manage_files.php', _C_RUSUREDELF, _YES, true, $action);
        break;
    // ------------------------------------------------------------------------- //
    // Delete it definitely                                                      //
    // ------------------------------------------------------------------------- //
    case 'delfileok':
        if (1 == $loc) {
            $dir = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/headers/';
        } else {
            $dir = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/content/';
        }
        @unlink($dir . '/' . $address);
        xoops_result(_C_FDELETED);
        echo '<script>
						opts = window.opener.document.ctform["' . ((1 == $loc) ? 'header_img' : 'address') . '"].options;
						for (i = 0; opt = opts[i]; i++){
							if ("' . $address . '" == opt.value){
								opts[i] = null;
								break;
							}
						}
				  </script>';
        show_form();
        break;
    // ------------------------------------------------------------------------- //
    // Show new link Page                                                        //
    // ------------------------------------------------------------------------- //
    default:
        // Delete File
        show_form();
        break;
}

function show_form()
{
    global $loc, $showshort;

    $form = new XoopsThemeForm(_C_DELFILE, 'form_name', 'manage_files.php');

    $address_select = new XoopsFormSelect(_C_URL, 'address');

    if (1 == $loc) {
        $folder = dir('../headers/');
    } else {
        $folder = dir('../content/');
    }

    while ($file = $folder->read()) {
        if ('.' != $file && '..' != $file) {
            $address_select->addOption($file, $file);
        }
    }

    $folder->close();

    $form->addElement($address_select);

    $delfile = 'delfile';

    $form->addElement(new XoopsFormHidden('op', $delfile));

    $form->addElement(new XoopsFormHidden('loc', $loc));

    $form->addElement(new XoopsFormHidden('showshort', $showshort));

    $submit = new XoopsFormButton('', 'submit', _C_DELETE, 'submit');

    $form->addElement($submit);

    $form->display();
}

print_footer();
