<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;

use Ilch\Accesses;
use Ilch\Layout\Base as Layout;
use Ilch\Registry;
use Ilch\View;
use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Mappers\Menu as MenuMapper;
use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Models\Box as BoxModel;
use Modules\Admin\Models\MenuItem;

class Model
{
    /**
     * @var Layout
     */
    protected Layout $layout;

    /**
     * Id of the menu.
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * Title of the menu.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * @var MenuMapper
     */
    protected MenuMapper $menuMapper;

    /**
     * @var BoxMapper
     */
    protected BoxMapper $boxMapper;

    /**
     * @var PageMapper
     */
    protected PageMapper $pageMapper;

    /**
     * @var string
     */
    protected string $currentUrl = '';

    /**
     * @var Accesses
     */
    protected Accesses $accessMapper;

    /**
     * Injects the layout.
     *
     * @param Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->layout = $layout;
        $this->menuMapper = new MenuMapper();
        $this->boxMapper = new BoxMapper();
        $this->pageMapper = new PageMapper();
        $this->accessMapper = new Accesses($layout->getRequest());
        $this->currentUrl = $layout->getCurrentUrl();
    }

    /**
     * Sets the menu id.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the menu id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the menu title.
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Gets the menu title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param string $tpl
     * @param array $options
     * @return string
     */
    public function getItems(string $tpl = '', array $options = []): string
    {
        // Build access permissions
        $groupIds = [3];
        $adminAccess = false;
        if ($this->layout->getUser()) {
            $groupIds = array_map(
                fn($group) => $group->getId(),
                $this->layout->getUser()->getGroups()
            );

            $adminAccess = $this->layout->getUser()->isAdmin();
        }

        // Load all items at once
        $allItems = $this->menuMapper->getMenuItems($this->getId());
        if (empty($allItems)) {
            return '';
        }

        // Prepare parent-child structure
        $menuData = [
            'items' => [],
            'parents' => []
        ];

        foreach ($allItems as $item) {
            $menuData['items'][$item->getId()] = $item;
            $menuData['parents'][$item->getParentId()][] = $item->getId();
        }

        // Check for root elements (parent ID 0)
        if (empty($menuData['parents'][0])) {
            return '';
        }

        $config = Registry::get('config');
        $locale = '';

        if ((bool)$config->get('multilingual_acp') && $this->layout->getTranslator()->getLocale() != $config->get('content_language')) {
            $locale = $this->layout->getTranslator()->getLocale();
        }

        $html = '';

        foreach ($menuData['parents'][0] as $itemId) {
            $item = $menuData['items'][$itemId];

            if (!is_in_array($groupIds, explode(',', $item->getAccess())) || $adminAccess) {
                if ($item->isBox()) {
                    if (array_dot($options, 'boxes.render') === false) {
                        continue;
                    }

                    if ($item->getBoxId()) {
                        if (!$this->accessMapper->hasAccess('Module', $item->getBoxId(), $this->accessMapper::TYPE_BOX)) {
                            continue;
                        }
                        // Get box without locale if no box with the requested locale was found. Display an "unlocalized"
                        // one instead of failing with an error or showing nothing.
                        $box = $this->boxMapper->getSelfBoxByIdLocale($item->getBoxId(), $locale) ?: $this->boxMapper->getSelfBoxByIdLocale($item->getBoxId());

                        // Purify content of user created box
                        $contentHtml = $this->layout->purify($box->getContent());
                    } else {
                        $box = $this->loadBoxFromModule($item);
                        $contentHtml = $box->getContent();
                    }
                } else {
                    // Menu item
                    $contentHtml = '<ul' . $this->createClassAttribute(array_dot($options, 'menus.ul-class-root')) . '>';
                    $contentHtml .= $this->buildMenu($item->getId(), $menuData, $locale, $options, $item->getType());
                    $contentHtml .= '</ul>';
                }

                $html .= str_replace(
                    ['%s', '%c'],
                    [$this->layout->escape($item->getTitle()), $contentHtml],
                    $tpl
                );
            }
        }

        return $html;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param int $parentId
     * @param array $menuData
     * @param string $locale
     * @param array $options
     * @param int|null $parentType
     * @return string
     */
    protected function buildMenu(int $parentId, array $menuData, string $locale, array $options = [], ?int $parentType = null): string
    {
        $html = '';
        $groupIds = [3];
        $adminAccess = false;

        if ($this->layout->getUser()) {
            $groupIds = [];
            foreach ($this->layout->getUser()->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            if ($this->layout->getUser()->isAdmin()) {
                $adminAccess = true;
            }
        }

        if (isset($menuData['parents'][$parentId])) {
            foreach ($menuData['parents'][$parentId] as $itemId) {
                $liClasses = [];

                // list classes
                if ($parentType === $menuData['items'][$itemId]::TYPE_MENU || array_dot($options, 'menus.allow-nesting') === false) {
                    $liClasses[] = array_dot($options, 'menus.li-class-root');
                } else {
                    $liClasses[] = array_dot($options, 'menus.li-class-child');
                }

                $target = '';
                $noopener = '';

                if ($menuData['items'][$itemId]->isPageLink()) {
                    if (!$this->accessMapper->hasAccess('Module', $menuData['items'][$itemId]->getSiteId(), $this->accessMapper::TYPE_PAGE)) {
                        continue;
                    }

                    $page = $this->pageMapper->getPageByIdLocale($menuData['items'][$itemId]->getSiteId(), $locale);
                    if (!$page) {
                        $page = $this->pageMapper->getPageByIdLocale($menuData['items'][$itemId]->getSiteId());
                    }

                    $href = $this->layout->getUrl($page ? $page->getPerma() : '');
                } elseif ($menuData['items'][$itemId]->isModuleLink()) {
                    if (!$this->accessMapper->hasAccess('Module', $menuData['items'][$itemId]->getModuleKey())) {
                        continue;
                    }

                    $href = $this->layout->getUrl(
                        ['module' => $menuData['items'][$itemId]->getModuleKey(), 'action' => 'index', 'controller' => 'index']
                    );
                } elseif ($menuData['items'][$itemId]->isLink()) {
                    $href = $menuData['items'][$itemId]->getHref();
                    $target = ' target="' . $menuData['items'][$itemId]->getTarget() . '"';
                    if ($menuData['items'][$itemId]->getTarget() === '_blank') {
                        $noopener = ' rel="noopener"';
                    }
                } else {
                    return '';
                }

                // add active class if configured and the link matches the origin source
                if ($href === $this->currentUrl) {
                    $liClasses[] = array_dot($options, 'menus.li-class-active');
                }

                if (!is_in_array($groupIds, explode(',', $menuData['items'][$itemId]->getAccess())) || $adminAccess) {
                    $title = $this->layout->escape($menuData['items'][$itemId]->getTitle());
                    $a_class_classAttribute = $this->createClassAttribute(array_dot($options, 'menus.a-class'));
                    $span_class_classAttribute = $this->createClassAttribute(array_dot($options, 'menus.span-class'));

                    $contentHtml = '<a' . $a_class_classAttribute . ' href="' . $href . '"' . $target . $noopener . '>' . (!empty($span_class_classAttribute) ? '<span' . $span_class_classAttribute . '>' . $title . '</span>' : $title) . '</a>';

                    // find childitems recursively
                    $subItemsHtml = $this->buildMenu($itemId, $menuData, $locale, $options, $menuData['items'][$itemId]->getType());

                    if (!empty($subItemsHtml) && array_dot($options, 'menus.allow-nesting') === true) {
                        $liClasses[] = array_dot($options, 'menus.li-class-root-nesting');
                        $contentHtml .= '<ul' . $this->createClassAttribute(array_dot($options, 'menus.ul-class-child'))
                            . '>' . $subItemsHtml . '</ul>';
                        $subItemsHtml = '';
                    }

                    $html .= '<li' . $this->createClassAttribute($liClasses) . '>' . $contentHtml . '</li>' . $subItemsHtml;
                }
            }
        }

        return $html;
    }

    /**
     * @param array|string $classes
     * @return string
     */
    private function createClassAttribute($classes): string
    {
        if (is_array($classes)) {
            $classes = array_filter($classes);
        }

        if (empty($classes)) {
            return '';
        }

        if (is_string($classes)) {
            $classes = [$classes];
        }

        return ' class="' . implode(' ', array_filter($classes)) . '"';
    }

    /**
     * @param MenuItem $item
     * @return BoxModel
     */
    protected function loadBoxFromModule(MenuItem $item): BoxModel
    {
        $parts = explode('_', $item->getBoxKey());
        $moduleKey = $parts[0];
        $boxKey = $parts[1];

        $class = '\\Modules\\' . ucfirst($moduleKey) . '\\Boxes\\' . ucfirst($boxKey);
        $view = new View($this->layout->getRequest(), $this->layout->getTranslator(), $this->layout->getRouter());
        $this->layout->getTranslator()->load(APPLICATION_PATH . '/modules/' . $moduleKey . '/translations');
        /** @var \Ilch\Box $boxObj */
        $boxObj = new $class(
            $this->layout,
            $view,
            $this->layout->getRequest(),
            $this->layout->getRouter(),
            $this->layout->getTranslator()
        );
        $boxObj->render();

        $layoutBoxFile = APPLICATION_PATH . '/' . dirname($this->layout->getFile()) . '/views/modules/'
            . $moduleKey . '/boxes/views/' . $boxKey . '.php';
        if (file_exists($layoutBoxFile)) {
            $viewPath = $layoutBoxFile;
        } else {
            $viewPath = APPLICATION_PATH . '/modules/' . $moduleKey . '/boxes/views/' . $boxKey . '.php';
        }

        $view->setLayoutKey($this->layout->getLayoutKey());
        $view->setBoxUrl('application/modules/' . $moduleKey);

        $output = $view->loadScript($viewPath);
        $box = new BoxModel();
        $box->setContent($output);
        return $box;
    }
}
