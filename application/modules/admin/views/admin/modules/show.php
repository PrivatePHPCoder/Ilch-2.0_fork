<?php
$modulesList = url_get_contents($this->get('updateserver') . 'modules.json');
$modules = json_decode($modulesList);
$versionsOfModules = $this->get('versionsOfModules');
$coreVersion = $this->get('coreVersion');
?>

<link href="<?=$this->getModuleUrl('static/css/extsearch.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/css/star-rating.min.css') ?>" rel="stylesheet">
<link href="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/themes/krajee-fas/theme.min.css') ?>" rel="stylesheet">

<?php
if (empty($modules)) {
    echo $this->getTrans('noModulesAvailable');
    return;
}

foreach ($modules as $module): ?>
    <?php if ($module->id == $this->getRequest()->getParam('id')): ?>
        <?php
        $phpExtension = [];
        if (!empty($module->phpExtensions)) {
            $extensionCheck = [];
            foreach ($module->phpExtensions as $extension) {
                $extensionCheck[] = extension_loaded($extension);
            }

            $phpExtensions = array_combine($module->phpExtensions, $extensionCheck);
            foreach ($phpExtensions as $key => $value) {
                if ($value == true) {
                    $phpExtension[] = '<span class="text-success">' . $key . '</span>';
                } else {
                    $phpExtension[] = '<span class="text-danger">' . $key . '</span>';
                }
            }

            $phpExtension = implode(', ', $phpExtension);
        }

        $dependency = [];
        if (!empty($module->depends)) {
            $dependencyCheck = [];
            foreach ($module->depends as $key => $value) {
                $parsed = explode(',', $value);
                $dependencyCheck[$key] = ['condition' => str_replace(',','', $value), 'result' => version_compare($versionsOfModules[$key]['version'], $parsed[1], $parsed[0])];
            }

            foreach ($dependencyCheck as $key => $value) {
                if ($value['result'] == true) {
                    $dependency[] = '<span class="text-success">' . $key . ' ' . $value['condition'] . '</span>';
                } else {
                    $dependency[] = '<span class="text-danger">' . $key . ' ' . $value['condition'] . '</span>';
                }
            }

            $dependency = implode(', ', $dependency);
        }

        if (version_compare(PHP_VERSION, $module->phpVersion, '>=')) {
            $phpVersion = '<span class="text-success">' . $module->phpVersion . '</span>';
        } else {
            $phpVersion = '<span class="text-danger">' . $module->phpVersion . '</span>';
        }

        if (version_compare($coreVersion, $module->ilchCore, '>=')) {
            $ilchCore = '<span class="text-success">' . $module->ilchCore . '</span>';
        } else {
            $ilchCore = '<span class="text-danger">' . $module->ilchCore . '</span>';
        }
        ?>
        <div id="module" class="tab-content">
            <?php if (!empty($module->thumbs)): ?>
                <div id="module-search-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php $itemI = 0; ?>
                        <?php foreach ($module->thumbs as $thumb): ?>
                            <div class="carousel-item <?=$itemI == 0 ? 'active' : '' ?>">
                                <img src="<?=$this->get('updateserver') . 'modules/images/' . $module->id . '/' . $thumb->img ?>" alt="<?=$this->escape($module->name) ?>">
                                <div class="carousel-caption">
                                    <?php if ($thumb->desc != ''): ?>
                                        <?=$this->escape($thumb->desc) ?>
                                    <?php else: ?>
                                        <?=$this->escape($module->name) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php $itemI++; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if(count($module->thumbs) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#module-search-carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#module-search-carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <ul id="tabs" class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true"><?=$this->getTrans('info') ?></a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" id="changelog-tab" data-bs-toggle="tab" data-bs-target="#changelog" type="button" role="tab" aria-controls="changelog" aria-selected="false"><?=$this->getTrans('changelog') ?></a></li>
            </ul>
            <br />

            <div class="tab-pane active" id="info">
                <div class="col-sm-12 col-xl-6">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('name') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$this->escape($module->name) ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('version') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$module->version ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('author') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?php if ($module->link != ''): ?>
                                <a href="<?=$module->link ?>" title="<?=$this->escape($module->author) ?>" target="_blank" rel="noopener">
                                    <i><?=$this->escape($module->author) ?></i>
                                </a>
                            <?php else: ?>
                                <i><?=$this->escape($module->author) ?></i>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('hits') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$module->hits ?? '' ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('downloads') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$module->downs ?? '' ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('rating') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <span title="<?=$module->rating ?? 0 ?> <?=(($module->rating ?? 0) == 1) ? $this->getTrans('star') : $this->getTrans('stars') ?>">
                                <input id="rating" name="rating" type="number" class="rating" value="<?=$module->rating ?? 0 ?>">
                            </span>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-sm-12">
                            <b><?=$this->getTrans('requirements') ?>:</b>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('ilchCoreVersion') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$ilchCore ?>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <b><?=$this->getTrans('phpVersion') ?>:</b>
                        </div>
                        <div class="col-md-9 col-sm-6">
                            <?=$phpVersion ?>
                        </div>
                        <?php if (!empty($module->phpExtensions)): ?>
                            <div class="col-md-3 col-sm-6">
                                <b><?=$this->getTrans('phpExtensions') ?>:</b>
                            </div>
                            <div class="col-md-9 col-sm-6">
                                <?=$phpExtension ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($module->depends)): ?>
                            <div class="col-md-3 col-sm-6">
                                <b><?=$this->getTrans('dependencies') ?>:</b>
                            </div>
                            <div class="col-md-9 col-sm-6">
                                <?=$dependency ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <br />
                    <div class="col-sm-12">
                        <b><?=$this->getTrans('desc') ?>:</b>
                    </div>
                    <div class="col-sm-12">
                        <?=$this->escape($module->desc) ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="changelog">
                <div class="col-sm-12">
                    <?php if (!empty($module->changelog)) {
                        echo $module->changelog;
                    } else {
                        echo $this->getTrans('noChangelog');
                    } ?>
                </div>
            </div>
        </div>

        <div class="content_savebox">
            <?php if (!empty($module->phpextensions) && in_array(false, $extensionCheck)): ?>
                <button class="btn btn-outline-secondary disabled" title="<?=$this->getTrans('phpExtensionError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!version_compare(PHP_VERSION, $module->phpVersion, '>=')): ?>
                <button class="btn btn-outline-secondary disabled" title="<?=$this->getTrans('phpVersionError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!version_compare($coreVersion, $module->ilchCore, '>=')): ?>
                <button class="btn btn-outline-secondary disabled" title="<?=$this->getTrans('ilchCoreError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (!empty($dependencyCheck)): ?>
                <button class="btn btn-outline-secondary disabled" title="<?=$this->getTrans('dependencyError') ?>">
                    <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                </button>
            <?php elseif (in_array($module->key, $this->get('modules'))): ?>
                <button class="btn btn-outline-secondary disabled" title="<?=$this->getTrans('alreadyExists') ?>">
                    <i class="fa-solid fa-check text-success"></i> <?=$this->getTrans('alreadyExists') ?>
                </button>
            <?php else: ?>
                <form method="POST" action="<?=$this->getUrl(['module' => 'admin', 'controller' => 'modules', 'action' => 'search', 'key' => $module->key]) ?>">
                    <?=$this->getTokenField() ?>
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-download"></i> <?=$this->getTrans('download') ?>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<script src="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/js/star-rating.min.js') ?>"></script>
<script src="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/themes/krajee-fas/theme.min.js') ?>"></script>
<?php if (strncmp($this->getTranslator()->getLocale(), 'en', 2) !== 0) : ?>
    <script src="<?=$this->getVendorUrl('kartik-v/bootstrap-star-rating/js/locales/' . substr($this->getTranslator()->getLocale(), 0, 2) . '.js') ?>"></script>
<?php endif; ?>
<script>
    $('#rating').rating({
        language: '<?=substr($this->getTranslator()->getLocale(), 0, 2) ?>',
        showCaptionAsTitle: 'true',
        displayOnly: true,
        showCaption: false,
        theme: 'krajee-fas',
        filledStar: '<i class="fa-solid fa-star"></i>',
        emptyStar: '<i class="fa-regular fa-star"></i>',
        stars: 5,
        min: 0,
        max: 5,
        step: 0.5,
        size: 'xs'
    });

    $(document).ready(function(){
        $('#module-search-carousel').carousel();
    });
</script>
