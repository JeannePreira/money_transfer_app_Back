<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource()
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"part:read", "deposer:write", "retrait:write"})
     */
    private $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $CNI;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="client")
     * @Groups({"deposer:write"})
     */
    private $envoi;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="client")
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $recuperer;

    public function __construct()
    {
        $this->envoi = new ArrayCollection();
        $this->recuperer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getCNI(): ?string
    {
        return $this->CNI;
    }

    public function setCNI(string $CNI): self
    {
        $this->CNI = $CNI;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getEnvoi(): Collection
    {
        return $this->envoi;
    }

    public function addEnvoi(Transaction $envoi): self
    {
        if (!$this->envoi->contains($envoi)) {
            $this->envoi[] = $envoi;
            $envoi->setClient($this);
        }

        return $this;
    }

    public function removeEnvoi(Transaction $envoi): self
    {
        if ($this->envoi->removeElement($envoi)) {
            // set the owning side to null (unless already changed)
            if ($envoi->getClient() === $this) {
                $envoi->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getRecuperer(): Collection
    {
        return $this->recuperer;
    }

    public function addRecuperer(Transaction $recuperer): self
    {
        if (!$this->recuperer->contains($recuperer)) {
            $this->recuperer[] = $recuperer;
            $recuperer->setClient($this);
        }

        return $this;
    }

    public function removeRecuperer(Transaction $recuperer): self
    {
        if ($this->recuperer->removeElement($recuperer)) {
            // set the owning side to null (unless already changed)
            if ($recuperer->getClient() === $this) {
                $recuperer->setClient(null);
            }
        }

        return $this;
    }
}
