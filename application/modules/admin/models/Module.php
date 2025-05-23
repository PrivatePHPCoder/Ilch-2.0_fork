<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class Module extends \Ilch\Model
{
    /**
     * Language Content of the module.
     *
     * @var string|array
     */
    protected $content = [];

    /**
     * Key of the module.
     *
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * Small icon of the module.
     *
     * @var string
     */
    protected string $iconSmall = '';

    /**
     * @var bool
     */
    protected bool $systemModule = false;

    /**
     * @var bool
     */
    protected bool $layoutModule = false;

    /**
     * @var bool
     */
    protected bool $hideMenu = false;

    /**
     * @var string
     */
    protected string $author = '';

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var string
     */
    protected string $version = '';

    /**
     * @var string
     */
    protected string $link = '';

    /**
     * @var bool
     */
    protected bool $official = false;

    /**
     * @var string
     */
    protected string $ilchCore = '';

    /**
     * @var string
     */
    protected string $phpVersion = '';

    /**
     * @var array
     */
    protected array $phpExtension = [];

    /**
     * @var array
     */
    protected array $depends = [];

    /**
     * @var array
     */
    protected array $dependsCheck = [];

    /**
     * @var array
     * @since 2.2.8
     */
    protected array $folderRights = [];

    /**
     * @param array $entries
     * @return $this
     * @since 2.2.8
     */
    public function setByArray(array $entries): Module
    {
        if (!empty($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (!empty($entries['key'])) {
            $this->setKey($entries['key']);
        }
        if (!empty($entries['author'])) {
            $this->setAuthor($entries['author']);
        }
        if (!empty($entries['languages'])) {
            foreach ($entries['languages'] as $key => $value) {
                $this->addContent($key, $value);
            }
        }
        if (!empty($entries['system_module']) || !empty($entries['system'])) {
            $this->setSystemModule($entries['system'] ?? true);
        }
        if (!empty($entries['isLayout']) || !empty($entries['layout'])) {
            $this->setLayoutModule($entries['layout'] ?? true);
        }
        if (!empty($entries['hide_menu'])) {
            $this->setHideMenu(true);
        }
        if (!empty($entries['official'])) {
            $this->setOfficial(true);
        }
        if (!empty($entries['link'])) {
            $this->setLink($entries['link']);
        }
        if (!empty($entries['version'])) {
            $this->setVersion($entries['version']);
        }
        if (!empty($entries['icon_small'])) {
            $this->setIconSmall($entries['icon_small']);
        }
        if (!empty($entries['ilchCore'])) {
            $this->setIlchCore($entries['ilchCore']);
        }
        if (!empty($entries['phpVersion'])) {
            $this->setPHPVersion($entries['phpVersion']);
        }
        if (!empty($entries['phpExtensions'])) {
            foreach ($entries['phpExtensions'] as $extension) {
                $this->addPHPExtension($extension);
            }
        }
        if (!empty($entries['depends'])) {
            $this->setDepends($entries['depends']);
            foreach ($entries['depends'] as $depend => $value) {
                $this->dependsCheck[$depend] = false;
            }
        }
        if (isset($entries['phpVersion'])) {
            $this->setPHPVersion($entries['phpVersion']);
        }
        if (isset($entries['folderRights'])) {
            foreach ($entries['folderRights'] as $folder) {
                $this->addFolderRight($folder);
            }
        }

        return $this;
    }

    /**
     * Gets the key.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): Module
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Gets the author.
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Sets the author.
     *
     * @param string $author
     * @return $this
     */
    public function setAuthor(string $author): Module
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets the small icon.
     *
     * @return string
     */
    public function getIconSmall(): string
    {
        return $this->iconSmall;
    }

    /**
     * Sets system module flag.
     *
     * @param bool $system
     * @return $this
     */
    public function setSystemModule(bool $system): Module
    {
        $this->systemModule = $system;
        return $this;
    }

    /**
     * Gets system module flag.
     *
     * @return bool
     */
    public function getSystemModule(): bool
    {
        return $this->systemModule;
    }

    /**
     * Sets layout module flag.
     *
     * @param bool $layout
     * @return $this
     */
    public function setLayoutModule(bool $layout): Module
    {
        $this->layoutModule = $layout;
        return $this;
    }

    /**
     * Gets layout module flag.
     *
     * @return bool
     */
    public function getLayoutModule(): bool
    {
        return $this->layoutModule;
    }

    /**
     * Sets hide in menu flag.
     *
     * @param bool $hideMenu
     * @return $this
     */
    public function setHideMenu(bool $hideMenu): Module
    {
        $this->hideMenu = $hideMenu;
        return $this;
    }

    /**
     * Gets hide in menu flag.
     *
     * @return bool
     */
    public function getHideMenu(): bool
    {
        return $this->hideMenu;
    }

    /**
     * Sets the small icon.
     *
     * @param string $icon
     * @return $this
     */
    public function setIconSmall(string $icon): Module
    {
        $this->iconSmall = $icon;
        return $this;
    }

    /**
     * Add content for given language.
     *
     * @param string $langKey
     * @param string|array $content
     * @return $this
     */
    public function addContent(string $langKey, $content): Module
    {
        $this->content[$langKey] = $content;
        return $this;
    }

    /**
     * Gets content for given language.
     *
     * @return string|null|array
     */
    public function getContentForLocale($langKey)
    {
        if (!isset($this->content[$langKey])) {
            return null;
        }

        return $this->content[$langKey];
    }

    /**
     * Gets all content.
     *
     * @return array|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Module
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Sets the version.
     *
     * @param string $version
     * @return $this
     */
    public function setVersion(string $version): Module
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Gets the link.
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Sets the link.
     *
     * @param string $link
     * @return $this
     */
    public function setLink(string $link): Module
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Gets the official flag.
     *
     * @return bool
     */
    public function getOfficial(): bool
    {
        return $this->official;
    }

    /**
     * Sets the official flag.
     *
     * @param bool $official
     * @return $this
     */
    public function setOfficial(bool $official): Module
    {
        $this->official = $official;
        return $this;
    }

    /**
     * Gets the ilch core version.
     *
     * @return string
     */
    public function getIlchCore(): string
    {
        return $this->ilchCore;
    }

    /**
     * Sets the ilch core version.
     *
     * @param string $ilchCore
     * @return $this
     */
    public function setIlchCore(string $ilchCore): Module
    {
        $this->ilchCore = $ilchCore;
        return $this;
    }

    /**
     * Gets the php version.
     *
     * @return string
     */
    public function getPHPVersion(): string
    {
        return $this->phpVersion;
    }

    /**
     * Sets the php version.
     *
     * @param string $phpVersion
     * @return $this
     */
    public function setPHPVersion(string $phpVersion): Module
    {
        $this->phpVersion = $phpVersion;
        return $this;
    }

    /**
     * Gets the php extension.
     *
     * @return array
     */
    public function getPHPExtension(): array
    {
        return $this->phpExtension;
    }

    /**
     * Sets the php extension.
     *
     * @param array $phpExtension
     * @return $this
     */
    public function setPHPExtension(array $phpExtension): Module
    {
        $this->phpExtension = $phpExtension;
        return $this;
    }

    /**
     * Sets the php extension.
     *
     * @param string $extension
     * @param bool $state
     * @return $this
     * @since 2.2.8
     */
    public function addPHPExtension(string $extension, bool $state = false): Module
    {
        $this->phpExtension[$extension] = $state;
        return $this;
    }

    /**
     * Gets the dependencies.
     *
     * @return array
     */
    public function getDepends(): array
    {
        return $this->depends;
    }

    /**
     * Sets the dependencies.
     *
     * @param array $depends
     * @return $this
     */
    public function setDepends(array $depends): Module
    {
        $this->depends = $depends;
        return $this;
    }


    /**
     * Gets the dependencies.
     *
     * @return array
     * @since 2.2.8
     */
    public function getCheckDepends(): array
    {
        return $this->dependsCheck;
    }

    /**
     * Sets the dependencies.
     *
     * @param array $checkDepends
     * @return $this
     * @since 2.2.8
     */
    public function setCheckDepends(array $checkDepends): Module
    {
        $this->dependsCheck = $checkDepends;
        return $this;
    }

    /**
     * Sets the dependencies.
     *
     * @param string $key
     * @param bool $state
     * @return $this
     * @since 2.2.8
     */
    public function addCheckDepends(string $key, bool $state): Module
    {
        $this->dependsCheck[$key] = $state;
        return $this;
    }

    /**
     * Gets the folderRight.
     *
     * @return array
     * @since 2.2.8
     */
    public function getFolderRights(): array
    {
        return $this->folderRights;
    }

    /**
     * Sets the folderRight.
     *
     * @param array $folderRights
     * @return $this
     * @since 2.2.8
     */
    public function setFolderRights(array $folderRights): Module
    {
        $this->folderRights = $folderRights;
        return $this;
    }

    /**
     * Sets the php extension.
     *
     * @param string $folder
     * @param bool $state
     * @return $this
     * @since 2.2.8
     */
    public function addFolderRight(string $folder, bool $state = false): Module
    {
        $this->folderRights[$folder] = $state;
        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @return array
     * @since 2.2.8
     */
    public function getArray(): array
    {
        return [
            'key' => $this->getKey(),
            'system' => (int)$this->getSystemModule(),
            'layout' => (int)$this->getLayoutModule(),
            'hide_menu' => (int)$this->getHideMenu(),
            'icon_small' => $this->getIconSmall(),
            'version' => $this->getVersion(),
            'link' => $this->getLink(),
            'author' => $this->getAuthor()
        ];
    }
}
