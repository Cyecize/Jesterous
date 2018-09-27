<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 9/25/2018
 * Time: 1:29 PM
 */

namespace AppBundle\Service;


use AppBundle\BindingModel\ArticleAsMessageBindingModel;
use AppBundle\Constants\Config;
use AppBundle\Contracts\IArticleAsMessageManager;
use AppBundle\Contracts\IArticleDbManager;
use AppBundle\Entity\Article;
use AppBundle\Exception\ArticleNotFoundException;
use AppBundle\Util\YamlParser;
use AppBundle\ViewModel\ArticleAsMessageViewModel;

class ArticleAsMessageManager implements IArticleAsMessageManager
{
    private const FILE_PATH = "../app/config/article_as_message.yml";

    private const SUBSCRIBE_REWARD = "subscribe_reward";
    private const SUBSCRIBE_MESSAGE = "subscribe_message";

    private const BINDING_MODEL_NAME_DELIMITER = "__";

    /**
     * @var IArticleDbManager
     */
    private $articleService;

    private $settings;

    public function __construct(IArticleDbManager $articleDb)
    {
        $this->settings = YamlParser::getFile(self::FILE_PATH);
        $this->articleService = $articleDb;
    }

    public function saveSettings(ArticleAsMessageBindingModel $bindingModel): void
    {
        $settings = $this->extractSettingsFromBindingModel($bindingModel);
        YamlParser::saveFile($settings, self::FILE_PATH);
    }

    public function getSubscribeReward(string $localeName): Article
    {
        return $this->getArticle($this->extractArticleId(self::SUBSCRIBE_REWARD, $localeName));
    }

    public function getSubscribeMessage(string $localeName): Article
    {
        return $this->getArticle($this->extractArticleId(self::SUBSCRIBE_MESSAGE, $localeName));
    }

    /**
     * @return ArticleAsMessageViewModel[]
     */
    public function getArticleSettings(): array
    {
        $res = array();
        foreach ($this->settings as $sectionName => $options) {
            $res[] = new ArticleAsMessageViewModel($sectionName, $options);
        }
        return $res;
    }

    //Private logic

    /**
     * @param string $section
     * @param $localeName
     * @return int
     * @throws ArticleNotFoundException
     */
    private function extractArticleId(string $section, $localeName): int
    {
        $sectionArr = $this->settings[$section];
        $articleLocale = $this->getArticleName($localeName);
        if (!array_key_exists($articleLocale, $sectionArr))
            throw new ArticleNotFoundException();
        return intval($sectionArr[$articleLocale]);
    }

    private function getArticle(int $id) : Article{
        $article = $this->articleService->findOneById($id, true);
        if($article == null)
            throw new ArticleNotFoundException();
        return $article;
    }

    private function getArticleName(string $locale): string
    {
        switch ($locale) {
            case Config::COOKIE_BG_LANG:
                return "bg_article";
            case Config::COOKIE_EN_LANG:
                return "en_article";
            default:
                return "bg_article";
        }
    }

    private function extractSettingsFromBindingModel(ArticleAsMessageBindingModel $bindingModel): array
    {
        $reflection = new \ReflectionObject($bindingModel);
        $res = array();
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $settingNameTokens = explode(self::BINDING_MODEL_NAME_DELIMITER, $property->getName());
            if (count($settingNameTokens) != 2)
                continue;
            $res[$settingNameTokens[0]][$settingNameTokens[1]] = $property->getValue($bindingModel);
        }
        return $res;
    }
}