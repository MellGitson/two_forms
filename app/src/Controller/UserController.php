<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user')]
#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/', name: '_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: '_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        FileUploadService $fileUploadService
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle profile picture upload
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                $filename = $fileUploadService->uploadFile($profilePictureFile, 'profiles');
                $user->setProfilePicture($filename);
            }

            // Hash the password
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            // Set default role if not set
            if (empty($user->getRoles())) {
                $user->setRoles(['ROLE_USER']);
            }

            // Set creation date
            $user->setCreatedAt(new \DateTimeImmutable());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès!');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: '_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: '_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        FileUploadService $fileUploadService
    ): Response {
        $form = $this->createForm(UserType::class, $user, ['edit_mode' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle profile picture upload
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile) {
                // Delete old profile picture if exists
                if ($user->getProfilePicture()) {
                    $fileUploadService->deleteFile($user->getProfilePicture(), 'profiles');
                }
                
                $filename = $fileUploadService->uploadFile($profilePictureFile, 'profiles');
                $user->setProfilePicture($filename);
            }

            // Hash the password if provided
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès!');

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: '_delete', methods: ['DELETE'])]
    public function delete(Request $request, User $user, EntityManagerInterface $em, FileUploadService $fileUploadService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // Prevent deleting the current user
            if ($user->getId() === $this->getUser()->getId()) {
                $this->addFlash('error', 'Vous ne pouvez pas supprimer votre propre compte!');
            } else {
                // Delete profile picture if exists
                if ($user->getProfilePicture()) {
                    $fileUploadService->deleteFile($user->getProfilePicture(), 'profiles');
                }
                
                $em->remove($user);
                $em->flush();
                $this->addFlash('success', 'Utilisateur supprimé avec succès!');
            }
        }

        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/{id}/promote-moderator', name: '_promote_moderator', methods: ['POST'])]
    public function promoteModerator(Request $request, User $user, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('promote'.$user->getId(), $request->request->get('_token'))) {
            $roles = $user->getRoles();
            if (!in_array('ROLE_MODERATOR', $roles)) {
                $roles[] = 'ROLE_MODERATOR';
                $user->setRoles($roles);
                $em->flush();
                $this->addFlash('success', 'Utilisateur promu modérateur!');
            } else {
                $this->addFlash('info', 'Cet utilisateur est déjà modérateur!');
            }
        }

        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }

    #[Route('/{id}/demote-moderator', name: '_demote_moderator', methods: ['POST'])]
    public function demoteModerator(Request $request, User $user, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('demote'.$user->getId(), $request->request->get('_token'))) {
            $roles = $user->getRoles();
            $key = array_search('ROLE_MODERATOR', $roles);
            if ($key !== false) {
                unset($roles[$key]);
                $user->setRoles(array_values($roles));
                $em->flush();
                $this->addFlash('success', 'Utilisateur retiré du rôle modérateur!');
            }
        }

        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }
}
