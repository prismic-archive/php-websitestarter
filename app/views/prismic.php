<!-- Prismic toolbar -->
<script type="application/javascript">
  window.prismic = {
      endpoint: '<?= prismic_endpoint() ?>'
  };
</script>
<script src="//www.google-analytics.com/cx/api.js?experiment=<?=current_experiment_id()?>"></script>
<script src="//prismic.io/snapshot/prismic.min.js"></script>
<?php if(current_experiment_id()) { ?>
  <script>prismic.startExperiment("<?=current_experiment_id()?>", cxApi);</script>
<?php }?>
