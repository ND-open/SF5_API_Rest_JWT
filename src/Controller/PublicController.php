<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\UserBundle\Model\UserManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use App\Entity\User;
use App\Form\User\UserType;

/**
 * Unicorn controller.
 * @Route("/api")
 */
class PublicController extends AbstractFOSRestController
{
    /**
     * Login User.
     * @Rest\Post("/login")
     */
    public function login(Request $request, UserManagerInterface $userManager, JWTTokenManagerInterface $JWTManager)
    {
        $data = json_decode($request->getContent(), true);
        $user = $userManager->findUserByUsername($data['username']);

        if (!$user) {
            return $this->handleView($this->view(['message' => 'username_not_found'], Response::HTTP_NOT_FOUND));
        }

        if (!(new NativePasswordEncoder(4))->isPasswordValid($user->getPassword(), $data['plainPassword'], 4)) {
            return $this->handleView($this->view(["message" => 'username_password_not_match'], Response::HTTP_UNAUTHORIZED));
        }

        return $this->handleView($this->view(["token" => $JWTManager->create($user)], Response::HTTP_OK));
    }

    /**
     * Create User.
     * @Rest\Post("/register")
     */
    public function register(Request $request, UserManagerInterface $userManager, JWTTokenManagerInterface $JWTManager)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setEnabled(true)
                ->setEmail($user->getUsername() . '@next-decision.com')
                ->setRoles(['ROLE_USER']);
            $userManager->updateUser($user, true);

            return $this->handleView($this->view(["token" => $JWTManager->create($user)], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }
}
