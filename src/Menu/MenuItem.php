<?php

namespace Xemoe\Menu;

use \Xemoe\Contracts\StringTemplateContract;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Menu\Contracts\MenuItemContract;

class MenuItem implements MenuItemContract
{
    const ATTR_NAME = 'name';
    const ATTR_LABEL = 'label';
    const ATTR_LINK = 'link';
    const ATTR_RULE = 'rule';
    const ATTR_ACTIVE = 'active';

    protected $attributes;
    protected $currentUri;
    protected $required;

    public function __construct(array $properties)
    {
        static::setRequired();
        if (static::validate($properties)) {
            $this->attributes = $properties;
        }
    }

    protected function setRequired()
    {
        $this->required = [
            static::ATTR_NAME,
            static::ATTR_LABEL,
            static::ATTR_LINK,
            static::ATTR_RULE,
        ];
        sort($this->required);
    }

    protected function validate(array $properties)
    {
        ksort($properties);

        if (array_keys($properties) != $this->required) {
            throw new InvalidArgumentException(sprintf('Constructor required array contains keys'));
        }

        if (!is_callable($properties[static::ATTR_RULE])) {
            throw new InvalidArgumentException(sprintf('Rule construct parameter should be callable function'));
        }

        return true;
    }

    public function getName() {
        return $this->attributes[static::ATTR_NAME];
    }

    public function getLabel() {
        return $this->attributes[static::ATTR_LABEL];
    }

    public function getLink() {
        return $this->attributes[static::ATTR_LINK];
    }

    public function setActiveRule($callable)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Rule parameter should be callable function'));
        }
        
        $this->attributes[static::ATTR_RULE] = $callable;
    }

    public function getActiveRule() {
        return $this->attributes[static::ATTR_RULE];
    }

    public function setUri($uri) {
        $this->currentUri = $uri;
    }

    public function getUri() {
        return $this->currentUri;
    }

    public function isActive() {
        $rule = static::getActiveRule();
        return $rule($this);
    }

    public function setTemplate(StringTemplateContract $template) {}
    public function getTemplate() {}

    public function toArray()
    {
        $ret = array_intersect_key(
            $this->attributes, 
            array_flip([static::ATTR_NAME, static::ATTR_LABEL, static::ATTR_LINK])
        );
        $ret[static::ATTR_ACTIVE] = static::isActive();

        return $ret;
    }
}
