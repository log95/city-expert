<?php

namespace App\Entity;

use App\Enum\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json", options={"default": "[]", "jsonb": true})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TestInterest", mappedBy="user")
     */
    private $testInterests;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default": null})
     */
    private $verifiedAt;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    public function __construct()
    {
        $this->testInterests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = Role::USER;

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

    /**
     * @return Collection|TestInterest[]
     */
    public function getTestInterests(): Collection
    {
        return $this->testInterests;
    }

    public function addTestInterest(TestInterest $testInterest): self
    {
        if (!$this->testInterests->contains($testInterest)) {
            $this->testInterests[] = $testInterest;
            $testInterest->setUser($this);
        }

        return $this;
    }

    public function removeTestInterest(TestInterest $testInterest): self
    {
        if ($this->testInterests->contains($testInterest)) {
            $this->testInterests->removeElement($testInterest);
            // set the owning side to null (unless already changed)
            if ($testInterest->getUser() === $this) {
                $testInterest->setUser(null);
            }
        }

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function isVerified(): bool
    {
        return !\is_null($this->verifiedAt);
    }

    public function setVerifiedAt(?\DateTimeInterface $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function markVerified(): self
    {
        $this->verifiedAt = new \DateTime();

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
