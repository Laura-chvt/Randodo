<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory{

    public const DEFAULT_PASSWORD = "P@ssw0rd";

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    #[\Override]    public static function class(): string
    {
        return User::class;
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    #[\Override]    protected function defaults(): array|callable    {
        return [
            'created_at'    => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'email'         => self::faker()->email(),
            'firstname'     => self::faker()->firstName(),
            'isVerified'    => self::faker()->boolean(),
            'name'          => self::faker()->lastName(),
            'password'      => $this->userPasswordHasher->hashPassword(new User(), self::DEFAULT_PASSWORD),
            'roles'         => [],
        ];
    }

        /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(User $user): void {})
        ;
    }
}
