<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiResource(
 *      
 *      
 *      collectionOperations={
 *          "createCompte_adminS" = {
 *              "method" = "POST",
 *              "path" =  "/adminSystem/compte/",
 *              "denormalization_context"={"groups"={"compte:write"}},
 *          },
 *      },
 *       itemOperations={
 *          "getCompte_adminS" = {
 *              "method" = "GET",
 *              "path" =  "/adminSystem/compte/{id}",
 *              "normalization_context"={"groups"={"compte:read"}},
 *          },
 *      }
 * )
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"compte:read"})
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"compte:read"})
     */
    private $solde;

    /**
     * @ORM\OneToMany(targetEntity=Depot::class, mappedBy="compte")
     * @Groups({"compte:read"})
     */
    private $depot;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, cascade={"persist", "remove"})
     * @Groups({"compte:read", "compte:write"})
     * @ApiSubresource
     */
    private $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compte")
     */
    private $transaction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adminSysteme")
     * @Groups({"compte:read"})
     */
    private $user;

    public function __construct()
    {
        $this->depot = new ArrayCollection();
        $this->transaction = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): self
    {
        $this->solde = $solde;

        return $this;
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
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depot->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
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
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
