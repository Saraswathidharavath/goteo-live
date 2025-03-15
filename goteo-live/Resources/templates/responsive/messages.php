<?php $this->layout('dashboard/layout') ?>

<?php $this->section('dashboard-content') ?>
<div class="dashboard-content">

  <div class="inner-container">
<?php if($this->messages): ?>
    <h2><?= $this->text('regular-messages') ?></h2>
    <?php
    foreach($this->messages as $message):
        $comments = $message->totalResponses($this->get_user(), true);
        $type = $message->getType();
        $icon = '';
        if($type == 'project-private') $icon = 'partners';
        if($type == 'project-support') $icon = 'supports';
        $project = $message->getProject() ?: null ;
        // Allow respond to all users if can edit the project
        $shared = $project ? $project->userIsOwner($this->get_user()) : false;
    ?>
    <div class="panel section-content">
      <div class="panel-body">
        <h4 class="data-support">
            <a href="/project/<?= $message->project ?>"><img class="img-circle" src="<?= $project ? $project->image->getLink(64,64,true) : null ?>" style="height: 32px"> <?= $message->getTitle() ?></a>
            <small><em><?= date_formater($message->date, true) ?></em></small>
            <?php if($shared): ?>
            <small><strong><?= count($message->getParticipants(false)).' ' . $this->text('dashboard-menu-projects-recipients') ?></strong></small>
            <?php endif ?>
            <small class="pull-right"><?= $this->text('message-'. $type ) ?></small>
        </h4>
          <p class="data-description"><?= $message->getHtml() ?></p>
          <p>
              <button class="btn btn-xs btn-<?= $comments ? 'info' : 'default' ?>" data-toggle="collapse"  data-target="#comments-<?= $message->id ?>"><i class="icon-1x icon icon-partners"></i> <?= $this->text('regular-num-comments', $comments) ?></button>

          </p>
          <div class="comments collapse" id="comments-<?= $message->id ?>">
            <?php if(!$comments): ?>
                <p><em><i class="fa fa-hand-o-right"></i> <?= $this->text('dashboard-project-support-no-responses') ?></em></p>
            <?php endif ?>

              <?= $this->insert('dashboard/project/partials/comments/full', [
                    'comments' => $message->getResponses($this->get_user(), true, 0 , 100),
                    'thread' => $message->id,
                    'private' => true,
                    'shared' => $shared,
                    'project' => $project->id,
                    'type' => $type
                    ]) ?>
          </div>
      </div>
    </div>
  <?php endforeach ?>
  <?= $this->insert('partials/utils/paginator', ['total' => $this->total, 'limit' => $this->limit ? $this->limit : 10]) ?>

<?php else: ?>
    <blockquote><?= $this->text('dashboard-message-no-message') ?></blockquote>
<?php endif ?>
  </div>
</div>

<?php $this->replace() ?>

<?php $this->section('footer') ?>

<script type="text/javascript">
// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt

$(function(){

    // Autoexpand comment-list if in hash
    var $thread = $(location.hash);
    if($thread.length) {
      // console.log('hash',location.hash);
      $thread.collapse('show');
      $thread.find('textarea').select();
    }
});

// @license-end
</script>

<?php $this->append() ?>
