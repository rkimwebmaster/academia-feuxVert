<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=BroadcastMessage::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $broadcastMessage;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userReceiver;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isNonLu;

    public function __construct(BroadcastMessage $broadcastMessage)
    {
        $this->setBroadcastMessage($broadcastMessage);
        $this->createdAt=new \DateTime();
        $this->setIsNonLu(true);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBroadcastMessage(): ?BroadcastMessage
    {
        return $this->broadcastMessage;
    }

    public function setBroadcastMessage(?BroadcastMessage $broadcastMessage): self
    {
        $this->broadcastMessage = $broadcastMessage;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->userReceiver;
    }

    public function setUserReceiver(?User $userReceiver): self
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }

    public function getIsNonLu(): ?bool
    {
        return $this->isNonLu;
    }

    public function setIsNonLu(bool $isNonLu): self
    {
        $this->isNonLu = $isNonLu;

        return $this;
    }
}
