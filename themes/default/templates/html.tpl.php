<!DOCTYPE HTML>
<html>
  <head>
    <title><?php echo $title ?></title>
    <?php foreach($css_file_paths as $css_file_path): ?>
      <link rel="stylesheet" href="
      <?php 
        if (CLEAN_URLS)
        {
          echo $css_file_path;
        }
        else
        {
          echo 'http://127.0.0.1/5eTemplates' . $css_file_path;
        }
      ?>">
    <?php endforeach;?>
    <?php foreach($js_file_paths as $js_file_path): ?>
      <script src="
        <?php
          if (CLEAN_URLS)
          {
            echo $js_file_path;
          }
          else
          {
            echo 'http://127.0.0.1/5eTemplates' . $js_file_path;
          }

        ?>">
      </script>
    <?php endforeach;?>
  </head>
  <body<?php echo buildAttr($body_attr)?>>
    <?php echo $body?>
  </body>
</html>