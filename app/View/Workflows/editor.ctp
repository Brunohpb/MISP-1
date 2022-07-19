<?php
$usableModules = [
    'blocks_action' => $modules['blocks_action'],
    'blocks_logic' => $modules['blocks_logic'],
];
$allModules = array_merge($usableModules['blocks_action'], $usableModules['blocks_logic']);
$triggerModules = $modules['blocks_trigger'];
$selectedTrigger = Hash::get($selectedWorkflow, 'Workflow.listening_triggers.0', []);
$isBlockingTrigger = $selectedTrigger['blocking'] ?? false;
?>
<div class="root-container">
    <div class="topbar">
        <a href="<?= $baseurl . '/workflows/triggers' ?>">
            <i class="fa-fw <?= $this->FontAwesome->getClass('caret-left') ?>"></i><?= __('Trigger index') ?>
        </a>
        <span style="display: flex; align-items: center; min-width: 220px; gap: 5px;">
            <h3 style="display: inline-block;">
                <span style="font-weight:normal;"><?= __('Workflow:') ?></span>
                <strong><?= h($selectedWorkflow['Workflow']['trigger_id']) ?></strong>
            </h3>
            <?php if (!empty($isBlockingTrigger)) : ?>
                <span class="label label-important" style="line-height: 20px;" title="<?= __('This workflow is a blocking worklow and can prevent the default MISP behavior to execute') ?>">
                    <i class="fa-lg fa-fw <?= $this->FontAwesome->getClass('stop-circle') ?>"></i>
                    <?= __('Blocking') ?>
                </span>
            <?php else : ?>
                <span class="label label-success" style="line-height: 20px;" title="<?= __('This workflow is a not blocking worklow. The default MISP behavior will or has already happened') ?>">
                    <i class="fa-lg fa-fw <?= $this->FontAwesome->getClass('check-circle') ?>"></i>
                    <?= __('Not blocking') ?>
                </span>
            <?php endif; ?>
        </span>
        <span style="display: flex; align-items: center;">
            <button id="saveWorkflow" class="btn btn-primary" href="#">
                <i class="fa-fw <?= $this->FontAwesome->getClass('save') ?>"></i> <?= __('Save') ?>
                <span class="fa fa-spin fa-spinner loading-span hidden"></span>
            </button>
            <span id="workflow-saved-container" class="fa-stack small" style="margin-left: 0.75em;">
                <i class=" fas fa-cloud fa-stack-2x"></i>
                <i class="fas fa-save fa-stack-1x fa-inverse" style="top: 0.15em;"></i>
            </span>
            <span id="workflow-saved-text" style="margin-left: 5px;"></span>
            <span id="workflow-saved-text-details" style="margin-left: 5px; font-size: 0.75em"></span>
        </span>
        <span style="display: flex; align-items: center; margin-left: auto; margin-right: 1em;">
            <button class="btn btn-info btn-mini" href="#workflow-info-modal" data-toggle="modal">
                <i class="<?= $this->FontAwesome->getClass('info') ?>"></i>
            </button>
        </span>
    </div>

    <div class="main-container">
        <div class="sidebar">
            <div class="side-panel">
                <ul class="nav nav-tabs" id="block-tabs">
                    <li class="active">
                        <a href="#container-actions">
                            <i class="<?= $this->FontAwesome->getClass('play') ?>"></i>
                            <?= __('Actions') ?>
                        </a>
                    </li>
                    <li>
                        <a href="#container-logic">
                            <i class="<?= $this->FontAwesome->getClass('code-branch') ?>"></i>
                            <?= __('Logic') ?>
                        </a>
                    </li>
                    <li>
                        <a href="#container-blueprints">
                            <i class="<?= $this->FontAwesome->getClass('shapes') ?>"></i>
                            <?= __('Blueprints') ?>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="container-actions">
                        <div id="block-filter-group" class="btn-group" data-toggle="buttons-radio">
                            <button type="button" class="btn btn-primary active" data-type="enabled" onclick="filterBlocks(this)"><?= __('Enabled') ?></button>
                            <button type="button" class="btn btn-primary" data-type="misp-module" onclick="filterBlocks(this)">
                                misp-module<span class="is-misp-module"></span>
                            </button>
                            <button type="button" class="btn btn-primary" data-type="is-blocking" onclick="filterBlocks(this)">
                                <?= __('Blocking') ?>
                            </button>
                            <button type="button" class="btn btn-primary" data-type="all" onclick="filterBlocks(this)"><?= __('All') ?></button>
                        </div>
                        <select type="text" placeholder="Search for a block" class="chosen-container blocks" autocomplete="off">
                            <?php foreach ($modules['blocks_action'] as $block) : ?>
                                <?php if (empty($block['disabled'])) : ?>
                                    <option value="<?= h($block['id']) ?>"><?= h($block['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="block-container">
                            <?php foreach ($modules['blocks_action'] as $block) : ?>
                                <?= $this->element('Workflows/sidebar-block', ['block' => $block]) ?>
                            <?php endforeach; ?>
                            <?php if (empty($modules['blocks_action'])) : ?>
                                <div class="alert alert-danger" style="margin: 10px 5px;">
                                    <?= __('There are no modules available. They can be enabled %s.', sprintf('<a href="%s">%s</a>', $baseurl . '/workflows/moduleIndex', __('here'))) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="container-logic">
                        <select type="text" placeholder="Search for a block" class="chosen-container blocks" autocomplete="off" style="width: 305px; margin: 0 0.5em;">
                            <?php foreach ($modules['blocks_logic'] as $block) : ?>
                                <?php if (empty($block['disabled'])) : ?>
                                    <option value="<?= h($block['id']) ?>"><?= h($block['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="block-container">
                            <?php foreach ($modules['blocks_logic'] as $block) : ?>
                                <?= $this->element('Workflows/sidebar-block', ['block' => $block]) ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (empty($modules['blocks_logic'])) : ?>
                            <div class="alert alert-danger" style="margin-top: 10px;">
                                <?= __('There are no modules available. They can be enabled %s.', sprintf('<a href="%s">%s</a>', $baseurl . '/workflows/moduleIndex/type:logic', __('here'))) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="tab-pane" id="container-blueprints">
                        <div style="margin-left: 0.75em; margin-bottom: 0.5em;">
                            <a id="saveBlueprint" class="btn btn-primary" href="<?= $baseurl . '/workflowBlueprints/add/1' ?>">
                                <i class="<?= $this->FontAwesome->getClass('save') ?>"></i> <?= __('Save blueprint') ?>
                            </a>
                        </div>
                        <select type="text" placeholder="Search for a block" class="chosen-container blocks blueprint-select" autocomplete="off" style="width: 305px; margin: 0 0.5em;">
                            <?php foreach ($workflowBlueprints as $workflowBlueprint) : ?>
                                <option value="<?= h($workflowBlueprint['WorkflowBlueprint']['id']) ?>"><?= h($workflowBlueprint['WorkflowBlueprint']['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="block-container">
                            <?php foreach ($workflowBlueprints as $workflowBlueprint) : ?>
                                <?= $this->element('Workflows/sidebar-block-workflow-blueprint', ['workflowBlueprint' => $workflowBlueprint['WorkflowBlueprint']]) ?>
                            <?php endforeach; ?>
                            <?php if (empty($workflowBlueprints)) : ?>
                                <div class="alert alert-info" style="margin-top: 10px;">
                                    <?= __('There are no blueprint available. You can create some by multi-selecting nodes and then saving the blueprint.') ?>
                                    <?= __('Alternatively, Blueprints can be imported on the %s', sprintf('<a href="%s">%s</a>', $baseurl . '/workflowBlueprints/index', __('blueprint index'))) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rightbar">
            <div class="right-panel">
                <div class="btn-group control-buttons">
                    <button id="control-duplicate" class="btn btn-small btn-primary disabled" type="button" title="<?= __('Duplicate') ?>">
                        <i class="fa-fw <?= $this->FontAwesome->getClass('clone') ?>"></i> <?= __('Duplicate') ?>
                    </button>
                    <button id="control-delete" class="btn btn-small btn-danger disabled" type="button" title="<?= __('Delete') ?>">
                        <i class="fa-fw <?= $this->FontAwesome->getClass('trash') ?>"></i> <?= __('Delete') ?>
                    </button>
                    <a class="btn btn-primary btn-small dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa-fw <?= $this->FontAwesome->getClass('shapes') ?>"></i> <?= __('Blueprints') ?> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li id="control-import-blocks" class="dropdown-submenu submenu-right">
                            <a href="#"><i class="fa-fw <?= $this->FontAwesome->getClass('file-import') ?>"></i> <?= __('Import blueprint') ?></a>
                            <ul class="dropdown-menu pull-right">
                                <?php if (empty($workflowBlueprints)) : ?>
                                    <li><a href="#"><?= _('No workflow blueprints saved') ?></a></li>
                                <?php endif; ?>
                                <?php foreach ($workflowBlueprints as $workflowBlueprint) : ?>
                                    <li><a href="#" title="<?= h($workflowBlueprint['WorkflowBlueprint']['description']) ?>" onclick="addWorkflowBlueprint(<?= h($workflowBlueprint['WorkflowBlueprint']['id']) ?>)">
                                            <?= h($workflowBlueprint['WorkflowBlueprint']['name']) ?>
                                            <small class="text-muted">[<?= h(substr($workflowBlueprint['WorkflowBlueprint']['uuid'], 0, 4)) ?>...]</small>
                                        </a></li>
                                <?php endforeach; ?>
                            </ul>
                        <li id="control-save-blocks" class="disabled"><a href="<?= $baseurl . '/workflowBlueprints/add/1' ?>"><i class=" fa-fw <?= $this->FontAwesome->getClass('save') ?>"></i> <?= __('Save blueprint') ?></a></li>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="canvas">
            <div id="drawflow" data-workflowid="<?= h($selectedWorkflow['Workflow']['id']) ?>"></div>
            <div id="loadingBackdrop" class="modal-backdrop" style="display: none;"></div>
        </div>
    </div>
</div>

<div id="block-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Module block modal" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Block options</h3>
    </div>
    <div class="modal-body">
        <p>Block options</p>
    </div>
    <div class="modal-footer">
        <button id="delete-selected-node" class="btn btn-danger">Delete</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>

<div id="block-notifications-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Module notification modal" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?= __('Module Notifications') ?></h3>
    </div>
    <div class="modal-body">
        <p>Block notifications</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>

<div id="block-filtering-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Module filtering modal" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3><?= __('Module Filtering') ?></h3>
    </div>
    <div class="modal-body">
        <p>Block filtering</p>
    </div>
    <div class="modal-footer">
        <button class="btn btn-success" onclick="saveFilteringForModule(this)" aria-hidden="true">Save</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>

<div id="workflow-info-modal" class="modal modal-lg hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?= __('Workflow information') ?></h3>
    </div>
    <div class="modal-body modal-body-xl">
        <ul class="nav nav-tabs">
            <li class="active"><a href=" #modal-info-concept" data-toggle="tab">Concept</a></li>
            <li><a href="#modal-info-usage" data-toggle="tab">Usage</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="modal-info-concept">
                <h4><?= __('Hash path') ?></h4>
                <p><?= __('Some modules have the possibility to filter or check conditions using %s', sprintf('<a href="%s">%s</a>', 'https://book.cakephp.org/2/en/core-utility-libraries/hash.html', __('CakePHP\'s path expression.'))) ?></p>
                <p><strong><?= __('Example:') ?></strong></p>
                <pre>
$users = [
     ['id' => 123, 'name'=> 'fred', 'surname' => 'bloggs'],
     ['id' => 245, 'name' => 'fred', 'surname' => 'smith'],
     ['id' => 356, 'name' => 'joe', 'surname' => 'smith'],
];
$path_expression = '{n}[name=fred].id'
$ids = Hash::extract($users, $$path_expression);
// $ids will be [123, 245]</pre>
                <h3><?= __('Module filtering with hash path') ?></h3>
                <p><?= __('Some modules can further filter incoming data based on the provided Hash path expression and associated condition.') ?></p>
                <p><i class="fa-fw <?= $this->FontAwesome->getClass('exclamation-triangle') ?>"></i> <?= __('Using filters will not modify the data being passed on from module to module.') ?></p>
                <h3><?= __('Conditional Switch') ?></h3>
                <p><?= __('The conditional switch allow to direct the execution path based on the provided condition. If the encoded condition is satisfied, the execution path will take the `then` path. Otherwise, the `else` path will be used. Note that the condition is only evaluated once.') ?></p>
                <p><strong><?= __('Example:') ?></strong></p>
                <pre>
$value_passed_to_switch = 'fred'
$operator_passed_to_switch = In'
$path_expression_passed_to_switch = '{n}.name'
$data_passed_to_switch = [
     ['id' => 123, 'name'=> 'fred', 'surname' => 'bloggs'],
     ['id' => 245, 'name' => 'fred', 'surname' => 'smith'],
     ['id' => 356, 'name' => 'joe', 'surname' => 'smith'],
];
// The condition is satisfied as `fred` is contained in the extracted data.
// Then `then` branch will be used by the execution path</pre>
                <h3><?= __('Blocking module') ?></h3>
                <p><?= __('A blocking module can block the execution of a workflow, potentially blocking the operation that triggered the worflow at the first place.') ?></p>
                <p><strong><?= __('Example:') ?></strong></p>
                <ol>
                    <li><?= __('An Event gets published') ?></li>
                    <li><?= __('The `publish` workflow is called (this workflow is a `blocking` workflow)') ?></li>
                    <li><?= __('If a blocking module cancels the execution, the event will not be published') ?></li>
                </ol>
                <h3><?= __('Parallel Task') ?></h3>
                <p><?= __('Allowing breaking the execution flow into a parallel tasks to be executed later on by a background worker, thus preventing blocking module to cancel the ongoing operation.') ?></p>
            </div>
            <div class="tab-pane" id="modal-info-usage">
                <h3><?= __('Shortcuts') ?></h3>
                <ul>
                    <li><code>Ctrl + Mouse_wheel</code>: <?= __('Zoom in / out') ?></li>
                    <li><code>Shift + Click</code>: <?= __('Multi-select tool') ?></li>
                    <li><code>c</code>: <?= __('Center canvas in viewport') ?></li>
                </ul>
                <h3><?= __('Blueprints') ?></h3>
                <p><?= __('To save a blueprint, select the nodes to be saved using the multi-select tool, then click the `Save blueprint` button') ?></p>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
    </div>
</div>

<?php
echo $this->element('genericElements/assetLoader', [
    'css' => ['drawflow.min', 'drawflow-default'],
    'js' => ['jquery-ui', 'drawflow.min', 'doT', 'moment.min', 'viselect.cjs'],
]);
echo $this->element('genericElements/assetLoader', [
    'css' => ['workflows-editor'],
    'js' => ['workflows-editor/workflows-editor', 'taskScheduler'],
]);
?>

<script>
    var $root_container = $('.root-container')
    var $side_panel = $('.root-container .side-panel')
    var $canvas = $('.root-container .canvas')
    var $loadingBackdrop = $('.root-container .canvas #loadingBackdrop')
    var $chosenBlocks = $('.root-container .side-panel .chosen-container.blocks')
    var $blockFilterGroup = $('.root-container .side-panel #block-filter-group')
    var $drawflow = $('#drawflow')
    var $blockModal = $('#block-modal')
    var $blockModalDeleteButton = $blockModal.find('#delete-selected-node')
    var $blockNotificationModal = $('#block-notifications-modal')
    var $blockFilteringModal = $('#block-filtering-modal')
    var $controlDuplicateButton = $('.control-buttons #control-duplicate')
    var $controlDeleteButton = $('.control-buttons #control-delete')
    var $controlExportBlocksLi = $('.control-buttons #control-export-blocks')
    var $controlSaveBlocksLi = $('.control-buttons #control-save-blocks')
    var $importWorkflowButton = $('#importWorkflow')
    var $exportWorkflowButton = $('#exportWorkflow')
    var $saveWorkflowButton = $('#saveWorkflow')
    var $saveBlueprintButton = $('#saveBlueprint')
    var $lastModifiedField = $('#lastModifiedField')
    var $workflowSavedIconContainer = $('#workflow-saved-container')
    var $workflowSavedIconText = $('#workflow-saved-text')
    var $workflowSavedIconTextDetails = $('#workflow-saved-text-details')
    var $blockContainerLogic = $('#container-logic')
    var $blockContainerAction = $('#container-actions')
    var editor = false
    var selection = false
    var all_blocks = <?= json_encode($allModules) ?>;
    var all_blocks_by_id = <?= json_encode(Hash::combine($allModules, '{n}.id', '{n}')) ?>;
    var all_triggers_by_id = <?= json_encode(Hash::combine($triggerModules, '{n}.id', '{n}')) ?>;
    var all_workflow_blueprints_by_id = <?= json_encode(Hash::combine($workflowBlueprints, '{n}.WorkflowBlueprint.id', '{n}')) ?>;
    var workflow = false
    <?php if (!empty($selectedWorkflow)) : ?>
        var workflow = <?= json_encode($selectedWorkflow) ?>;
    <?php endif; ?>

    $(document).ready(function() {
        initDrawflow()
    })
</script>

<style>
    .dropdown-menu li.disabled a {
        pointer-events: none;
    }
</style>