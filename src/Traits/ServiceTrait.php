<?php
namespace Yivic\Wp\Plugin\Traits;

use Illuminate\Container\Container;

trait ServiceTrait {
    /**
     * @var Container|null
     */
    protected ?Container $_container = null;

    abstract public function init();

    /**
     * @param Container $container
     *
     * @noinspection PhpUnusedDeclarationInspection
     */
    public function setContainer( Container $container ) {
        if ( null === $this->_container ) {
            $this->_container = $container;
        }
    }

    /**
     * @return Container
     */
    public function getContainer(): Container {
        return $this->_container;
    }
}
