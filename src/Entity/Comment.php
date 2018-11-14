<?php

namespace App\Entity;

use App\DTO\CommentInitDTO;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     * @Assert\Length(min=10)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Billet", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $billet;

    /**
     * @var string
     * @ORM\Column(type="integer", nullable=false, options={"default":0})
     */
    private $signalement;

    /**
     * Comment constructor.
     *
     * @param User $user
     */
    public function __construct()
    {

        $this->createdAt = new \DateTime();
        $this->signalement = 0;

    }



    public function getId(): ?int
    {
        return $this->id;
    }


    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBillet(): ?billet
    {
        return $this->billet;
    }

    public function setBillet(?billet $billet): self
    {
        $this->billet = $billet;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $signalement): self
    {
        $this->content = $signalement;

        return $this;
    }

    public function getSignalement(): ?int
    {
        return $this->signalement;
    }

    public function setSignalement(int $signalement): self
    {
        $this->signalement = $signalement;

        return $this;
    }

    public function incrementSignalement() {
        $this->signalement +=1;

        return $this;
    }

}
