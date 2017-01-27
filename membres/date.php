<a>Changer</a> 
<div id="item" class="collapse">
  Contenu
</div>
<script src="<?php echo ROOTPATH; ?>/assets/js/jquery.js"></script>
<script src="<?php echo ROOTPATH; ?>/assets/js/bootstrap.min.js"></script>
<script>
  $(function() {
    $('a').click(function() {
      $('#item').collapse('toggle');
    });
    $('#item').on('shown.bs.collapse', function () {
      alert('On me voit !');
    })
  });
</script>
