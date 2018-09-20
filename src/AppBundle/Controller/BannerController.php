<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/20/2018
 * Time: 2:17 PM
 */

namespace AppBundle\Controller;


use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Contracts\IBannerDbManager;
use AppBundle\Exception\IllegalArgumentException;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\ImageType;
use AppBundle\Service\LocalLanguage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class BannerController extends BaseController
{
    private const BANNER_NOT_FOUND_MSG = "Banner was not found!";
    /**
     * @var IBannerDbManager
     */
    private $bannerService;

    public function __construct(LocalLanguage $language, IBannerDbManager $bannerDb)
    {
        parent::__construct($language);
        $this->bannerService = $bannerDb;
    }

    /**
     * @Route("/admin/banners/upload", name="upload_banner")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function createBannerAction(Request $request)
    {
        $bindingModel = new ImageBindingModel();
        $form = $this->createForm(ImageType::class, $bindingModel);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;
            $this->bannerService->uploadBanner($bindingModel);
            return $this->redirectToRoute('admin_panel', ['info' => "Banner Uploaded!"]);
        }

        escape:
        return $this->render('admin/banners/upload-banner.html.twig', [
            'form1' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/banners/browse", name="browse_banners")
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myImagesAction(Request $request)
    {
        return $this->render('admin/banners/browse-banners.html.twig', [
            'banners' => $this->bannerService->findAll(),
        ]);
    }

    /**
     * @Route("/admin/banners/remove/{bannerId}", name="remove_banner", defaults={"bannerId"=-1}, methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $bannerId
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function removeBannerAction(Request $request, $bannerId) {
        $this->validateToken($request);
        $banner = $this->bannerService->findOneById(intval($bannerId));
        if($banner == null)
            throw new RestFriendlyExceptionImpl(self::BANNER_NOT_FOUND_MSG);
        $this->bannerService->removeBanner($banner);
        return new JsonResponse(['message'=>"Banner Removed!"]);
    }

    /**
     * @Route("/admin/banners/edit/{bannerId}", name="edit_banner", defaults={"bannerId"=-1}, methods={"POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param $bannerId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws IllegalArgumentException
     * @throws RestFriendlyExceptionImpl
     */
    public function editBannerAction(Request $request, $bannerId){
        $this->validateToken($request);
        $banner = $this->bannerService->findOneById(intval($bannerId));
        if($banner == null)
            throw new IllegalArgumentException(self::BANNER_NOT_FOUND_MSG);
        $orderIndex = $request->get('orderIndex');
        if($orderIndex == null)
            throw new IllegalArgumentException($this->language->fieldCannotBeEmpty());
        $this->bannerService->editBannerIndex($banner, intval($orderIndex));
        return $this->redirectToRoute('browse_banners');
    }
}