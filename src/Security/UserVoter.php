<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match($attribute) {
            'EDIT' => $this->canEdit($subject, $user),
            'DELETE' => $this->canDelete($subject, $user),
            default => false,
        };
    }

    private function canEdit(User $subject, User $user): bool
    {
        return $user->isAdmin() || $user->getId() === $subject->getId();
    }

    private function canDelete(User $subject, User $user): bool
    {
        return $user->isAdmin() && $user->getId() !== $subject->getId();
    }
} 