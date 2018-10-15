<?php
/**
 * Created by PhpStorm.
 * User: ceci
 * Date: 10/15/2018
 * Time: 8:13 PM
 */

namespace AppBundle\Controller;

use AppBundle\Contracts\ILogDbManager;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;
use AppBundle\Util\PageRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

class LogController extends BaseController
{
    /**
     * @var ILogDbManager
     */
    private $logService;

    public function __construct(LocalLanguage $language, ILogDbManager $logDb)
    {
        parent::__construct($language);
        $this->logService = $logDb;
    }

    /**
     * @Route("/admin/logs/show", name="show_logs")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function showLogsAction()
    {
        return $this->render('admin/logs/browse-logs.html.twig', [
            'logsPage' => $this->logService->findAll(new PageRequest(1, 2))
        ]);
    }

    /**
     * @Route("/admin/logs/show-more", name="show_more_logs")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadMoreLogs(Request $request)
    {
        return $this->render('queries/load-more-logs.html.twig', [
            'page'=>$this->logService->findAll(new Pageable($request))
        ]);
    }
}