<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CommentVoter extends Voter
{
    public const EDIT   = 'EDIT';
    public const DELETE = 'DELETE';

    public function __construct(private Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $subject instanceof Comment;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Comment $comment */
        $comment = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $user === $comment->getUserComment();
                
            case self::DELETE:
                if ($this->security->isGranted('ROLE_MODO')) {
                    return true;
                }
                return $user === $comment->getUserComment();
        }

        return false;
    }
}
