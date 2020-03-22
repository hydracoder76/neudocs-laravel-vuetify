<?php

namespace NeubusSrm\Providers;

use Illuminate\Support\ServiceProvider;
use NeubusSrm\Lib\Builders\Menu\Impl\BaseMenuBuilder;
use NeubusSrm\Lib\Builders\Menu\Impl\BaseMenuNode;
use NeubusSrm\Lib\Builders\Menu\MenuBuilder;
use NeubusSrm\Lib\Builders\Menu\MenuNode;
use NeubusSrm\Lib\Builders\MenuBuilderFactory;

/**
 * Class BuilderServiceProvider
 * @package NeubusSrm\Providers
 */
class BuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(MenuBuilderFactory::class)->needs(MenuBuilder::class)->give(BaseMenuBuilder::class);
        $this->app->when(MenuBuilder::class)->needs(MenuNode::class)->give(BaseMenuNode::class);
    }
}
