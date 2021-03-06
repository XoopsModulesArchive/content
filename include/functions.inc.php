//used by most of the blocks

function getMenuAsArray(){
global $xoopsModule, $xoopsUser;
//Modules
$menuModule = array();
$moduleHandler = xoops_getHandler('module');
$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
$criteria->add(new Criteria('weight', 0, '>'));
$criteria->add(new Criteria('isactive', 1));
$modules = $moduleHandler->getObjects($criteria, true);
$modulepermHandler = xoops_getHandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$read_allowed =& $modulepermHandler->getItemIds('module_read', $groups);
foreach (array_keys($modules) as $i) {
if (in_array($i, $read_allowed)) {
$menuModule[$i]['title'] = $modules[$i]->getVar('name');
$menuModule[$i]['url'] = XOOPS_URL."/modules/".$modules[$i]->getVar('dirname')."/";
$menuModule[$i]['priority'] = $modules[$i]->getVar('weight');
$menuModule[$i]['id'] = $modules[$i]->getVar('id');
$menuModule[$i]['type'] = "module";
$sublinks =& $modules[$i]->subLink();
if (count($sublinks) > 0) {
foreach($sublinks as $sublink){
$menuModule[$i]['sublinks'][] = array('title' => $sublink['name'], 'url' => XOOPS_URL.'/modules/'.$modules[$i]->getVar('dirname').'/'.$sublink['url']);
}
} else {
$menuModule[$i]['sublinks'] = array();
}
}
}

return $menuModule;
}

function getContentAsArray(){
global $xoopsDB, $xoopsModule, $xoopsUser;
$moduleHandler = xoops_getHandler('module');

//Content
$result = $xoopsDB->query("SELECT *, blockid AS priority, 'content' AS type FROM ".$xoopsDB->prefix('content')." WHERE visible = 1 ORDER BY blockid");
$contentItems = array();
$groupPermHandler = xoops_getHandler('groupperm');
$module = $moduleHandler->getByDirname('content');
($xoopsUser) ? $groups = $xoopsUser->getGroups() : $groups = XOOPS_GROUP_ANONYMOUS;
$allowedItems = $groupPermHandler->getItemIds("content_page_view", $groups, $module->getVar("mid"));
while($tcontent = $xoopsDB->fetchArray($result)) {
//echo "here".$tcontent["storyid"];
if (in_array($tcontent["storyid"], $allowedItems)) {
$contentItems[] = $tcontent;
}
}

return $contentItems;
}
