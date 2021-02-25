<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiResource(
 *      attributes = {
 *         "security" = "is_granted('ROLE_ADMIN-SYSTEM')",
 *         "security_message" = "Seules les admin ont accèes à cette ressource!"
 *      },
 *      collectionOperations={
 *          "getPart_adminS" = {
 *              "method" = "get",
 *              "path" =  "/adminSystem/transaction/",
 *              "normalization_context"={"groups"={"part:read"}},
 *              "security" = "(is_granted('ROLE_ADMIN-SYSTEM') or is_granted('ROLE_ADMIN_AGENCE'))",
 *              "security_message" = "Seules les admin ont accèes à cette ressource!"
 *          },
 *           "Transaction_User" = {
 *              "method" = "POST",
 *              "path" =  "utilisateur/deposer/",
 *              "denormalization_context"={"groups"={"deposer:write"}}
 *          },
 *          "Transaction_User" = {
 *              "method" = "POST",
 *              "path" =  "utilisateur/retrait/",
 *              "denormalization_context"={"groups"={"retrait:write"}}
 *          }
 *      },
 *      itemOperations={
 *          "delete", "get"
 *      }
 * )
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"part:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $montant;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"deposer:write"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"retrait:write"})
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $TTC;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"part:read", "deposer:write", "retrait:write"})
     */
    private $fraisEtat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"part:read", "deposer:write", "retrait:write"})
     */
    private $fraisSystem;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"deposer:write","retrait:write"})
     */
    private $fraisEnvoi;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $fraisRetrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $codeTransaction;


    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transaction")
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="envoi", cascade={"persist", "remove"})
     * @Groups({"deposer:write", "retrait:write"})
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="retrait")
     * @Groups({"transaction:write", "retrait:write"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    public function getTTC(): ?string
    {
        return $this->TTC;
    }

    public function setTTC(string $TTC): self
    {
        $this->TTC = $TTC;

        return $this;
    }

    public function getFraisEtat(): ?string
    {
        return $this->fraisEtat;
    }

    public function setFraisEtat(string $fraisEtat): self
    {
        $this->fraisEtat = $fraisEtat;

        return $this;
    }

    public function getFraisSystem(): ?string
    {
        return $this->fraisSystem;
    }

    public function setFraisSystem(string $fraisSystem): self
    {
        $this->fraisSystem = $fraisSystem;

        return $this;
    }

    public function getFraisEnvoi(): ?string
    {
        return $this->fraisEnvoi;
    }

    public function setFraisEnvoi(string $fraisEnvoi): self
    {
        $this->fraisEnvoi = $fraisEnvoi;

        return $this;
    }

    public function getFraisRetrait(): ?string
    {
        return $this->fraisRetrait;
    }

    public function setFraisRetrait(string $fraisRetrait): self
    {
        $this->fraisRetrait = $fraisRetrait;

        return $this;
    }

    public function getCodeTransaction(): ?string
    {
        return $this->codeTransaction;
    }

    public function setCodeTransaction(string $codeTransaction): self
    {
        $this->codeTransaction = $codeTransaction;

        return $this;
    }
    
    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
