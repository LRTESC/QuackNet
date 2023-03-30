<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Duck;

class DuckVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, mixed $subject ): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute,  [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Duck;
    }

    protected function voteOnAttribute(string         $attribute, mixed $subject,
                                       TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On verifie si l'annonce a un propriétaire
        if (null === $subject->getUsers()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // on verifie si on peut éditer
                return $this->canEdit($user);

                break;
            case self::DELETE:
                // on verifie si on peut supprimer
                return $this->canDelete($user);
                break;
        }

        return false;
    }

    private function canEdit(Duck $duck, Users $users){
        // le propriétaire de l'annonce peut la modifier
        return $users === $duck->getUsers();
    }

    private function canDelete(Duck $duck, Users $usucers){
        // le propriétaire de l'annonce peut la supprimer
        return $users === $duck->getUsers();
    }
}
