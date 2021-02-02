<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\CreateImageAction;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('ROLE_USER')",
 *              "controller"=CreateImageAction::class,
 *              "deserialize"=false,
 *              "openapi_context"={
 *                  "requestBody"={
 *                      "content"={
 *                          "multipart/form-data"={
 *                              "schema"={
 *                                  "type"="object",
 *                                  "properties"={
 *                                      "file"={
 *                                          "type"="string",
 *                                          "format"="binary"
 *                                      }
 *                                  }
 *                              }
 *                          }
 *                      }
 *                  }
 *              }
 *          }
 *     },
 *     itemOperations={"get"},
 *     paginationEnabled=true,
 *     paginationItemsPerPage=12
 * )
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     * @ApiProperty(identifier=true)
     * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiProperty()
     * @ApiFilter(SearchFilter::class, strategy="ipartial")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiProperty()
     */
    private $description;

    /**
     * @ORM\Column(type="enum_image_category")
     * @ApiProperty()
     * @ApiFilter(SearchFilter::class, strategy="exact")
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity=File::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty()
     * @ApiProperty(iri="http://schema.org/image")
     */
    private $file;

    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
