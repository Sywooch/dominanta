<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\Html;

function is_current_page($item)
{

}

?>

<?php

foreach ($items AS $item) {
    $second_level = $item->secondLevel;


?>

              <a href="<?= $item->currentLink ?>" <?= $second_level?'class="has_submenu"':'' ?> id="header_menu_<?= $item->id ?>" data-menu="<?= $item->id ?>">
                  <?= $item->item ?>
              </a>

<?php

    if ($second_level) {

?>

              <div class="header_submenu" data-menu="<?= $item->id ?>" id="header_submenu_<?= $item->id ?>">


<?php

        foreach ($second_level AS $subitem) {

?>

                <a href="<?= $subitem->currentLink ?>">
                    <?= $subitem->item ?>
                </a>


<?php

        }

?>

              </div>

<?php

    }
}

?>