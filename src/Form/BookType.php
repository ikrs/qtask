<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\Author;
use App\Model\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * @var Author[] $authors
         */
        $authors = $options['authors'];

        $authorChoices = [];

        foreach ($authors as $author) {
            $authorChoices[$author->getFullName()] = $author->getId();
        }

        $builder
            ->add(
                'id',
                HiddenType::class
            )
            ->add(
                'authorId',
                ChoiceType::class,
                [
                    'choices' => $authorChoices,
                    'required' => true,
                    'label' => 'Author',
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'releaseDate',
                DateType::class,
                [
                    'required' => true,
                    'html5' => true,
                ]
            )
            ->add(
                'isbn',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'format',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'numberOfPages',
                NumberType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'submit',
                SubmitType::class
            );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(
                [
                    'authors' => [],
                    'data_class' => Book::class,
                ]
            )
            ->setRequired(
                [
                    'authors',
                ]
            );
    }
}
