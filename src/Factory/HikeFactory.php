<?php

namespace App\Factory;

use App\Entity\Hike;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Hike>
 */
final class HikeFactory extends PersistentObjectFactory{
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
        return Hike::class;
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     */
    #[\Override]    protected function defaults(): array|callable    {
        return [
            'family'    => self::faker()->boolean(),
            'length'    => self::faker()->randomFloat(),
            'level'     => self::faker()->text(15),
            'name'      => self::faker()->text(255),
            'time'      => self::faker()->randomNumber(),
        ];
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Hike $hike): void {})
        ;
    }
}
