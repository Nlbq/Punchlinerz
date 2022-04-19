<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PasswordUpdateRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\component\Validator\Constraints as Assert;



class PasswordUpdate
{

    private $oldPassword;

    /**
     *
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au moins 8 caractères")
     */
    private $newPassword;

    /**
     * 
     */
    private $confirmPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
