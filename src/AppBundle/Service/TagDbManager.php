<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/4/2018
 * Time: 6:56 PM
 */

namespace AppBundle\Service;


use AppBundle\Contracts\ITagDbManager;
use AppBundle\Entity\Tag;
use AppBundle\Exception\IllegalArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class TagDbManager implements ITagDbManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \AppBundle\Repository\TagRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private $tagRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
        $this->tagRepository = $em->getRepository(Tag::class);
    }

    /**
     * @param int $id
     * @return Tag|null
     */
    public function findById(int $id): ?Tag
    {
       return $this->tagRepository->findOneBy(array('id'=>$id));
    }

    /**
     * @param string $name
     * @return Tag|null
     */
    public function findByName(string $name): ?Tag
    {
       return $this->tagRepository->findOneBy(array('tagName'=>$name));
    }

    /**
     * @param string $tags
     * @return Tag[]
     * @throws IllegalArgumentException
     */
    public function addTags(string $tags = null): array
    {
        $result = array();
        $tagParts = $this->parseTags($tags);
        foreach ($tagParts as $tag){
            $persistedTag = $this->findByName(trim($tag));
            if($persistedTag == null){
                if(strlen($tag) > 50)
                    throw new IllegalArgumentException("Tag length too long (50)");
                $persistedTag = new Tag();
                $persistedTag->setTagName(trim($tag));
                $this->entityManager->persist($persistedTag);
                $result[] = $persistedTag;
            }else{
                $result[] = $persistedTag;
            }
        }
        $this->entityManager->flush();
        return $result;
    }

    /**
     * @return Tag[]
     */
    public function findAll(): array
    {
        return $this->tagRepository->findAll();
    }

    private function parseTags(string $tags = null){
        if($tags == null) return array();
        return array_unique(preg_split('/\s+/', $tags));
    }
}