<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
require_once 'admin_header.php';

$xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
global $op;

// ------------------------------------------------------------------------- //
// Update Content -> Show Content Page                                       //
// ------------------------------------------------------------------------- //
if ($op == 'update') {
    foreach ($id as $storyid) {
        ($storyid == (int)$homepage[0]) ? $hp = 1 : $hp = 0;
        ($storyid == (int)$epage[0]) ? $ep = 1 : $ep = 0;
        $sqlinsert = 'UPDATE '
                     . $xoopsDB->prefix(_MIC_CONTENT_PREFIX)
                     . " SET parent_id='"
                     . (int)$parent_id[$storyid]
                     . "', blockid='"
                     . (int)$blockid[$storyid]
                     . "', visible='"
                     . (int)$visible[$storyid]
                     . "', homepage='"
                     . $hp
                     . "', epage='"
                     . $ep
                     . "', nocomments='"
                     . (($nocomments[$storyid]) ? 0 : 1)
                     . "', submenu='"
                     . (int)$submenu[$storyid]
                     . "', date=NOW() WHERE storyid='"
                     . (int)$storyid
                     . "'";
        if (!$result = $xoopsDB->query($sqlinsert)) {
            echo _C_ERRORINSERT;
        }
    }
    redirect_header('manage_content.php' . ((isset($showshort)) ? '?showshort=' . $showshort : ''), 2, _C_DBUPDATED);
} else {
    // ------------------------------------------------------------------------- //
    // Show Manage Content Form                                                  //
    // ------------------------------------------------------------------------- //

    print_header();
    echo '<script language="JavaScript" src="include/prototype.js"></script>';
    echo '
			<style>
				.hideme{
					display:none;
				}
			</style>
			<script language="JavaScript">
			///////////////////////////////////////
			//RollOver Routines
			//Initialize variables
			var rollOverImages = new Array(), overIndicator = "_o";
			//Create New RollOver Object
			function createRollOver(img) {if (!rollOverImages[img.name] && document.images) rollOverImages[img.name] = new rollOver(img.src);}		
			//Function for Rollover On State
			function imgOn(imgName) {if (document.images && rollOverImages[imgName]) $(imgName).src = rollOverImages[imgName].on.src;}
			//Function for Rollover Off State
			function imgOff(imgName) {if (document.images && rollOverImages[imgName]) $(imgName).src = rollOverImages[imgName].off.src;}
			//Constructor
			function rollOver(on) {this.on = new Image();this.on.src = on.substring(0,on.lastIndexOf(".")) + overIndicator + on.substring(on.lastIndexOf("."),on.length); this.off = new Image();this.off.src = on;}
			//////////////////////////////////////////
			
			progress_icon = new Image();
			progress_icon.src = "' . XOOPS_URL . '/modules/content/images/indicator_arrows.gif";
			
			function showitems(itemName){
				$("control-" + itemName).src = progress_icon.src;
				window.setTimeout("process_showitems(\'" + itemName + "\')", 100);
			}
			
			function process_showitems(itemName){
				elements = document.getElementsByClassName("parent-" + itemName, $("displaytable"))
				if (elements && Element.hasClassName(elements[0], "hideme")){
					for (i=0; ele = elements[i]; i++){
						Element.removeClassName(ele, "hideme");
					}
					imgOn("control-" + itemName);
				} else {
					hide_items(elements, itemName)
					imgOff("control-" + itemName);
				}
			}
			
			function hide_items(items, itemNum){
				var ele, i;
				for (i=0; ele = items[i]; i++){
					if (!Element.hasClassName(ele, "hideme")){
						Element.addClassName(ele, "hideme");
					}
					if (ele.id.length > 0){
						var elements = document.getElementsByClassName("parent-" + ele.id, $("displaytable"))
						if (elements.length > 0)
							hide_items(elements, ele.id)
					}
				}
			}
			
			function showallitems(){
				$("control-all").src = progress_icon.src;
				window.setTimeout("process_showallitems()", 100);
			}
			
			function process_showallitems(){
				if (document.getElementsByClassName("hideme", $("displaytable")).length > 0){
					elements = $("displaytable").getElementsByTagName("tr")
					for (i=0; ele = elements[i]; i++){
						if (Element.hasClassName(elements[i], "hideme")){
							Element.removeClassName(ele, "hideme");
						}
					}
					elements = elements = document.getElementsByClassName("folder", $("displaytable"))
					for (i=0; ele = elements[i]; i++){
						imgOn(ele.id);
					}
					imgOn("control-all");
				} else {
					elements = $("displaytable").getElementsByTagName("tr")
					for (i=0; ele = elements[i]; i++){
						if (!Element.hasClassName(elements[i], "even") && !Element.hasClassName(elements[i], "head")){
							Element.addClassName(ele, "hideme");
						}
					}
					elements = elements = document.getElementsByClassName("folder", $("displaytable"))
					for (i=0; ele = elements[i]; i++){
						imgOff(ele.id);
					}
					imgOff("control-all");
				}
			}
			
			</script>';
    global $xoopsDB;
    $myts = MyTextSanitizer::getInstance();

    echo '';
    echo '
			<h4>' . _C_ADMINTITLE . '</h4>' . showMenu() . "
			<table border='0' cellpadding='0' cellspacing='1' width='100%' class='outer' id='displaytable'>
				<tr class='even'>
					<td colspan='7'>" . displayFilterForm() . '</td>
				</tr>';
    if (isset($filterSQL)) {
        $filterSQL = ' WHERE ' . $filterSQL;
    }
    echo "
				<form method='post'>
				<tr class='head'>
					<td><a href='#' onclick='showallitems();return false;'><img onload='createRollOver(this)' name='control-all' id='control-all' src='../images/folder.png' alt='' border='0' align='absmiddle' height='16' width='16'></a>&nbsp;<b>" . _C_LINKNAME . "</b></td>
					<td width='60'><b>" . _C_POSITION . "</b></td>
					<td width='60'><b>" . _C_LINKID . "</b></td>
					<td width='40'><b>" . _C_HOMEPAGE . "</b></td>
					<td width='40'><b>" . _C_ERROR . "</b></td>
					<td width='50'><b>" . _C_VISIBLE . "</b></td>
					<td width='100'><b>" . _C_ACTION . '</b></td>
				</tr>';
    $contentItems = [];
    $result       = $xoopsDB->query("SELECT *, blockid AS priority, 'content' AS type FROM " . $xoopsDB->prefix('content') . ' ' . $filterSQL . ' ORDER BY blockid');
    while (false !== ($tcontent = $xoopsDB->fetchArray($result))) {
        $contentItems[] = $tcontent;
    }

    if ($filterSQL == '') {
        $sortedContent = return_children($contentItems, 0);
    } else {
        $sortedContent = $contentItems;
    }

    unset($contentItems);
    $contentItems = [];
    $result       = $xoopsDB->query("SELECT *, blockid AS priority, 'content' AS type FROM " . $xoopsDB->prefix('content') . ' ORDER BY visible DESC, blockid');
    while (false !== ($tcontent = $xoopsDB->fetchArray($result))) {
        $contentItems[] = $tcontent;
    }
    $allItems = return_children($contentItems, 0);
    foreach ($sortedContent as $tcontent) {
        if ((isset($tcontent['depth']) && $tcontent['depth'] == 0) || $filterSQL != '') {
            print_item($tcontent, $xoopsModule->dirname(), $allItems, $myts);
            foreach (return_children($contentItems, $tcontent['storyid'], 1) as $child) {
                print_item($child, $xoopsModule->dirname(), $allItems, $myts);
            }
        }
    }

    echo "</table><br>
	  	<div align='center'>
			<input type='hidden' name='op' value='update'>";
    if (isset($showshort)) {
        echo "<input type='hidden' name='showshort' value='" . $showshort . "'>";
    }

    echo "<input type='submit' name='submit' value=" . _SUBMIT . '></div>';
    echo '</form>';

    print_footer();
}

