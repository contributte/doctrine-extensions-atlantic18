<?php declare(strict_types = 1);

namespace Tests\Fixtures;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'dummy')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
class DummyEntity
{

	#[ORM\Id]
	#[ORM\GeneratedValue(strategy: 'IDENTITY')]
	#[ORM\Column(type: Types::INTEGER)]
	private int $id;

	#[ORM\Column(type: Types::STRING, length: 255)]
	private string $title;

	#[ORM\Column(type: Types::STRING, length: 255, unique: true)]
	#[Gedmo\Slug(fields: ['title'])]
	private string $slug;

	#[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
	#[Gedmo\Timestampable(on: 'create')]
	private DateTimeImmutable $createdAt;

	#[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
	#[Gedmo\Timestampable(on: 'update')]
	private DateTimeImmutable $updatedAt;

	#[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
	#[Gedmo\Blameable(on: 'create')]
	private ?string $createdBy = null;

	#[ORM\Column(type: Types::STRING, length: 45, nullable: true)]
	#[Gedmo\IpTraceable(on: 'create')]
	private ?string $createdFromIp = null;

	#[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
	private ?DateTimeImmutable $deletedAt = null;

	public function __construct(string $title)
	{
		$this->title = $title;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function setTitle(string $title): void
	{
		$this->title = $title;
	}

	public function getSlug(): string
	{
		return $this->slug;
	}

	public function getCreatedAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function getUpdatedAt(): DateTimeImmutable
	{
		return $this->updatedAt;
	}

	public function getCreatedBy(): ?string
	{
		return $this->createdBy;
	}

	public function getCreatedFromIp(): ?string
	{
		return $this->createdFromIp;
	}

	public function getDeletedAt(): ?DateTimeImmutable
	{
		return $this->deletedAt;
	}

}
