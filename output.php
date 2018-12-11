<div id="<?php echo $options['id'] ?>"<?php if ($options['cssClass']) echo ' class="'.$options['cssClass'].'"'?><?php if ($options['cssStyle']) echo ' style="'.$options['cssStyle'].'"'?>></div>

<script>

callback = function() 
{   
    options = <?php echo json_encode($options) ?>;
    wp3d = new WPGLTF('<?php echo $model?>', options);
    wp3d.render();
}

if(window.addEventListener){
  window.addEventListener('load',callback,false);
} else {
  window.attachEvent('onload',callback);
}

</script>