function print_item($tcontent, $dirname, $allMenuItems, $txtSant)
{
    global $xoopsModuleConfig, $showshort;
    $menu = '<select name="parent_id[' . $tcontent['storyid'] . ']">';
    $menu .= '<option>' . _C_MAINMENU . '</option>';
    foreach ($allMenuItems as $ct_item) {
        $menu .= '<option ';
        if ($tcontent['parent_id'] == $ct_item['storyid']) {
            $menu .= 'selected="selected" ';
        }
        $menu .= 'value="' . $ct_item['storyid'] . '">' . str_repeat('&nbsp;&nbsp;', ($ct_item['depth'] + 1)) . str_repeat('-', ($ct_item['depth'])) . $ct_item['title'] . '</option>';
    }
    $menu .= '</select>';
    echo '
			<tr ';
    if (isparent($allMenuItems, $tcontent['storyid'])) {
        echo 'id="' . $tcontent['storyid'] . '" ';
    }

    echo " class='" . ((!isset($tcontent['depth']) || $tcontent['depth'] == 0) ? 'even' : 'odd parent-' . $tcontent['parent_id'] . (($xoopsModuleConfig['cont_collapse'] == '1') ? ' hideme ' : '')) . "'>";
    if (!isset($tcontent['depth'])) {
        $tcontent['depth'] = 0;
    }
    echo '  <td>';
    if (isset($tcontent['depth']) && isparent($allMenuItems, $tcontent['storyid'])) {
        if (isset($tcontent['depth']) && $tcontent['depth'] != 0) {
            echo '<img src="../images/spacer.gif" alt="" width="' . ($tcontent['depth'] * 8) . '" height="10" border="0" align="absmiddle">';
            echo '<img src="../images/child_mark.png" alt="" width="6" height="17" border="0" align="absmiddle">';
        }
        echo '<a href="#" onclick="showitems('
             . $tcontent['storyid']
             . ');return false;"><img onload="createRollOver(this)" name="control-'
             . $tcontent['storyid']
             . '" id="control-'
             . $tcontent['storyid']
             . '" src="../images/folder.png" alt="" border="0" align="absmiddle" height="16" width="16" class="folder"></a>';
    } else {
        if (isset($tcontent['depth']) && $tcontent['depth'] != 0) {
            echo '<img src="../images/spacer.gif" alt="" width="' . ($tcontent['depth'] * 8) . '" height="10" border="0" align="absmiddle">';
            echo '<img src="../images/child_mark.png" alt="" width="6" height="17" border="0" align="absmiddle">';
        }
        echo '<img src="../images/page.png" alt="" border="0" align="absmiddle" height="16" width="16" >';
    }
    echo "
				<a href='edit_content.php?id=" . $tcontent['storyid'] . ((isset($showshort)) ? '&showshort=' . $showshort : '') . "'>" . $txtSant->htmlSpecialChars($tcontent['title'], 0, 0, 0) . "</a></td>
				<td>$menu</td>
				<td><nobr>" . str_repeat('--->', $tcontent['depth']) . "<input type='hidden' name='id[]' value='" . $tcontent['storyid'] . "'><input type='text' name='blockid[" . $tcontent['storyid'] . "]' size='2' maxlength='2' value='" . $tcontent['blockid'] . "'></nobr></td>
				<td align='center'><input type='radio' name='homepage[]' value='" . $tcontent['storyid'] . "' " . (($tcontent['homepage'] == '1') ? 'checked' : '') . "></td>
				<td align='center'><input type='radio' name='epage[]' value='" . $tcontent['storyid'] . "' " . (($tcontent['epage'] == '1') ? 'checked' : '') . "></td>
				<td align='center'>
					<input type='checkbox'  name='visible[" . $tcontent['storyid'] . "]' value='1' " . (($tcontent['visible'] == '1') ? 'CHECKED' : '') . "></td>
				<td><nobr><a href='" . XOOPS_URL . '/modules/' . $dirname . '/index.php?id=' . $tcontent['storyid'] . "'><img src='../images/go.png' alt=" . _C_GO . " height='16' width='16'></a>
					<a href='edit_content.php?id=" . $tcontent['storyid'] . ((isset($showshort)) ? '&showshort=' . $showshort : '') . "'><img src='../images/edit.png' alt=" . _C_EDIT . " height='16' width='16'></a>
					<a href='copy_content.php?id=" . $tcontent['storyid'] . ((isset($showshort)) ? '&showshort=' . $showshort : '') . "'><img src='../images/copy.png' height='16' width='16'></a>
					<a href='add_content.php?id=" . $tcontent['storyid'] . ((isset($showshort)) ? '&showshort=' . $showshort : '') . "'><img src='../images/add.png' alt=" . _C_ADD . " height='16' width='16'></a>
					<a href='delete_content.php?id=" . $tcontent['storyid'] . ((isset($showshort)) ? '&showshort=' . $showshort : '') . "'><img src='../images/delete.png' alt=" . _C_DELETE . " height='16' width='16'></a></nobr></td>
		</tr>";
}


