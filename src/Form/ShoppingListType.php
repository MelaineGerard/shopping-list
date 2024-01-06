<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Item;
use App\Entity\ShoppingList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingListType extends AbstractType
{
    #[\Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la liste de course',
                'required' => true,
            ])
            ->add('items', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name',
                'multiple' => true,
                'autocomplete' => true,
                'label' => 'Articles',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ])
        ;
    }

    #[\Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShoppingList::class,
        ]);
    }
}
