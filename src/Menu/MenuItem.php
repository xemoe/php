<?php

namespace Xemoe\Menu;

use \Xemoe\Contracts\StringTemplateContract;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Menu\Contracts\MenuItemContract;

class MenuItem implements MenuItemContract
{
    protected $attributes;
    protected $currentUri;
    protected $required = [
        'name',
        'label',
        'link',
        'active',
    ];

    public function __construct(array $properties)
    {
        if (static::validate($properties)) {
            $this->attributes = $properties;
        }
    }

    protected function validate(array $properties)
    {
        sort($this->required);
        ksort($properties);

        if (array_keys($properties) != $this->required) {
            throw new InvalidArgumentException(sprintf('Constructor required array contains keys'));
        }

        if (!is_callable($properties['active'])) {
            throw new InvalidArgumentException(sprintf('Active construct parameter should be callable function'));
        }

        return true;
    }

    public function getName() {
        return $this->attributes['name'];
    }

    public function getLabel() {
        return $this->attributes['label'];
    }

    public function getLink() {
        return $this->attributes['link'];
    }

    public function getActiveRule() {
        return $this->attributes['active'];
    }

    public function setUri($uri) {
        $this->currentUri = $uri;
    }

    public function getUri() {
        return $this->currentUri;
    }

    public function isActive() {
        return $this->attributes['active']($this);
    }

    public function setTemplate(StringTemplateContract $template) {}
    public function getTemplate() {}
}
