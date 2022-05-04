<?php

namespace App\Entity;

use App\Repository\PunchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PunchRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 * fields={"content", "id"},
 * message="Cette punch' existe déjà, plagieur que tié"
 * )
 */

class Punch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $theme;


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="punches")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="punch", orphanRemoval=true)
     */
    private $comments;

    /**
     * Permet de mettre en place la date de création
     * 
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist(){
        if(empty($this->date)){
            $this->date = new \DateTime();
        }
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPunch($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPunch() === $this) {
                $comment->setPunch(null);
            }
        }

        return $this;
    }

    public function getAvgRatings(){
        //Calculer la somme des notations
        $sum = array_reduce($this->comments->toArray(), function($total, $comment){
            return $total + $comment->getRating();
        }, 0);
        //Faire la division pour avoir la moyenne
        if(count($this->comments) > 0) return $sum / count($this->comments);

        return 0;
    }

}
