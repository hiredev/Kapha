<?php

namespace App\Form;

use App\Entity\Maestro;
use App\Entity\Categoria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class MaestroType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categoriaRepositorio = $this->entityManager->getRepository(Categoria::class);

        $builder
            ->add('nombre', TextType::class)
            ->add('apellido', TextType::class)
            ->add('categoria', AssociationField::class)            
            ->add('email', EmailType::class, ['mapped' => false])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'La Contraseña no coincide.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Contraseña'],
                'second_options' => ['label' => 'Confirmar Contraseña'],
                'mapped' => false                
            ])
            ->add('categoria', ChoiceType::class, [
                'choices' => $categoriaRepositorio->findAll(),
                'choice_label' => 'titulo'])    
            ->add('registrar', SubmitType::class, ['label' => 'Registrar Maestro'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Maestro::class,
        ]);
    }
}
