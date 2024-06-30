<?php

namespace App\Entity;


use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserTypeRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["userType:read"]],
    denormalizationContext: ["groups" => ["userType:create", "userType:update"]]
)]
#[ORM\Table(name: "user_type")]
class UserType
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    #[Groups("userType:read", "user:read")]
    private ?int $id = null;


    #[ORM\Column(type: "string", length: 255)]
    #[Groups("userType:read", "userType:create", "userType:update", "user:read")]
    private ?string $Name = null;

    /**
     * @var Collection<int, User>
     */
    #[Groups("userType:read")]
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'userType')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addUserType($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeUserType($this);
        }

        return $this;
    }
}
