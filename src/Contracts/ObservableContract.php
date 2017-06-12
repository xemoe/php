<?php

namespace Xemoe\Contracts;

interface ObservableContract
{
    public function getObserverMembers();
    public function getObserverInstances();
    public function getObserver($klass);
    public function hasObserver($klass);
    public function setObserver($instance, $klass);
}
