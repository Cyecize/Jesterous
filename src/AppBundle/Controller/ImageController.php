<?php
/**
 * Created by PhpStorm.
 * User: cyecize
 * Date: 9/18/2018
 * Time: 9:16 PM
 */

namespace AppBundle\Controller;

use AppBundle\BindingModel\ImageBindingModel;
use AppBundle\Contracts\IImageDbManager;
use AppBundle\Contracts\IUserDbManager;
use AppBundle\Exception\RestFriendlyExceptionImpl;
use AppBundle\Form\ImageType;
use AppBundle\Service\LocalLanguage;
use AppBundle\Util\Pageable;
use AppBundle\ViewModel\BrowseImagesBindingModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends BaseController
{
    private const IMAGE_NOT_FOUND_MSG = "Image not found!";
    private const IMAGE_NOT_OWNED_MSG = "This image is not yours!";

    /**
     * @var IImageDbManager
     */
    private $imageService;

    /**
     * @var IUserDbManager
     */
    private $userService;

    public function __construct(LocalLanguage $language, IUserDbManager $userDbManager, IImageDbManager $imageDb)
    {
        parent::__construct($language);
        $this->imageService = $imageDb;
        $this->userService = $userDbManager;
    }

    /**
     * @Route("/authors/images/upload", name="upload_image")
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function uploadImageAction(Request $request)
    {

        $bindingModel = new ImageBindingModel();
        $form = $this->createForm(ImageType::class, $bindingModel);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $this->validateToken($request);
            if (count($this->validate($bindingModel)) > 0)
                goto  escape;
            $this->imageService->uploadImage($bindingModel, $this->userService->findOneById($this->getUserId()));
            return $this->redirectToRoute('author_panel', ['info' => 'Image uploaded!']);
        }

        escape:
        return $this->render('author/images/upload-image.html.twig', [
            'form1' => $form->createView()
        ]);
    }

    /**
     * @Route("/authors/images/browse", name="browse_my_images")
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myImagesAction(Request $request)
    {
        return $this->render('author/images/browse-images.html.twig', [
            'viewModel' => new BrowseImagesBindingModel($this->imageService->findByUser($this->getUser(), new Pageable($request))),
        ]);
    }

    /**
     * @Route("/authors/images/browse-rest", name="browse_my_images_rest")
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myImagesRest(Request $request)
    {
        return $this->render('queries/load-more-images-query.html.twig', [
            'viewModel' => new BrowseImagesBindingModel($this->imageService->findByUser($this->getUser(), new Pageable($request))),
        ]);
    }

    /**
     * @Route("/authors/images/remove/{imgId}", name="remove_image", defaults={"imgId"=null})
     * @Security("has_role('ROLE_AUTHOR')")
     * @param Request $request
     * @param $imgId
     * @return JsonResponse
     * @throws \AppBundle\Exception\RestFriendlyExceptionImpl
     */
    public function removeImageAction(Request $request, $imgId)
    {
        $this->validateToken($request);
        if ($imgId == null)
            throw new RestFriendlyExceptionImpl(self::IMAGE_NOT_FOUND_MSG);
        $image = $this->imageService->findOneById($imgId);
        if ($image == null)
            throw new RestFriendlyExceptionImpl(self::IMAGE_NOT_FOUND_MSG);
        if ($image->getOwner()->getId() != $this->getUserId())
            throw new RestFriendlyExceptionImpl(self::IMAGE_NOT_OWNED_MSG);
        $this->imageService->removeImage($image);
        return new JsonResponse(['message' => 'Image removed!']);
    }
}