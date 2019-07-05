<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use rmrevin\yii\fontawesome\component\Icon;
use dosamigos\fileupload\FileUploadUI;

$this->title = Yii::t('app', Yii::t('app', 'Import'));

$this->params['top_panel'] = '';
$this->params['select_menu'] = Url::to(['/manage/market/stock-import']);

$form = ActiveForm::begin($form_config);

?>
<script>
    function getProgress(filename, page, context) {
        var table_row   = $(context[0]);
        var progress    = table_row.find('div.progress');
        var progressbar = progress.find('div.progress-bar');

        if (page == 1) {
            table_row.find('p.size').html('Обработка 0%');
            table_row.find('button').hide();
            progressbar.width(1);
            progress.attr('aria-valuenow', 0);
            progressbar.removeClass('progress-bar-success');
            progressbar.addClass('progress-bar-primary');
        }

        $.ajax({
            url: '/manage/market/stock-import/process',
            data: {filename: filename, page: page},
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.next_page) {
                    progressbar.css('width', data.progress + '%');
                    progress.attr('aria-valuenow', data.progress);
                    table_row.find('p.size').html('Обработка ' + data.progress + '%');
                    getProgress(filename, data.next_page, context)
                } else {
                    table_row.find('p.size').html('Обработано');
                    progress.hide();
                }

            }
        })

        console.log(filename);
        console.log(page);
        console.log(context);
        console.log(context[0]);
        //$(context[0]).find('p.size').html('sdfsdfsdfsf')
    }
</script>

<div class="text-center">

<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'upload',
    'url' => ['/manage/market/stock-import/upload'],
    'gallery' => false,
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                if (data.result.status == "ok") {



                                    getProgress(data.result.message, 1, data.context)
                                }


                                //productPhoto.uploadedPhoto(data.result);
                                console.log(e);
                                console.log(data);

                                return false;
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

</div>

<?php

ActiveForm::end();

?>