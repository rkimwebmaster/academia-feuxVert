<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     errorPath="email",
 *     message="Cette valeur est déjà utilisé et ne peut exister en double."
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180)
     * @Assert\Length(min=4, minMessage="Le username doit avoir plus de 3 caracères")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\Length(max=4096, min=8)
     */
    private $plainPassword;



    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;





    /**
     * @ORM\ManyToOne(targetEntity=Faculte::class)
     */
    private $faculte;



    /**
     * @ORM\OneToOne(targetEntity=Finaliste::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $finaliste;

    /**
     * @ORM\OneToOne(targetEntity=Enseignant::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $enseignant;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userReceiver")
     */
    private $messages;

    /**
     * @ORM\Column(type="integer")
     */
    private $nombreMessageNonLu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true )
     */
    private $photo;

    public function __toString()
    {
        return $this->getUsername();
    }

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->setNombreMessageNonLu(0);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFinaliste(): ?Finaliste
    {
        return $this->finaliste;
    }

    public function setFinaliste(?Finaliste $finaliste): self
    {
        $this->finaliste = $finaliste;

        return $this;
    }

    public function getFaculte(): ?Faculte
    {
        return $this->faculte;
    }

    public function setFaculte(?Faculte $faculte): self
    {
        $this->faculte = $faculte;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUserReceiver($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUserReceiver() === $this) {
                $message->setUserReceiver(null);
            }
        }

        return $this;
    }

    public function getNombreMessageNonLu(): ?int
    {
        return $this->nombreMessageNonLu;
    }

    public function setNombreMessageNonLu(int $nombreMessageNonLu): self
    {
        $this->nombreMessageNonLu = $nombreMessageNonLu;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
