<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 07/11/2018
 * Time: 14:39
 */

namespace App\Manager;


use App\Entity\Billet;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function createComment(Comment $comment, $name, Billet $billet)
    {

        $comment->setAuthor($name);
        $comment->setBillet($billet);
        $this->save($comment);
    }

    public function save(Comment $comment)
    {
        $this->manager->persist($comment);
        $this->manager->flush();
    }

    public function incrementComment(Comment $comment)
    {
        $comment->incrementSignalement();
        $this->save($comment);
    }


}