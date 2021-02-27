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
 *         "security" = "is_granted('ROLE_ADMIN_AGENCE')",
 *         "security_message" = "Seules les admin ont accèes à cette ressource!",
 *            "denormalization_context"={"groups"={"deposer:write"}},
 *      },
 *      collectionOperations={
 *          "getPart_adminS" = {
 *              "method" = "get",
 *              "path" =  "/adminSystem/transaction/",
 *              "normalization_context"={"groups"={"part:read"}},
 *              "security" = "(is_granted('ROLE_ADMIN-SYSTEM') or is_granted('ROLE_ADMIN_AGENCE'))",
 *              "security_message" = "Seules les admin ont accèes à cette ressource!"
 *          },
 *           "TransactionDepot" = {
 *              "method" = "POST",
 *              "path" =  "/utilisateur/deposer/",
 *              "deserialize" =false,
 *              "route_name" = "depotTransaction"
 *          },
 *          "TransactionRetrait" = {
 *              "method" = "POST",
 *              "path" =  "/utilisateur/retrait/",
 *              "deserialize" =false,
 *              "route_name" = "retraitTransaction"
 *          }
 *      },
 *      itemOperations={
 *          "Impression" = {
 *              "method" = "GET",
 *              "path" =  "/utilisateur/transaction/{id}",
 *              "normalization_context"={"groups"={"impression:read"}},
 *          } 
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
     * @ORM\Column(type="date")
     * @Groups({"deposer:write", "impression:read"})
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"retrait:write", "deposer:write"})
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
     * @Groups({"deposer:write", "retrait:write", "impression:read"})
     */
    private $codeTransaction;


    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transaction")
     * @Groups({"retrait:write"})
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transaction")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $userDepot;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactionRetrait")
     * @ORM\JoinColumn(nullable=true)
     */
    private $userRetrait;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transaction", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"impression:read"})
     */
    private $clientEnvoi;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="transactionRetrait", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"impression:read"})
     */
    private $clientRetrait;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"deposer:write"})
     * 
     */
    private $montant;



    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserDepot(): ?User
    {
        return $this->userDepot;
    }

    public function setUserDepot(?User $userDepot): self
    {
        $this->userDepot = $userDepot;

        return $this;
    }

    public function getUserRetrait(): ?User
    {
        return $this->userRetrait;
    }

    public function setUserRetrait(?User $userRetrait): self
    {
        $this->userRetrait = $userRetrait;

        return $this;
    }

    public function getClientEnvoi(): ?Client
    {
        return $this->clientEnvoi;
    }

    public function setClientEnvoi(?Client $clientEnvoi): self
    {
        $this->clientEnvoi = $clientEnvoi;

        return $this;
    }

    public function getClientRetrait(): ?Client
    {
        return $this->clientRetrait;
    }

    public function setClientRetrait(?Client $clientRetrait): self
    {
        $this->clientRetrait = $clientRetrait;

        return $this;
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

}
