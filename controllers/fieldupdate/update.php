<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('wpjscc/popupform/fieldupdate') ?>?mode=<?= $mode ?>"><?= e(trans('wpjscc.popupform::lang.models.fieldconfig.label_plural')); ?></a></li>
        <li><?= e($this->pageTitle) ?></li>
    </ul>
<?php Block::endPut() ?>

<?php if (!$this->fatalError): ?>

    <?= Form::open(['class' => 'layout']) ?>

        <div class="layout-row">
            <?= $this->formRender() ?>
        </div>

        <div class="form-buttons">
            <div class="loading-indicator-container">
                <button
                    type="button"
                    data-request="onSave"
                    data-request-data="redirect:0,mode:'<?= $mode ?>'"
                    data-hotkey="ctrl+s, cmd+s"
                    data-load-indicator="<?= e(trans('backend::lang.form.saving_name', ['name' => trans('wpjscc.popupform::lang.models.fieldconfig.label')])); ?>"
                    class="btn btn-primary">
                    <?= e(trans('backend::lang.form.save')); ?>
                </button>
                <button
                    type="button"
                    data-request="onSave"
                    data-request-data="close:1,mode:'<?= $mode ?>'"
                    data-hotkey="ctrl+enter, cmd+enter"
                    data-load-indicator="<?= e(trans('backend::lang.form.saving_name', ['name' => trans('wpjscc.popupform::lang.models.fieldconfig.label')])); ?>"
                    class="btn btn-default">
                    <?= e(trans('backend::lang.form.save_and_close')); ?>
                </button>
                <button
                    type="button"
                    class="wn-icon-trash-o btn-icon danger pull-right"
                    data-request="onDelete"
                    data-request-data="mode:'<?= $mode ?>'"
                    data-load-indicator="<?= e(trans('backend::lang.form.deleting_name', ['name' => trans('wpjscc.popupform::lang.models.fieldconfig.label')])); ?>"
                    data-request-confirm="<?= e(trans('backend::lang.form.confirm_delete')); ?>">
                </button>
                <span class="btn-text">
                    or <a href="<?= Backend::url('wpjscc/popupform/fieldupdate') ?>?mode=<?= $mode ?>"><?= e(trans('backend::lang.form.cancel')); ?></a>
                </span>
            </div>
        </div>

    <?= Form::close() ?>

<?php else: ?>

    <p class="flash-message static error"><?= e($this->fatalError) ?></p>
    <p><a href="<?= Backend::url('wpjscc/popupform/fieldupdate') ?>?mode=<?= $mode ?>" class="btn btn-default"><?= e(trans('backend::lang.form.return_to_list')); ?></a></p>

<?php endif ?>
