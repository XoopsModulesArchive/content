<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

$modversion['name'] = _MIC_CONTENT_NAME;
$modversion['version'] = 1;
$modversion['author'] = 'Ben Brown';
$modversion['description'] = _MIC_CONTENT_DESC;
$modversion['credits'] = 'The Handcoders, Reinarz & Associates';
$modversion['license'] = 'GPL see LICENSE';
$modversion['help'] = 'http://xoops.thehandcoders.com';
$modversion['official'] = 1;
$modversion['image'] = 'images/logo.gif';
$modversion['dirname'] = _MIC_DIR_NAME;

// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = _MIC_DIR_NAME;

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'content_search';

// Menu
$modversion['hasMain'] = 1;
global $xoopsDB;

// Submenu Items

$result = $xoopsDB->query('SELECT storyid, title, homepage, submenu FROM ' . $xoopsDB->prefix('content') . " WHERE homepage='0' AND submenu='1' ORDER BY title");
$i = 1;

while (list($storyid, $title) = $xoopsDB->fetchRow($result)) {
    $modversion['sub'][$i]['name'] = $title;

    $modversion['sub'][$i]['url'] = 'index.php?id=' . $storyid . '';

    $i++;
}

// Smarty
$modversion['use_smarty'] = 1;

// Templates
$modversion['templates'][1]['file'] = 'ct_index.html';
$modversion['templates'][1]['description'] = _MIC_TEMP_NAME1;

// Blocks
$modversion['blocks'][1]['file'] = 'ct_navigation.php';
$modversion['blocks'][1]['name'] = _MIC_C_BNAME1;
$modversion['blocks'][1]['description'] = _MIC_C_BNAME1_DESC;
$modversion['blocks'][1]['show_func'] = 'content_block_nav';
$modversion['blocks'][1]['template'] = 'ct_nav_block.html';

$modversion['blocks'][2]['file'] = 'ct_sitenavigation.php';
$modversion['blocks'][2]['name'] = _MIC_C_BNAME2;
$modversion['blocks'][2]['description'] = _MIC_C_BNAME2_DESC;
$modversion['blocks'][2]['show_func'] = 'site_block_nav';
$modversion['blocks'][2]['edit_func'] = 'edit_block_nav';
$modversion['blocks'][2]['options'] = '10';
$modversion['blocks'][2]['template'] = 'ct_site_nav_block.html';

$modversion['blocks'][3]['file'] = 'ct_dhtml_sitenavigation.php';
$modversion['blocks'][3]['name'] = _MIC_C_BNAME3;
$modversion['blocks'][3]['description'] = _MIC_C_BNAME3_DESC;
$modversion['blocks'][3]['show_func'] = 'site_block_dhtml_nav';
$modversion['blocks'][3]['template'] = 'ct_dhtml_site_nav_block.html';

$modversion['blocks'][4]['file'] = 'ct_section_navigation.php';
$modversion['blocks'][4]['name'] = 'Section Navigation';
$modversion['blocks'][4]['description'] = 'Displays only navigation for current section';
$modversion['blocks'][4]['show_func'] = 'site_block_section_nav';
$modversion['blocks'][4]['edit_func'] = 'edit_block_sec_nav';
$modversion['blocks'][4]['options'] = '10';
$modversion['blocks'][4]['template'] = 'ct_section_nav_block.html';

$modversion['blocks'][5]['file'] = 'ct_dhtml_horizontal.php';
$modversion['blocks'][5]['name'] = 'Horizontal DHTML Nav';
$modversion['blocks'][5]['description'] = 'Generate a Horizontal DHTML Navigation Menu';
$modversion['blocks'][5]['show_func'] = 'site_block_horz_dhtml_nav';
$modversion['blocks'][5]['template'] = 'ct_dhtml_horz_site_nav_block.html';

$modversion['blocks'][6]['file'] = 'ct_top_navigation.php';
$modversion['blocks'][6]['name'] = 'Horizontal Seciontal Nav';
$modversion['blocks'][6]['description'] = 'Generate a Horizontal Section Only Navigation Menu';
$modversion['blocks'][6]['show_func'] = 'content_block_top_nav';
$modversion['blocks'][6]['template'] = 'ct_top_navigation.html';

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'index.php';

$modversion['config'][1]['name'] = 'cont_wysiwyg';
$modversion['config'][1]['title'] = '_MIC_WYSIWYG';
$modversion['config'][1]['formtype'] = 'yesno';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 1;

$modversion['config'][2]['name'] = 'cont_crumbs';
$modversion['config'][2]['title'] = '_MIC_CRUMBS';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;

$modversion['config'][3]['name'] = 'cont_title';
$modversion['config'][3]['title'] = '_MIC_SHOWTITLE';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 1;

$modversion['config'][4]['name'] = 'cont_collapse';
$modversion['config'][4]['title'] = '_MIC_COLLAPSE';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 1;

$modversion['config'][5]['name'] = 'cont_permits_advnaced';
$modversion['config'][5]['title'] = '_MIC_LEVELS';
$modversion['config'][5]['description'] = '_MIC_LEVELS_DESC';
$modversion['config'][5]['formtype'] = 'select';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 1;
$modversion['config'][5]['options'] = ['_MIC_NONE' => 0, '_MIC_BASIC' => 1];

$modversion['config'][5]['name'] = 'cont_edit_height';
$modversion['config'][5]['title'] = '_MIC_POP_H';
$modversion['config'][5]['description'] = '_MIC_POP_DESC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = 640;

$modversion['config'][6]['name'] = 'cont_edit_width';
$modversion['config'][6]['title'] = '_MIC_POP_W';
$modversion['config'][6]['description'] = '_MIC_POP_DESC';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = 640;
