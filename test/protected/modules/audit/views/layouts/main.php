<?php
/**
 * @var $this AuditWebController
 * @var $content string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
$cs = Yii::app()->clientScript;
$cs->coreScriptPosition = CClientScript::POS_HEAD;
$cs->scriptMap = array();
$baseUrl = $this->module->assetsUrl;
$cs->registerCoreScript('jquery');
Yii::app()->yiistrap->register();
$cs->registerCssFile($baseUrl . '/font-awesome/css/font-awesome.min.css');
$cs->registerCssFile($baseUrl . '/css/main.css');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>

<?php
$items = array();
foreach (array_keys($this->module->controllerMap) as $controllerName) {
    $items[] = array(
        'label' => Yii::t('audit', ucfirst($controllerName)),
        'url' => Yii::app()->getUser()->getState('index.audit' . ucfirst($controllerName), array($controllerName . '/index')),
        'active' => $this->id == $controllerName,
    );
}
$this->widget('yiistrap.widgets.TbNavbar', array(
    'brandLabel' => $this->module->getName(),
    'brandUrl' => array('/' . $this->module->id),
    'fluid' => true,
    'items' => array(
        array(
            'class' => 'yiistrap.widgets.TbNav',
            'items' => $items,
        ),
        array(
            'class' => 'yiistrap.widgets.TbNav',
            'htmlOptions' => array('class' => 'pull-right'),
            'items' => array(array('label' => Yii::app()->name, 'url' => Yii::app()->homeUrl)),
        ),
    ),
));

echo CHtml::tag('div', array('class' => 'container-fluid'), $this->widget('yiistrap.widgets.TbBreadcrumb', array(
    'links' => array_merge($this->getBreadcrumbs(), array($this->pageTitle)),
), true));

echo $content;

?>

<div id="footer" class="container-fluid small text-center">
    <?php
    $this->renderPartial('audit.views.request.__footer');
    echo '<br/>';
    echo AuditModule::powered();
    echo '<br/>A product of <a href="http://mrphp.com.au">Mr PHP</a>.';
    ?>
</div>

</body>
</html>
