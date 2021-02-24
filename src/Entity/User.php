<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"compte:read"})
     */
    private $username;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="user")
     */
    private $depot;

    /**
     * @ORM\OneToMany(targetEntity=Compte::class, mappedBy="user")
     */
    private $adminSysteme;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users")
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="user")
     */
    private $retrait;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="user")
     */
    private $deposer;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     */
    private $profil;

    public function __construct()
    {
        $this->depot = new ArrayCollection();
        $this->adminSysteme = new ArrayCollection();
        $this->agence = new ArrayCollection();
        $this->retrait = new ArrayCollection();
        $this->deposer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
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
     * @return Collection|Depot[]
     */
    public function getDepot(): Collection
    {
        return $this->depot;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depot->contains($depot)) {
            $this->depot[] = $depot;
            $depot->setUser($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depot->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getUser() === $this) {
                $depot->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getAdminSysteme(): Collection
    {
        return $this->adminSysteme;
    }

    public function addAdminSysteme(Compte $adminSysteme): self
    {
        if (!$this->adminSysteme->contains($adminSysteme)) {
            $this->adminSysteme[] = $adminSysteme;
            $adminSysteme->setUser($this);
        }

        return $this;
    }

    public function removeAdminSysteme(Compte $adminSysteme): self
    {
        if ($this->adminSysteme->removeElement($adminSysteme)) {
            // set the owning side to null (unless already changed)
            if ($adminSysteme->getUser() === $this) {
                $adminSysteme->setUser(null);
            }
        }

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getRetrait(): Collection
    {
        return $this->retrait;
    }

    public function addRetrait(Transaction $retrait): self
    {
        if (!$this->retrait->contains($retrait)) {
            $this->retrait[] = $retrait;
            $retrait->setUser($this);
        }

        return $this;
    }

    public function removeRetrait(Transaction $retrait): self
    {
        if ($this->retrait->removeElement($retrait)) {
            // set the owning side to null (unless already changed)
            if ($retrait->getUser() === $this) {
                $retrait->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getDeposer(): Collection
    {
        return $this->deposer;
    }

    public function addDeposer(Transaction $deposer): self
    {
        if (!$this->deposer->contains($deposer)) {
            $this->deposer[] = $deposer;
            $deposer->setUser($this);
        }

        return $this;
    }

    public function removeDeposer(Transaction $deposer): self
    {
        if ($this->deposer->removeElement($deposer)) {
            // set the owning side to null (unless already changed)
            if ($deposer->getUser() === $this) {
                $deposer->setUser(null);
            }
        }

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }


}
