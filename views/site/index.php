<?php

/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'ОБК - качественный бетон, раствор и ЖБИ изделия по доступным ценам';
/*
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => 'свадебный салон, казань, фата, салимжанова, свадебное платье, свадебные аксессуары, купить',
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Свадебный салон Фата - купить свадебное платье и свадебные аксессуары в Казани',
]);
*/

$depends_option = ['depends' => 'app\assets\SiteAsset'];

$this->registerCssFile('/css/multiscroll/jquery.multiscroll.css', $depends_option);
$this->registerCssFile('/css/multiscroll/demo.css', $depends_option);
$this->registerCssFile('/css/main.css', $depends_option);


$this->registerJsFile('/js/multiscroll/jquery.multiscroll.extensions.min.js', $depends_option);
$this->registerJsFile('/js/main.js', $depends_option);

?>

<!-- Begin footer -->
<ul class="home-links no-d">
    <li>
        Copyright © 2018 ОБК
    </li>
    <li>
        Создание и продвижение сайтов <a style="color:#333" href="#">Reliz</a>
    </li>
</ul>
<!-- End footer -->

<!-- Begin container -->
<div id="myContainer">

    <!-- Begin left side -->
    <div id="left-side" class="yes-d ms-left">
        <!-- Begin section 1 -->
        <div class="ms-section section1" id="section1-left">
            <div class="intro">
                <img class="logo" src="/images/logo.png">
                <h2>ОБК</h2>
                <p>Только качественный бетон, раствор и <br>ЖБИ изделия по доступным ценам</p>
                <div class="button" id="download">
                    <a href="/uploads/price.pdf" target="_blank" class="button-purchase">Посмотреть прайс-лист</a>
                </div>
                <div class="button" id="calcul">
                    <a href="/calculator" class="button-purchase">Калькулятор стоимости</a>
                </div>
            </div>
        </div>
        <!-- End section 1 -->

        <!-- Begin section 2 -->
        <div class="ms-section section2" id="section2-left">
            <div class="intro text">
                <h2>О компании</h2>
                <p>Наша компания существует на рынке с 1999 года, под названием «Сфера трейдинг». С 2010 года, под названием «Объединенная Бетонная Компания».</p>
                <p>Одним из основных направлений деятельности ООО «ОБК» является производство бетона и раствора любых марок, бордюры дорожные и тротуарные, вибропрессованная брусчатка, сваи, блоки фундаментные.. Производимый суточный объем бетона — 1500 куб. м. ; раствора — 200 куб. м.</p>
                <p>Вся выпускаемая продукция проходит через тщательный контроль служб ОТК и сертифицированной лаборатории.</p>
                <p>Услуги автобетононасосов любой длины ; АБС - 7 куб. м., 9 куб. м. ; Самосвалы 13т, 15т, 20т и т. д. Все цены в прайс листе указаны c НДС 18%.</p>
            </div>
        </div>
        <!-- End section 2 -->

        <!-- Begin section 3 -->
        <div class="ms-section section3 ms-tableCell" id="section3-left">
            <div class="intro text">
                <h2>Бетон</h2>
                <p>Для изготовления товарного бетона используют четыре основных «ингредиента» - цемент, песок, щебень, воду <a href="<?= Url::to(['/concrete']) ?>">подробнее...</a></p>
                <h2>ЖБИ</h2>
                <p>Аббревиатура ЖБИ объединяет широкую группу стройматериалов, произведенных из железобетона <a href="<?= Url::to(['/ferroconcrete']) ?>">подробнее...</a></p>
                <h2>Цемент</h2>
                <p>Сложно представить себе успешное строительство какого-либо здания или сооружения без использования цемента <a href="<?= Url::to(['/cement']) ?>">подробнее...</a></p>
                <h2>Услуги автотранспорта</h2>
                <p>Услуга предоставления автотранспорта предполагает собой подачу автомашины выбранного типа и модификации.</p>
            </div>
        </div>
        <!-- End section 3 -->

        <!-- Begin section 4 -->
        <div class="ms-section section4" id="section4-left">
            <div class="intro text">
                <h2>Как с нами связаться</h2>
                <h3 style=" margin-top:10px; font-size: 1.5em;">Адрес</h3>
                <p>420030, Респ. Татарстан, Казань, ул.Набережная, 31А </p>
                <h3 style=" margin-top:10px; font-size: 1.5em;">Коммерческий отдел</h3>
                <p>8(843) 297-15-19; 8-9377-705-707</p>
                <h3 style=" margin-top:10px; font-size: 1.5em;">Приемная / факс</h3>
                <p>8(843) 590-51-52; 590-51-58</p>
                <h3 style=" margin-top:10px; font-size: 1.5em;">E-mail</h3>
                <p><a href="#">obkk16@mail.ru</a></p>
                <iframe class="hidden visible-xs" src="https://yandex.ru/map-widget/v1/?um=constructor%3A7690586513d57144bd1eb492e718361fd3ce146a95a7911d5e6d2dc2bf379d98&amp;source=constructor" width="100%" height="300px" frameborder="0"></iframe>
            </div>
        </div>
        <!-- End section 4 -->

    </div>
    <!-- End left side -->

    <!-- Begin right side -->
    <div id="right-side" class="ms-right no-d">
        <!-- Begin section 1 -->
        <div class="ms-section section1 no-d" id="section1-right">
            <div class="intro"></div>
        </div>
        <!-- End section 1 -->

        <!-- Begin section 2 -->
        <div class="ms-section section2" id="section2-right">
        </div>
        <!-- End section 2 -->

        <!-- Begin section 3 -->
        <div class="ms-section section3" id="section3-right">
            <div class="intro text">
            <h2>Наши преимущества</h2>
                <h3>Индивидуальный подход <img style="width:11%; opacity:0.3; margin-right:5px" src="/images/546756765.png"></h3>
                <h3>Собственный автопарк <img style="width:14%; opacity:0.5; margin-right:5px" src="/images/546756765.png"></h3>
                <h3>Лабораторный контроль <img style="width:17%; opacity:0.8; margin-right:5px" src="/images/546756765.png"></h3>
                <h3>Собственное производство <img style="width:20%; opacity:1; margin-right:5px" src="/images/546756765.png"></h3>
            </div>
        </div>
        <!-- End section 3 -->

        <!-- Begin section 4 -->
        <div class="ms-section section4" id="section4-right">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A7690586513d57144bd1eb492e718361fd3ce146a95a7911d5e6d2dc2bf379d98&amp;source=constructor" width="100%" height="100%" frameborder="0"></iframe>
        </div>
        <!-- End section 4 -->
    </div>
    <!-- End right side -->

</div>
<!-- End container -->

