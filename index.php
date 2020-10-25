<?php

//  ------------------------------------------------------------------------ //
// Author: Ben Brown                                                         //
// Site: http://xoops.thehandcoders.com                                      //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include '../../mainfile.php';
global $xoopsModuleConfig;
$groupPermHandler = xoops_getHandler('groupperm');
$moduleHandler = xoops_getHandler('module');
$module = $moduleHandler->getByDirname('content');
($xoopsUser) ? $groups = $xoopsUser->getGroups() : $groups = XOOPS_GROUP_ANONYMOUS;

$id = $_GET['id'] ?? 0;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 0;

if (file_exists('language/' . $xoopsConfig['language'] . '/modinfo.php')) {
    include 'language/' . $xoopsConfig['language'] . '/modinfo.php';
} else {
    include 'language/english/modinfo.php';
}

$result = $xoopsDB->query('SELECT storyid, title, parent_id FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX));

while (false !== ($item = $xoopsDB->fetchArray($result))) {
    $allItems[] = $item;
}

if (0 != $id) {
    $result = $xoopsDB->queryF(
        'SELECT storyid, ptitle, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address, date, header_img FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE storyid=' . $id
    );
} else {
    $result = $xoopsDB->queryF(
        'SELECT storyid, ptitle, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address, date, header_img FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE homepage=1'
    );
}

if (0 == $xoopsDB->getRowsNum($result) || 1 == (int)$_GET['showerror']) {
    $result = $xoopsDB->queryF('SELECT storyid, ptitle, title, text, visible, nohtml, nosmiley, nobreaks, nocomments, link, address, date, header_img FROM ' . $xoopsDB->prefix(_MIC_CONTENT_PREFIX) . ' WHERE epage=1');

    [
        $storyid,
        $ptitle,
        $title,
        $text,
        $visible,
        $nohtml,
        $nosmiley,
        $nobreaks,
        $nocomments,
        $link,
        $address,
        $date,
        $header
    ] = $xoopsDB->fetchRow($result);
} else {
    [
        $storyid,
        $ptitle,
        $title,
        $text,
        $visible,
        $nohtml,
        $nosmiley,
        $nobreaks,
        $nocomments,
        $link,
        $address,
        $date,
        $header
    ] = $xoopsDB->fetchRow($result);

    $id = $storyid;

    if ($xoopsModuleConfig['cont_permits_advnaced'] > '0' && !$groupPermHandler->checkRight('content_page_view', $id, $groups, $module->getVar('mid'))) {
        redirect_header(XOOPS_URL, 2, _NOPERM, false);
    }
}

require_once XOOPS_ROOT_PATH . '/header.php';

if (1 == $link) {
    $includeContent = XOOPS_ROOT_PATH . '/modules/' . _MIC_DIR_NAME . '/content/' . $address;

    if (file_exists($includeContent)) {
        $GLOBALS['xoopsOption']['template_main'] = 'ct_index.html';

        ob_start();

        include $includeContent;

        $content = ob_get_contents();

        ob_end_clean();

        $xoopsTpl->assign('xoops_pagetitle', $title);

        if ($ptitle) {
            $xoopsTpl->assign('title', $ptitle);
        } else {
            $xoopsTpl->assign('title', $title);
        }

        $xoopsTpl->assign('content', $content);

        $xoopsTpl->assign('nocomments', $nocomments);

        $xoopsTpl->assign('mail_link', 'mailto:?subject=' . sprintf(_C_INTARTIGO, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_C_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/content/index.php?id=' . $id);

        $xoopsTpl->assign('lang_printerpage', _C_PRINTERFRIENDLY);

        $xoopsTpl->assign('lang_sendstory', _C_SENDSTORY);

        $xoopsTpl->assign('id', $id);

        $xoopsTpl->assign('date', $date);

        $xoopsTpl->assign('pagewrap', 1);
    } else {
        redirect_header('index.php', 1, _C_FILENOTFOUND);
    }
} else {
    //Should we show crumbs

    if (1 == $xoopsModuleConfig['cont_crumbs']) {
        $xoopsTpl->assign('breadcrumbs', array_reverse(backOneLevel($allItems, $id)));
    }

    //Should we redirect or continue with this page

    if (isset($address) && mb_strlen($address) > 0) {
        echo $address;

        //header("location: " . $address);

        exit;
    }

    $GLOBALS['xoopsOption']['template_main'] = 'ct_index.html';

    (isset($nohtml) && 1 == $nohtml) ? $html = 0 : $html = 1;

    (isset($nosmiley) && 1 == $nosmiley) ? $smiley = 0 : $smiley = 1;

    (isset($nobreaks) && 1 == $nobreaks) ? $breaks = 0 : $breaks = 1;

    $myts = MyTextSanitizer::getInstance();

    $contentPages = explode('[pagebreak]', $text);

    $pageCount = count($contentPages);

    //split up the pages

    if ($pageCount > 1) {
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $nav = new XoopsPageNav($pageCount, 1, $currentPage, 'page', "id=$id");

        $xoopsTpl->assign('nav', $nav->renderNav());

        $xoopsTpl->assign('content', $contentPages[$currentPage]);
    } else {
        $xoopsTpl->assign('content', $text);
    }

    if (1 == $xoopsModuleConfig['cont_title']) {
        $xoopsTpl->assign('xoops_pagetitle', $title);
    }

    if (isset($ptitle)) {
        $xoopsTpl->assign('title', $ptitle);
    } else {
        $xoopsTpl->assign('title', $title);
    }

    if (isset($header)) {
        $xoopsTpl->assign('header_image', $header);
    }

    $xoopsTpl->assign('edit_width', $xoopsModuleConfig['cont_edit_width']);

    $xoopsTpl->assign('edit_height', $xoopsModuleConfig['cont_edit_height']);

    if (0 == $xoopsModuleConfig['cont_edit_width'] || 0 == $xoopsModuleConfig['cont_edit_height']) {
        $xoopsTpl->assign('editpopup', '0');
    } else {
        $xoopsTpl->assign('editpopup', '1');
    }

    $xoopsTpl->assign('nocomments', $nocomments);

    $xoopsTpl->assign('mail_link', 'mailto:?subject=' . sprintf(_C_INTARTIGO, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_C_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/content/index.php?id=' . $id);

    $xoopsTpl->assign('lang_printerpage', _C_PRINTERFRIENDLY);

    $xoopsTpl->assign('lang_sendstory', _C_SENDSTORY);

    $xoopsTpl->assign('date', $date);

    $xoopsTpl->assign('id', $id);
}
$xoopsTpl->assign(
    'local_admin',
    ($groupPermHandler->checkRight('content_page_write', $id, $groups, $module->getVar('mid'))
     || $groupPermHandler->checkRight('content_page_add', $id, $groups, $module->getVar('mid'))
     || $groupPermHandler->checkRight('content_admin', null, $groups, $module->getVar('mid')))
);
$xoopsTpl->assign('permit_edit', $groupPermHandler->checkRight('content_page_write', $id, $groups, $module->getVar('mid')));
$xoopsTpl->assign('permit_add', $groupPermHandler->checkRight('content_page_add', $id, $groups, $module->getVar('mid')));
$xoopsTpl->assign('permit_admin', $groupPermHandler->checkRight('content_admin', null, $groups, $module->getVar('mid')));

require XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';

function backOneLevel($items, $ctid)
{
    foreach ($items as $item) {
        if ($item['storyid'] == $ctid) {
            $crumbsout[] = $item;

            $crumbsout = array_merge($crumbsout, backOneLevel($items, $item['parent_id']));
        }
    }

    return $crumbsout;
}
