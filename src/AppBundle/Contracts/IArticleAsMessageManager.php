<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/25/2018
 * Time: 1:15 PM
 */

namespace AppBundle\Contracts;


use AppBundle\BindingModel\ArticleAsMessageBindingModel;
use AppBundle\Entity\Article;
use AppBundle\Exception\ArticleNotFoundException;

interface IArticleAsMessageManager
{
    /**
     * @param ArticleAsMessageBindingModel $bindingModel
     */
    public function saveSettings(ArticleAsMessageBindingModel $bindingModel): void;

    /**
     * @param string $localeName
     * @return int
     * @throws ArticleNotFoundException
     */
    public function getSubscribeReward(string $localeName): Article;

    /**
     * @param string $localeName
     * @return int
     * @throws ArticleNotFoundException
     */
    public function getSubscribeMessage(string $localeName): Article;

    /**
     * @return ArticleAsMessageBindingModel[]
     */
    public function getArticleSettings(): array;
}