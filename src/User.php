<?php

namespace Iconic\Security;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, IdentifiableUserInterface, OwnerUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(nullable: false)]
    private int $id = 0;

    /**
     * @var non-empty-string
     */
    #[ORM\Column(length: 180, nullable: false)]
    private string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public ?string $freeTextPassword = null;
    public ?string $freeTextPasswordVerification = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postalCode = null;

    public function __construct()
    {
    }

    /**
     * @param array<string, mixed> $data
     * @return User
     */
    public static function fromArray(array $data): User
    {
        $user = new self();

        if (isset($data['id']) && is_numeric($data['id'])) {
            $user->id = (int)$data['id'];
        }

        if (isset($data['email']) && is_string($data['email']) && trim($data['email']) !== '') {
            $email = $data['email'];
            /** @var non-empty-string $email */
            $user->setEmail($email);
        }

        if (isset($data['password']) && is_string($data['password'])) {
            $user->setPassword($data['password']);
        }

        if (isset($data['roles']) && is_array($data['roles'])) {
            $roles = $data['roles'];
            /** @var list<string> $roles */
            $user->setRoles($roles);
        }

        if (isset($data['isVerified'])) {
            $user->setVerified((bool)$data['isVerified']);
        }

        if (isset($data['name']) && is_string($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['surname']) && is_string($data['surname'])) {
            $user->setSurname($data['surname']);
        }

        if (isset($data['address']) && is_string($data['address'])) {
            $user->setAddress($data['address']);
        }

        if (isset($data['city']) && is_string($data['city'])) {
            $user->setCity($data['city']);
        }

        if (isset($data['postalCode']) && is_string($data['postalCode'])) {
            $user->setPostalCode($data['postalCode']);
        }

        if (isset($data['freeTextPassword']) && is_string($data['freeTextPassword'])) {
            $user->freeTextPassword = $data['freeTextPassword'];
        }

        if (isset($data['freeTextPasswordVerification']) && is_string($data['freeTextPasswordVerification'])) {
            $user->freeTextPasswordVerification = $data['freeTextPasswordVerification'];
        }

        return $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param non-empty-string $email
     *
     * @return $this
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return non-empty-string
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(Role $role): void
    {
        $this->roles[] = $role->value;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function isAdmin(): bool
    {
        return in_array(Role::ADMINISTRATOR->value, $this->getRoles());
    }

    public function isUser(): bool
    {
        return in_array(Role::USER->value, $this->getRoles());
    }

    public function isOwnerOf(OwnableInterface $ownable): bool
    {
        return $ownable->getOwner()->getId() === $this->getId();
    }
}
