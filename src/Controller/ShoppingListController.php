<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShoppingList;
use App\Entity\User;
use App\Form\ShoppingListType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/shoppinglist', name: 'app_shoppinglist_')]
class ShoppingListController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $shoppingLists = $this->entityManager->getRepository(ShoppingList::class)->findBy([
            'user' => $this->getUser(),
        ]);

        return $this->render('shopping_list/index.html.twig', [
            'shoppingLists' => $shoppingLists,
        ]);
    }

    #[Route('/create', name: 'create')]
    #[Route('/edit/{id}', name: 'edit')]
    public function create(Request $request, ?ShoppingList $shoppingList): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($shoppingList instanceof ShoppingList && $shoppingList->getUser()->getId() !== $user->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier cette liste de course !');

            return $this->redirectToRoute('app_shoppinglist_index');
        }

        if (!$shoppingList instanceof ShoppingList) {
            $shoppingList = new ShoppingList();
        }

        $form = $this->createForm(ShoppingListType::class, $shoppingList);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shoppingList->setUser($user);

            $this->entityManager->persist($shoppingList);
            $this->entityManager->flush();

            $this->addFlash('success', 'Liste de course créé avec succès !');

            return $this->redirectToRoute('app_shoppinglist_index');
        }

        return $this->render('shopping_list/create.html.twig', [
            'form' => $form->createView(),
            'shoppingList' => $shoppingList,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(ShoppingList $shoppingList): Response
    {
        return $this->render('shopping_list/show.html.twig', [
            'shoppingList' => $shoppingList,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(ShoppingList $shoppingList): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($shoppingList->getUser()->getId() !== $user->getId()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer cette liste de course !');

            return $this->redirectToRoute('app_shoppinglist_index');
        }
        $this->entityManager->remove($shoppingList);
        $this->entityManager->flush();

        $this->addFlash('success', 'Liste de course supprimé avec succès !');

        return $this->redirectToRoute('app_shoppinglist_index');
    }
}
