<?php

namespace Xemoe\Menu;

use \Xemoe\Contracts\StringTemplateContract;
use \Xemoe\Exceptions\InvalidArgumentException;
use \Xemoe\Menu\Contracts\MenuItemContract;
use \Xemoe\Menu\Contracts\ParentItemContract;

class ParentItem extends MenuItem implements ParentItemContract
{
    const ATTR_CHILD = 'child';

    protected $child;

    public function __construct(array $properties)
    {
        parent::__construct($properties);
        $this->child = [];
    }

    //
    // @override
    //
    protected function setRequired()
    {
        $this->required = [
            static::ATTR_NAME,
            static::ATTR_LABEL,
            static::ATTR_LINK,
        ];
        sort($this->required);
    }

    //
    // @override
    //
    protected function validate(array $properties)
    {
        ksort($properties);

        if (array_keys($properties) != $this->required) {
            throw new InvalidArgumentException(sprintf('Constructor required array contains keys'));
        }

        return true;
    }

    //
    // @override
    //
    public function toArray()
    {
        $parent = array_intersect_key(
            $this->attributes, 
            array_flip([static::ATTR_NAME, static::ATTR_LABEL, static::ATTR_LINK])
        );

        if (sizeof(static::getChild()) > 0) {
            $parent[static::ATTR_ACTIVE] = static::hasActiveChild();
            $parent[static::ATTR_CHILD] = [];
            foreach (static::getChild() as $child) {
                $child->setUri(static::getUri());
                $parent[static::ATTR_CHILD][$child->getName()] = $child->toArray();
            }
        } else {
            $parent[static::ATTR_ACTIVE] = static::isActive();
        }

        $ret = [
            $parent[static::ATTR_NAME] => $parent,
        ];

        return $ret;
    }

    public function addChild(MenuItemContract $item) {
        array_push($this->child, $item);
    }

    public function getChild() {
        return $this->child;
    }

    public function hasActiveChild() {
        $rule = static::parentActiveRule();
        return $rule($this);
    }

    protected function parentActiveRule()
    {
        $uri = static::getUri();

        return function(ParentItemContract $parent) use ($uri) {

            $active = false;
            foreach ($parent->getChild() as $child) {
                $child->setUri($uri);
                if ($child->isActive()) {
                    $active = true;
                }
            }

            return (bool) $active;
        };
    }
}
