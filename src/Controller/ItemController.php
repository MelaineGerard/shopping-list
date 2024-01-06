<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/item', name: 'app_item_')]
#[IsGranted('ROLE_USER')]
class ItemController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FilesystemOperator $defaultStorage,
    ) {}

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $items = $this->entityManager->getRepository(Item::class)->findAll();

        return $this->render('item/index.html.twig', [
            'items' => $items,
        ]);
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/create', name: 'create')]
    #[Route('/edit/{id}', name: 'edit')]
    public function create(Request $request, ?Item $item): Response
    {
        if (!$item instanceof Item) {
            $item = new Item();
        }

        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile[] $images */
            $images = $form->get('images')->getData();

            foreach ($images as $image) {
                $filename = uniqid() . '.' . $image->guessExtension();
                $this->defaultStorage->write($filename, (string) file_get_contents($image->getPathname()));

                $item->addImage($filename);
            }

            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $this->addFlash('success', 'Objet créé avec succès !');

            return $this->redirectToRoute('app_item_index');
        }

        return $this->render('item/create.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Item $item): Response
    {
        $this->entityManager->remove($item);
        $this->entityManager->flush();

        $this->addFlash('success', 'Objet supprimé avec succès !');

        return $this->redirectToRoute('app_item_index');
    }
}
