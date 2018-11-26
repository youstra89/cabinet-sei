<?php
// src/Controller/LuckyController.php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class MainController extends AbstractController
{
  // /**
  //  * Matches /blog exactly
  //  *
  //  * @Route("/blog", name="blog_list")
  //  */
    public function indexAction()
    {
        $number = random_int(0, 100);

        // $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        // if(!empty($targetPath))
        // {
        //   $this->redirectToRoute($targetPath);
        // }

        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );
        return $this->render('AppBundle:Home:index.html.twig', [
            'number' => $number,
        ]);
    }
}
