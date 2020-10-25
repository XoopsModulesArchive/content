<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <http://xoopscube.org>                             //
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
if ('Yes' == $_POST['submit']) {
    $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('content'));

    if (!$result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('content') . ' (SELECT * FROM ' . $xoopsDB->prefix('xt_conteudo') . ' )')) {
        redirect_header('migrate.php', 2, _C_ERRORINSERT);
    } else {
        redirect_header('index.php', 2, _C_DBUPDATED);
    }
} else {
    xoops_cp_header();

    echo '<h4>' . _C_ADMINTITLE . "</h4><table border='0' cellpadding='0' cellspacing='1' class='outer'>";

    echo "<form action='migrate.php' method='post'><tr class='even'><td>Migrating data will delete all content from the content database and replace it with all content in the XT Conteudo database.  Would you like to continue?</td></tr>";

    echo "<tr class='odd'><td align='center'><input type='submit' name='submit' value='Yes'>&nbsp;&nbsp;&nbsp;<input type='button' value='No' onClick=\"location.href='index.php'\"></td></tr>";

    echo '</form></table>';

    xoops_cp_footer();
}
