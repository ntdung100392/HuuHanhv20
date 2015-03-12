<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?><?php echo $column_right; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <h1 class="heading-title"><?php echo $heading_title; ?></h1>
      <?php echo $content_top; ?>
      <div class="content">
      <?php if ($labpreorders) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-right"><?php echo $column_labpreorder_id; ?></td>
            <td class="text-left"><?php echo $column_status; ?></td>
            <td class="text-left"><?php echo $column_date_added; ?></td>
            <td class="text-right"><?php echo $column_order_id; ?></td>
            <td class="text-left"><?php echo $column_customer; ?></td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($labpreorders as $labpreorder) { ?>
          <tr>
            <td class="text-right">#<?php echo $labpreorder['labpreorder_id']; ?></td>
            <td class="text-left"><?php echo $labpreorder['status']; ?></td>
            <td class="text-left"><?php echo $labpreorder['date_added']; ?></td>
            <td class="text-right"><?php echo $labpreorder['order_id']; ?></td>
            <td class="text-left"><?php echo $labpreorder['name']; ?></td>
            <td class="text-right"><a href="<?php echo $labpreorder['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info btn-primary"><i class="fa fa-eye"></i></a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
        </div>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary button"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<?php echo $footer; ?>