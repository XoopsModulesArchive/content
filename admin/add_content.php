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
global $op, $showshort;

if ('add' == $op || 'link' == $op) {
    $myts = MyTextSanitizer::getInstance();

    $title = $myts->addSlashes($title);

    $ptitle = $myts->addSlashes($ptitle);

    $message = $myts->addSlashes($message);

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . '');

    $rows = $GLOBALS['xoopsDB']->getRowsNum($result);

    $hp = (0 == $rows) ? 1 : 0;

    if ($_FILES[imageupload]) {
        $uploadpath = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/headers/';

        $source = $_FILES[imageupload][tmp_name];

        $fileupload_name = $_FILES[imageupload][name];

        if (('none' != $source) && ('' != $source)) {
            $dest = $uploadpath . $fileupload_name;

            if (file_exists($uploadpath . $fileupload_name)) {
                redirect_header('add_content.php', 2, _C_ERRORUPL);
            } else {
                if (copy($source, $dest)) {
                    $header_img = $fileupload_name;
                } else {
                    redirect_header('add_content.php', 2, _C_ERRORUPL);
                }

                unlink($source);
            }
        }
    }

    $sqlinsert = 'INSERT INTO '
                 . $xoopsDB->prefix(_MIC_CONTENT_PREFIX)
                 . " (parent_id, ptitle, title, text, visible, homepage, nohtml, nosmiley, nobreaks, nocomments, 
		link, address, submenu, newwindow, date, assoc_module, header_img) VALUES ('"
                 . (int)$parent_id
                 . "','"
                 . $ptitle
                 . "','"
                 . $title
                 . "','"
                 . $message
                 . "','"
                 . (int)$visible
                 . "','"
                 . $hp
                 . "','"
                 . (int)$nohtml
                 . "','"
                 . (int)$nosmiley
                 . "','"
                 . (int)$nobreaks
                 . "', '"
                 . "', '0','"
                 . $externalURL
                 . "','"
                 . (int)$submenu
                 . "','"
                 . (int)$newwindow
                 . "',NOW(), '"
                 . (int)$assoc_module
                 . "', '"
                 . $header_img
                 . "')";

    if (!$result = $xoopsDB->query($sqlinsert)) {
        echo _C_ERRORINSERT;
    }

    $newId = $xoopsDB->getInsertId();

    if ($xoopsModuleConfig['cont_permits_advnaced'] > 0) {
        $moduleHandler = xoops_getHandler('module');

        $groupPermHandler = xoops_getHandler('groupperm');

        $module = $moduleHandler->getByDirname('content');

        foreach ($group_read_perms as $group) {
            $groupPermHandler->addRight('content_page_view', $newId, $group, $module->getVar('mid'));
        }

        if (2 == $xoopsModuleConfig['cont_permits_advnaced']) {
            foreach ($group_write_perms as $group) {
                $groupPermHandler->addRight('content_page_write', $newId, $group, $module->getVar('mid'));
            }
        }
    }

    if (isset($return) && 1 == $return) {
        echo "<script>window.opener.location.href='/modules/content/index.php?id=" . $newId . "';window.close();</script>";

    //redirect_header(XOOPS_URL."/modules/content/index.php?id=" . $id, 2, _C_DBUPDATED);
    } else {
        redirect_header('manage_content.php' . ((isset($showshort)) ? '?showshort=' . $showshort : ''), 2, _C_DBUPDATED);
    }
} elseif ('pagewrap' == $op) {
    $myts = MyTextSanitizer::getInstance();

    if ($_FILES[fileupload]) {
        $uploadpath = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/content/';

        $source = $_FILES[fileupload][tmp_name];

        $fileupload_name = $_FILES[fileupload][name];

        if (('none' != $source) && ('' != $source)) {
            $dest = $uploadpath . $fileupload_name;

            if (file_exists($uploadpath . $fileupload_name)) {
                redirect_header('add_content.php', 2, _C_ERRORUPL);
            } else {
                if (copy($source, $dest)) {
                    $address = $fileupload_name;
                } else {
                    redirect_header('add_content.php', 2, _C_ERRORUPL);
                }

                unlink($source);
            }
        }
    }

    $title = $myts->addSlashes($title);

    $address = $myts->addSlashes($address);

    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . '');

    $rows = $GLOBALS['xoopsDB']->getRowsNum($result);

    $hp = (0 == $rows) ? 1 : 0;

    $sqlinsert = 'INSERT INTO '
                 . $xoopsDB->prefix(_MIC_CONTENT_PREFIX)
                 . " (parent_id,title,text,visible,homepage,nohtml,nosmiley,nocomments,link,address,submenu,date) VALUES ('"
                 . (int)$parent_id
                 . "','"
                 . $title
                 . "','0','"
                 . (int)$visible
                 . "','"
                 . $hp
                 . "','0','0','"
                 . (int)$nocomments
                 . "','1','"
                 . $address
                 . "','"
                 . (int)$submenu
                 . "', NOW())";

    if (!$result = $xoopsDB->query($sqlinsert)) {
        echo _C_ERRORINSERT;
    }

    $newId = $xoopsDB->getInsertId();

    if ($xoopsModuleConfig['cont_permits_advnaced'] > 0) {
        $moduleHandler = xoops_getHandler('module');

        $groupPermHandler = xoops_getHandler('groupperm');

        $module = $moduleHandler->getByDirname('content');

        foreach ($group_read_perms as $group) {
            $groupPermHandler->addRight('content_page_view', $newId, $group, $module->getVar('mid'));
        }

        if (2 == $xoopsModuleConfig['cont_permits_advnaced']) {
            foreach ($group_write_perms as $group) {
                $groupPermHandler->addRight('content_page_write', $newId, $group, $module->getVar('mid'));
            }
        }
    }

    if (isset($return) && 1 == $return) {
        echo "<script>window.opener.location.href='/modules/content/index.php?id=" . $newId . "';window.close();</script>";

    //redirect_header(XOOPS_URL."/modules/content/index.php?id=" . $id, 2, _C_DBUPDATED);
    } else {
        redirect_header('manage_content.php' . ((isset($showshort)) ? '?showshort=' . $showshort : ''), 2, _C_DBUPDATED);
    }
} else {
    // ------------------------------------------------------------------------- //

    // Show add content Page                                                     //

    // ------------------------------------------------------------------------- //

    global $xoopsDB, $xoopsModuleConfig, $xoopsUser, $xoopsModule;

    $menuModule = [];

    $moduleHandler = xoops_getHandler('module');

    $criteria = new CriteriaCompo(new Criteria('hasmain', 1));

    $criteria->add(new Criteria('isactive', 1));

    $modules = $moduleHandler->getList($criteria);

    asort($modules);

    print_header();

    echo '<script language="JavaScript" src="include/prototype.js"></script>';

    $currentParent = 0;

    if (isset($id)) {
        $result = $xoopsDB->query("SELECT CASE parent_id WHEN 0 THEN storyid ELSE parent_id END 'sortorder' FROM " . $xoopsDB->prefix('content') . " WHERE visible='1' AND storyid=" . $_GET['id']);

        [$currentParent] = $xoopsDB->fetchRow($result);
    }

    $contentItems = [];

    $result = $xoopsDB->query("SELECT *, blockid AS priority, 'content' AS type FROM " . $xoopsDB->prefix('content') . ' ORDER BY visible DESC, blockid');

    while (false !== ($tcontent = $xoopsDB->fetchArray($result))) {
        $contentItems[] = $tcontent;
    }

    $allMenuItems = return_children($contentItems, 0);

    $form = new XoopsThemeForm(_C_ADDCONTENT, 'form_name', 'add_content.php');

    $categoria_select = new XoopsFormSelect(_C_POSITION, 'parent_id', $currentParent);

    $categoria_select->addOption('', _C_MAINMENU);

    foreach ($allMenuItems as $ct_item) {
        $categoria_select->addOption($ct_item['storyid'], str_repeat('&nbsp;&nbsp;', ($ct_item['depth'] + 1)) . str_repeat('-', ($ct_item['depth'])) . $ct_item['title']);
    }

    $text_box = new XoopsFormText(_C_LINKNAME, 'title', 50, 255);

    $opProcedure = new XoopsFormRadio(_C_CNTTYP, 'op');

    $opProcedure->addOption('add', 'Content');

    $opProcedure->addOption('link', 'Link');

    $opProcedure->addOption('pagewrap', 'Pagewrap');

    $ptext_box = new XoopsFormText(_C_PAGENAME, 'ptitle', 50, 255);

    $url_box = new XoopsFormText(_C_EXTURL, 'externalURL', 50, 255);

    $newwindow_checkbox = new XoopsFormCheckBox('', 'newwindow', 0);

    $newwindow_checkbox->addOption(1, _C_NEWWINDOW);

    $visible_checkbox = new XoopsFormCheckBox('', 'visible', 1);

    $visible_checkbox->addOption(1, _C_VISIBLE);

    if ('1' == $xoopsModuleConfig['cont_wysiwyg']) {
        $fckeditor_root = XOOPS_ROOT_PATH . '/modules/content/admin/fckeditor/';

        require XOOPS_ROOT_PATH . '/modules/content/admin/fckeditor/fckeditor.php';

        ob_start();

        $oFCKeditor = new FCKeditor('message');

        $oFCKeditor->BasePath = XOOPS_URL . '/modules/content/admin/fckeditor/';

        if (isset($message)) {
            $oFCKeditor->Value = $message;
        }

        $oFCKeditor->Height = 500;

        $oFCKeditor->Create();

        $editor = new XoopsFormLabel(_C_CONTENT, ob_get_contents());

        ob_end_clean();
    } else {
        $editor = new XoopsFormDhtmlTextArea(_C_CONTENT, 'message', '', 37, 35);
    }

    //user permissions

    $moduleHandler = xoops_getHandler('module');

    $groupPermHandler = xoops_getHandler('groupperm');

    $module = $moduleHandler->getByDirname('content');

    $readpermits = new XoopsFormSelectGroup(_C_PERMITREAD, 'group_read_perms', true, 1, 4, true);

    $writepermits = new XoopsFormSelectGroup(_C_PERMITWRITE, 'group_write_perms', true, 1, 4, true);

    $option_tray = new XoopsFormElementTray(_OPTIONS, '<br>');

    $option_tray->addElement($newwindow_checkbox);

    $option_tray->addElement($visible_checkbox);

    if ('1' == $xoopsModuleConfig['cont_wysiwyg']) {
        $nohtml = new XoopsFormHidden(_DISABLEHTML, 0);

        $nosmile = new XoopsFormHidden(_DISABLESMILEY, 0);
    } else {
        $nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', 0);

        $nohtml_checkbox->addOption(1, _DISABLEHTML);

        $option_tray->addElement($nohtml_checkbox);
    }

    if ('1' == $xoopsModuleConfig['cont_wysiwyg']) {
        $form->addElement(new XoopsFormHidden('nobreaks', 1));
    } else {
        $breaks_checkbox = new XoopsFormCheckBox('', 'nobreaks', 0);

        $breaks_checkbox->addOption(1, _C_DISABLEBREAKS);

        $option_tray->addElement($breaks_checkbox);
    }

    $comments_checkbox = new XoopsFormCheckBox('', 'nocomments', 0);

    $comments_checkbox->addOption(1, _C_DISABLECOM);

    $option_tray->addElement($comments_checkbox);

    if (isset($return) && 1 == $return) {
        $return_field = (new XoopsFormHidden('return', 1));
    }

    $submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');

    $modules_select = new XoopsFormSelect(_C_MODULENAME, 'assoc_module');

    $modules_select->addOption('', _C_NONE);

    foreach ($modules as $key => $value) {
        $modules_select->addOption($key, $value);
    }

    $address_select = new XoopsFormSelect(_C_SELECTFILE, 'address');

    $address_select->addOption('', _C_NONE);

    $folder = dir('../content/');

    while ($file = $folder->read()) {
        if ('.' != $file && '..' != $file) {
            $address_select->addOption($file, '' . $file . '');
        }
    }

    $folder->close();

    $uplfile = new XoopsFormFile(_C_UPLOADFILE, 'fileupload', 500000);

    $header_img = new XoopsFormSelect(_C_SELECTIMG, 'header_img');

    $folder = dir('../headers/');

    $header_img->addOption('', _C_NONE);

    while ($file = $folder->read()) {
        if ('.' != $file && '..' != $file) {
            $header_img->addOption($file, '' . $file . '');
        }
    }

    $folder->close();

    $uplimage = new XoopsFormFile(_C_UPLOADIMG, 'imageupload', 500000);

    echo '<h4>' . _C_ADMINTITLE . '</h4>' . showMenu();

    echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='outer'>";

    echo '<form action="add_content.php" method="post" name="ctform" id="ctform" enctype="multipart/form-data">';

    show_form_line($categoria_select);

    show_form_line($text_box);

    echo '	<tr>
				<td class="even" valign="top" width="170"><strong>' . _C_CNTTYP . '</strong></td>
				<td class="even">
					<select id="op" name="op" onchange="showform(this.options[this.selectedIndex].value)">
						<option value="">Please Select</option>
						<option value="add">Content</option>
						<option value="link">Link</option>
						<option value="pagewrap">Pagewrap</option>
					</select></td>
		  	</tr>';

    echo '<tbody id="link" style="display:none;">';

    show_form_line($url_box);

    show_form_line($modules_select);

    echo '</tbody>';

    echo '<tbody id="content" style="display:none;">';

    show_form_line($ptext_box);

    show_form_line($header_img);

    show_form_line($uplimage);

    show_form_line($editor);

    echo '</tbody>';

    echo '<tbody id="pagewrap" style="display:none;">';

    show_form_line($address_select);

    show_form_line($uplfile);

    echo '</tbody>';

    echo '<tbody id="both" style="display:none;">';

    if ($xoopsModuleConfig['cont_permits_advnaced'] > 0) {
        show_form_line($readpermits);

        if (2 == $xoopsModuleConfig['cont_permits_advnaced']) {
            show_form_line($writepermits);
        }
    }

    show_form_line($option_tray);

    show_form_line($submit);

    echo '</tbody>';

    echo "</table>
	 	<script language='JavaScript'>
		 <!--
			function showform(det){
				if (det == 'add'){
					$('content').style.display = '';
					if (FCKeditorAPI.GetInstance('message'))
						FCKeditorAPI.GetInstance('message').MakeEditable();
				}else{
					$('content').style.display = 'none';
				}
					
				if (det == 'link')
					$('link').style.display = '';
				else
					$('link').style.display = 'none';
				
				if (det == 'pagewrap')
					$('pagewrap').style.display = '';
				else
					$('pagewrap').style.display = 'none';
				
				if (det != '')
					$('both').style.display = '';
				else
					$('both').style.display = 'none';
			}
			
		//-->
		</script>
			";

    if (isset($nohtml)) {
        echo $nohtml->render() . $nosmile->render();
    }

    if (isset($return_field)) {
        echo $return_field->render();
    }

    if (isset($showshort)) {
        echo "<input type='hidden' name='showshort' value='" . $showshort . "'>";
    }

    echo '</form>';

    print_footer();
}
