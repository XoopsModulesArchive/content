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
$menuModule     = [];
$moduleHandler = xoops_getHandler('module');
$criteria       = new CriteriaCompo(new Criteria('hasmain', 1));
$criteria->add(new Criteria('isactive', 1));
$modules = $moduleHandler->getList($criteria);
asort($modules);

$groupPermHandler = xoops_getHandler('groupperm');
$module           = $moduleHandler->getByDirname('content');
($xoopsUser) ? $groups = $xoopsUser->getGroups() : $groups = XOOPS_GROUP_ANONYMOUS;
$allowedItems = $groupPermHandler->getItemIds('content_page_write', $groups, $module->getVar('mid'));

if (!$groupPermHandler->checkRight('content_page_write', $id, $groups, $module->getVar('mid'))) {
    //redirect_header(XOOPS_URL, 2, _NOPERM, false);
}
// ------------------------------------------------------------------------- //
// Do the edit of the Content                                                //
// ------------------------------------------------------------------------- //
if ($op == 'add' || $op == 'link') {
    $myts = MyTextSanitizer::getInstance();

    $title  = $myts->addSlashes($title);
    $ptitle = $myts->addSlashes($ptitle);
    //$message=$myts->addSlashes($message);

    //update permissions
    $moduleHandler   = xoops_getHandler('module');
    $groupPermHandler = xoops_getHandler('groupperm');
    $module           = $moduleHandler->getByDirname('content');
    $groupPermHandler->DeleteByModule($module->getVar('mid'), 'content_page_view', $id);
    if ($xoopsModuleConfig['cont_permits_advnaced'] > 0) {
        $groupPermHandler->DeleteByModule($module->getVar('mid'), 'content_page_view', $id);
        foreach ($group_read_perms as $group) {
            $groupPermHandler->addRight('content_page_view', $id, $group, $module->getVar('mid'));
        }
        if ($xoopsModuleConfig['cont_permits_advnaced'] == 2) {
            $groupPermHandler->DeleteByModule($module->getVar('mid'), 'content_page_write', $id);
            foreach ($group_write_perms as $group) {
                $groupPermHandler->addRight('content_page_write', $id, $group, $module->getVar('mid'));
            }
        }
    }

    if ($_FILES[imageupload]) {
        $uploadpath      = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/headers/';
        $source          = $_FILES[imageupload][tmp_name];
        $fileupload_name = $_FILES[imageupload][name];
        if (($source != 'none') && ($source != '')) {
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

    if ($op == 'add') {
        $externalURL = '';
    }

    $sqlinsert = 'UPDATE '
                 . $xoopsDB->prefix(_MIC_CONTENT_PREFIX)
                 . " SET parent_id='"
                 . (int)$parent_id
                 . "', title='"
                 . $title
                 . "', ptitle='"
                 . $ptitle
                 . "', text='"
                 . $message
                 . "', visible='"
                 . (int)$visible
                 . "', nohtml='"
                 . (int)$nohtml
                 . "', nosmiley='"
                 . (int)$nosmiley
                 . "', nobreaks='"
                 . (int)$nobreaks
                 . "', nocomments='"
                 . (int)$nocomments
                 . "', address='"
                 . $externalURL
                 . "', submenu='"
                 . (int)$submenu
                 . "', newwindow='"
                 . (int)$newwindow
                 . "', date=NOW(), link=0, header_img='"
                 . $header_img
                 . "' WHERE storyid='"
                 . (int)$id
                 . "'";

    if (!$result = $xoopsDB->query($sqlinsert)) {
        echo _C_ERRORINSERT;
    }
    if ($return == 1) {
        echo '<script>window.opener.location.reload(true);window.close();</script>';
        //redirect_header(XOOPS_URL."/modules/content/index.php?id=" . $id, 2, _C_DBUPDATED);
    } else {
        redirect_header('manage_content.php' . ((isset($showshort)) ? '?showshort=' . $showshort : ''), 2, _C_DBUPDATED);
    }
} elseif ($op == 'pagewrap') {
    $myts = MyTextSanitizer::getInstance();

    $title   = $myts->addSlashes($title);
    $address = $myts->addSlashes($address);

    //update permissions
    $moduleHandler   = xoops_getHandler('module');
    $groupPermHandler = xoops_getHandler('groupperm');
    $module           = $moduleHandler->getByDirname('content');

    if ($xoopsModuleConfig['cont_permits_advnaced'] > 0) {
        $groupPermHandler->DeleteByModule($module->getVar('mid'), 'content_page_view', $id);
        foreach ($group_read_perms as $group) {
            $groupPermHandler->addRight('content_page_view', $id, $group, $module->getVar('mid'));
        }
        if ($xoopsModuleConfig['cont_permits_advnaced'] == 2) {
            $groupPermHandler->DeleteByModule($module->getVar('mid'), 'content_page_write', $id);
            foreach ($group_write_perms as $group) {
                $groupPermHandler->addRight('content_page_write', $id, $group, $module->getVar('mid'));
            }
        }
    }

    if ($_FILES[fileupload]) {
        $uploadpath      = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/content/';
        $source          = $_FILES[fileupload][tmp_name];
        $fileupload_name = $_FILES[fileupload][name];
        if (($source != 'none') && ($source != '')) {
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

    $sqlinsert = 'UPDATE '
                 . $xoopsDB->prefix(_MIC_CONTENT_PREFIX)
                 . " SET parent_id='"
                 . (int)$parent_id
                 . "', title='"
                 . $title
                 . "', visible='"
                 . (int)$visible
                 . "', nocomments='"
                 . (int)$nocomments
                 . "', address='"
                 . $address
                 . "', submenu='"
                 . $submenu
                 . "', date=NOW(), link=1 WHERE storyid='"
                 . (int)$id
                 . "'";
    if (!$result = $xoopsDB->query($sqlinsert)) {
        echo _C_ERRORINSERT;
    }
    redirect_header('manage_content.php' . ((isset($showshort)) ? '?showshort=' . $showshort : ''), 2, _C_DBUPDATED);
} else {
    // ------------------------------------------------------------------------- //
    // Show Edit Content Page                                                    //
    // ------------------------------------------------------------------------- //
    global $xoopsDB, $xoopsModuleConfig;
    $myts = MyTextSanitizer::getInstance();
    print_header();

    $result = $xoopsDB->query(
        'SELECT storyid, parent_id, ptitle, title, text, visible, nohtml, nosmiley, 
							   nobreaks, nocomments, address, submenu, newwindow, assoc_module, link, header_img FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE storyid=' . (int)$id
    );

    [
        $storyid,
        $parent_id,
        $ptitle,
        $title,
        $text,
        $visible,
        $nohtml,
        $nosmiley,
        $nobreaks,
        $nocomments,
        $externalURL,
        $submenu,
        $newwindow,
        $assoc_module,
        $link,
        $header_img,
    ] = $xoopsDB->fetchRow($result);

    $contentItems = [];
    $result       = $xoopsDB->query("SELECT *, blockid AS priority, 'content' AS type FROM " . $xoopsDB->prefix('content') . ' ORDER BY visible DESC, blockid');
    while (false !== ($tcontent = $xoopsDB->fetchArray($result))) {
        $contentItems[] = $tcontent;
    }
    $allMenuItems = return_children($contentItems, 0);

    $title   = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
    $message = $text;

    $form             = new XoopsThemeForm(_C_EDITCONTENT, 'form_name', 'edit_content.php');
    $categoria_select = new XoopsFormSelect(_C_POSITION, 'parent_id', $parent_id);
    $categoria_select->addOption('', _C_MAINMENU);
    foreach ($allMenuItems as $ct_item) {
        $categoria_select->addOption($ct_item['storyid'], str_repeat('&nbsp;&nbsp;', ($ct_item['depth'] + 1)) . str_repeat('-', ($ct_item['depth'])) . $ct_item['title']);
    }
    $form->addElement($categoria_select);
    $text_box  = new XoopsFormText(_C_LINKNAME, 'title', 50, 255, $title);
    $ptext_box = new XoopsFormText(_C_PAGENAME, 'ptitle', 50, 255, $ptitle);
    $form->addElement($text_box);
    $url_box = new XoopsFormText(_C_EXTURL, 'externalURL', 50, 255, $externalURL);
    $form->addElement($url_box);

    $newwindow_checkbox = new XoopsFormCheckBox('', 'newwindow', $newwindow);
    $newwindow_checkbox->addOption(1, _C_NEWWINDOW);

    $visible_checkbox = new XoopsFormCheckBox('', 'visible', $visible);
    $visible_checkbox->addOption(1, _C_VISIBLE);

    if ($xoopsModuleConfig['cont_wysiwyg'] == '1') {
        $fckeditor_root = XOOPS_ROOT_PATH . '/modules/content/admin/fckeditor/';
        require XOOPS_ROOT_PATH . '/modules/content/admin/fckeditor/fckeditor.php';
        ob_start();
        $oFCKeditor           = new FCKeditor('message');
        $oFCKeditor->BasePath = XOOPS_URL . '/modules/content/admin/fckeditor/';
        $oFCKeditor->Value    = $message;
        $oFCKeditor->Height   = 500;
        $oFCKeditor->Create();
        $editor = new XoopsFormLabel(_C_CONTENT, ob_get_contents());
        ob_end_clean();
    } else {
        $editor = new XoopsFormDhtmlTextArea(_C_CONTENT, 'message', $message, 37, 35);
    }

    $form->addElement($t_area);

    //user permissions
    $moduleHandler   = xoops_getHandler('module');
    $groupPermHandler = xoops_getHandler('groupperm');
    $module           = $moduleHandler->getByDirname('content');
    $page_groups      = $groupPermHandler->getGroupIds('content_page_view', $id, $module->getVar('mid'));
    $readpermits      = new XoopsFormSelectGroup(_C_PERMITREAD, 'group_read_perms', true, $page_groups, 4, true);

    $page_groups  = $groupPermHandler->getGroupIds('content_page_write', $id, $module->getVar('mid'));
    $writepermits = new XoopsFormSelectGroup(_C_PERMITWRITE, 'group_write_perms', true, $page_groups, 4, true);

    $form->addElement($permissionField);

    $option_tray = new XoopsFormElementTray(_OPTIONS, '<br>');
    $option_tray->addElement($newwindow_checkbox);
    $option_tray->addElement($visible_checkbox);

    if ($xoopsModuleConfig['cont_wysiwyg'] == '1') {
        $nohtml  = new XoopsFormHidden(_DISABLEHTML, 0);
        $nosmile = new XoopsFormHidden(_DISABLESMILEY, 0);
    } else {
        $nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', 0);
        $nohtml_checkbox->addOption(1, _DISABLEHTML);
        $option_tray->addElement($nohtml_checkbox);
    }
    if ($xoopsModuleConfig['cont_wysiwyg'] == '1') {
        $form->addElement(new XoopsFormHidden('nobreaks', 1));
    } else {
        $breaks_checkbox = new XoopsFormCheckBox('', 'nobreaks', 0);
        $breaks_checkbox->addOption(1, _C_DISABLEBREAKS);
        $option_tray->addElement($breaks_checkbox);
    }

    $comments_checkbox = new XoopsFormCheckBox('', 'nocomments', $nocomments);
    $comments_checkbox->addOption(1, _C_DISABLECOM);
    $option_tray->addElement($comments_checkbox);

    if (isset($return) && $return == 1) {
        $return_field = (new XoopsFormHidden('return', 1));
    }

    $editid = new XoopsFormHidden('id', $storyid);

    $submit = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');

    $modules_select = new XoopsFormSelect(_C_MODULENAME, 'assoc_module', $assoc_module);
    $modules_select->addOption('', _C_NONE);

    foreach ($modules as $key => $value) {
        $modules_select->addOption($key, $value);
    }

    $form->addElement($submit);

    $address_select = new XoopsFormSelect(_C_SELECTFILE, 'address', $externalURL);
    $address_select->addOption('', _C_NONE);
    $folder = dir('../content/');
    while ($file = $folder->read()) {
        if ($file != '.' && $file != '..') {
            $address_select->addOption($file, '' . $file . '');
        }
    }
    $folder->close();

    $uplfile = new XoopsFormFile(_C_UPLOADFILE, 'fileupload', 500000);

    $header_img = new XoopsFormSelect(_C_SELECTIMG, 'header_img', $header_img);
    $folder     = dir('../headers/');
    $header_img->addOption('', _C_NONE);
    while ($file = $folder->read()) {
        if ($file != '.' && $file != '..') {
            $header_img->addOption($file, '' . $file . '');
        }
    }
    $folder->close();
    $uplimage = new XoopsFormFile(_C_UPLOADIMG, 'imageupload', 500000);

    //$form->display();
    echo '<h4>' . _C_ADMINTITLE . '</h4>' . showMenu();
    echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='outer'>";
    echo '<form action="edit_content.php" method="post" name="ctform" id="ctform" enctype="multipart/form-data">';
    show_form_line($categoria_select);
    show_form_line($text_box);
    echo '	<tr>
				<td class="even" valign="top" width="170"><strong>' . _C_CNTTYP . '</strong></td>
				<td class="even">
					<select id="op" name="op" onchange="showform(this.options[this.selectedIndex].value)">
						<option value="add"' . (($link != 1 && (!isset($externalURL) || strlen(trim($externalURL)) == 0)) ? ' selected' : '') . '>Content</option>
						<option value="link"' . (($link != 1 && isset($externalURL) && strlen(trim($externalURL)) > 0) ? ' selected' : '') . '>Link</option>
						<option value="pagewrap"' . (($link == 1) ? ' selected' : '') . '>Pagewrap</option>
					</select></td>
		  	</tr>';

    echo '<tbody id="link" ' . (($link != 1 && isset($externalURL) && strlen(trim($externalURL)) > 0) ? '' : ' style="display:none;"') . '>';
    show_form_line($url_box);
    show_form_line($modules_select);
    echo '</tbody>';

    echo '<tbody id="content"' . (($link != 1 && (!isset($externalURL) || strlen(trim($externalURL)) == 0)) ? '' : ' style="display:none;"') . '>';
    show_form_line($ptext_box);
    echo '<tr>
				<td class="even"><strong>' . $header_img->getCaption() . '</strong></td>
				<td class="even">' . $header_img->render() . '&nbsp;&nbsp;&nbsp;<a href="javascript:newWindow(\'manage_files.php?loc=1&showshort=1\', \'manage\', \'height=130,width=350,toolbars=0,statusbar=0,menubar=0\')">' . _C_DELETEFILES . '</a></td>
			  </tr>';
    show_form_line($uplimage);
    show_form_line($editor);
    echo '</tbody>';

    echo '<tbody id="pagewrap" ' . (($link == 1) ? '' : ' style="display:none;"') . '>';
    echo '<tr>
				<td class="even"><strong>' . $address_select->getCaption() . '</strong></td>
				<td class="even">' . $address_select->render() . '&nbsp;&nbsp;&nbsp;<a href="javascript:newWindow(\'manage_files.php?showshort=1\', \'manage\', \'height=130,width=350,toolbars=0,statusbar=0,menubar=0\')">' . _C_DELETEFILES . '</a></td>
			  </tr>';
    show_form_line($uplfile);
    echo '</tbody>';

    echo '<tbody id="both">';
    if ($xoopsModuleConfig['cont_permits_advnaced'] > 0) {
        show_form_line($readpermits);
        if ($xoopsModuleConfig['cont_permits_advnaced'] == 2) {
            show_form_line($writepermits);
        }
    }
    show_form_line($option_tray);
    echo $editid->render();
    if (isset($nohtml)) {
        echo $nohtml->render() . $nosmile->render();
    }
    if (isset($return_field)) {
        echo $return_field->render();
    }
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
    if (isset($showshort)) {
        echo "<input type='hidden' name='showshort' value='" . $showshort . "'>";
    }
    echo '</form>'; ?>
    <script>
        //////////////////////////////////////////
        // Function for Opening New Window with Event-Specified Parameters
        function newWindow(filePath, winName, winProperties) {
            NewWin = window.open(filePath, winName, winProperties);
            NewWin.moveTo(50, 50);
            NewWin.focus();
        }

        //////////////////////////////////////////
    </script>
    <?php print_footer();
}
?>
