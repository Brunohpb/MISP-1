    <tr data-row-id="<?php echo h($item['TagCollection']['id']); ?>">
        <td class="short"><?php echo h($item['TagCollection']['id']);?>&nbsp;</td>
        <td class="short"><?php echo h($item['TagCollection']['uuid']);?>&nbsp;</td>
        <td class="short"><?php echo h($item['TagCollection']['name']);?>&nbsp;</td>
        <td class="shortish">
          <div class="attributeTagContainer" id="#Tag_Collection_<?php echo h($item['TagCollection']['id']);?>_tr .attributeTagContainer">
            <?php
                echo $this->element(
                    'ajaxTagCollectionTags',
                    array(
                        'attributeId' => $item['TagCollection']['id'],
                        'attributeTags' => $item['TagCollectionTag'],
                        'tagAccess' => ($isSiteAdmin || $me['org_id'] == $item['TagCollection']['org_id']),
                        'context' => 'tagCollection',
                        'tagCollection' => $item
                    )
                );
                ?>
          </div>
        </td>
        <td class="shortish">
          <?php
            echo $this->element('galaxyQuickViewMini', array(
              'mayModify' => ($isSiteAdmin || $me['org_id'] == $item['TagCollection']['org_id']),
              'isAclTagger' => true,
              'data' => $item['Galaxy'],
              'target_id' => h($item['TagCollection']['id']),
              'target_type' => 'tag_collection'
            ));
          ?>
        </td>
        <td class="short"><span class="icon-<?php echo $item['TagCollection']['all_orgs'] ? 'ok' : 'remove'; ?>">&nbsp;</span></td>
        <td class="short" ondblclick="document.location.href ='<?php echo $baseurl . "/events/index/searchorg:" . $item['Organisation']['id'];?>'">
            <?php
                echo $this->OrgImg->getOrgImg(array('name' => $item['Organisation']['name'], 'id' => $item['Organisation']['id'], 'size' => 24));
            ?>
        </td>
        <td class="short"><?php echo empty($item['User']['email']) ? '&nbsp;' : h($item['User']['email']);?>&nbsp;</td>
        <td><?php echo h($item['TagCollection']['description']);?>&nbsp;</td>
        <td class="short action-links">
            <?php
                if ($isSiteAdmin || $me['org_id'] == $item['TagCollection']['org_id']) {
                    echo $this->Html->link('', array('action' => 'edit', $item['TagCollection']['id']), array('class' => 'icon-edit', 'title' => 'Edit'));
                    echo $this->Form->postLink('', array('action' => 'delete', $item['TagCollection']['id']), array('class' => 'icon-trash', 'title' => 'Delete'), __('Are you sure you want to delete "%s"?', $item['TagCollection']['name']));
                }
            ?>
        </td>
    </tr>
