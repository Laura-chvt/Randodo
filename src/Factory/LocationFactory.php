<?php

namespace App\Factory;

use App\Entity\Location;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;


/**
 * @extends PersistentObjectFactory<Location>
 */
final class LocationFactory extends PersistentObjectFactory{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    #[\Override]    public static function class(): string
    {
        return Location::class;
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]    protected function defaults(): array|callable    {
        return [
            'gps' => self::faker()->text(150),
            'name' => self::faker()->text(150),
        ];
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Location $location): void {})
        ;
    }
}
