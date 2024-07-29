<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\State\UserPasswordHasher;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['user:list']]),
        new Post(processor: UserPasswordHasher::class, validationContext: ['groups' => ['Default', 'user:create']]),
        new Get(normalizationContext: ['groups' => ['user:read']]),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]
#[ApiFilter(SearchFilter::class, properties: ['userType' => 'exact', 'team' => 'exact', 'lastname' => 'partial', 'firstname' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:read', 'user:list'])]
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Groups(['user:read', 'user:create', 'user:update', 'user:list'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    private ?string $temporaryPassword = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create', 'user:update', 'user:list'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:create', 'user:update', 'user:list'])]
    private ?string $firstname = null;

    #[ORM\ManyToOne(inversedBy: 'players')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    #[MaxDepth(1)]
    private ?Team $team = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?bool $hasToChangePassword = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:read', 'user:create', 'user:update', 'user:list'])]
    private ?string $LicenceNumber = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?UserType $userType = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->hasToChangePassword = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getTemporaryPassword(): ?string
    {
        return $this->temporaryPassword;
    }

    public function setTemporaryPassword(?string $temporaryPassword): self
    {
        $this->temporaryPassword = $temporaryPassword;
        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->temporaryPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function hasToChangePassword(): ?bool
    {
        return $this->hasToChangePassword;
    }

    public function setHasToChangePassword(bool $hasToChangePassword): static
    {
        $this->hasToChangePassword = $hasToChangePassword;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLicenceNumber(): ?string
    {
        return $this->LicenceNumber;
    }

    public function setLicenceNumber(?string $LicenceNumber): static
    {
        $this->LicenceNumber = $LicenceNumber;
        return $this;
    }

    public function getUserType(): ?UserType
    {
        return $this->userType;
    }

    public function setUserType(?UserType $userType): static
    {
        $this->userType = $userType;
        return $this;
    }
}