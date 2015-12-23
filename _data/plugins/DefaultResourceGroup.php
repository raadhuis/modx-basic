id: 11
name: DefaultResourceGroup
description: 'Assigns new resources to one or more Resource Groups'
category: DefaultResourceGroup
properties: 'a:1:{s:10:"drg_groups";a:7:{s:4:"name";s:10:"drg_groups";s:4:"desc";s:173:"Resource Group name or comma-separated list of Resource Group names to assign new resources to. Set to `Parent` to have new resources inherit their parent''s resource groups.";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";N;s:4:"area";s:0:"";}}'
disabled: 1

-----

/**
 * DefaultResourceGroup
 * 
 * Copyright 2012-2014 Bob Ray
 *
 * @author Bob Ray
 * @created 1/20/12
 *
 * DefaultResourceGroup is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * DefaultResourceGroup is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * DefaultResourceGroup; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package defaultresourcegroup
 */
/**
 * MODx DefaultResourceGroup Snippet
 *
 * Description Adds resources to default resource group(s)
  *
 * @package defaultresourcegroup
 *
 * @param drg_groups string -- comma-separated list of resource groups
 */

  $drg_groups = null;

/** @var $modx modX */
/** @var $scriptProperties array */

function matchParent($resource) {

    /** @var $resource modResource */
    /** @var $grpObject modResourceGroupResource */

    $parentId = $resource->get('parent');

    $res = $parentId;
    /* top level documents are not regarded! */
    if ($parentId > 0) {
        $parentResource = $resource->getOne('Parent');
        $groups = $parentResource->getMany('ResourceGroupResources');
        foreach ($groups as $name => $grpObject) {
            $resGrpId = (int) $grpObject->get('document_group');
            @$resource->joinGroup($resGrpId);
        }
    }
}

/* only operate on new resources */
if ($mode != modSystemEvent::MODE_NEW) return;

$groupSetting = $modx->getOption('drg_groups', $scriptProperties, null);

if (!empty($groupSetting)) {
   if ($groupSetting == 'parent' || $groupSetting == 'Parent') {
       matchParent($resource);
   } else {
       $groups = explode(',', $groupSetting);

       foreach ($groups as $group) {
           $success = $resource->joinGroup(trim($group));
       }
   }
}

return '';