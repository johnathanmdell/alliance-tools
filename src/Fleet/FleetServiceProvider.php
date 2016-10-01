<?php
namespace AllianceTools\Fleet;

use AllianceTools\Fleet\Repository\EloquentFleetRepository;
use AllianceTools\Fleet\Repository\FleetRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class FleetServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FleetInterface::class, EloquentFleet::class);
        $this->app->bind(FleetRepositoryInterface::class, EloquentFleetRepository::class);
    }
}