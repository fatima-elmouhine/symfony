<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id')
                ->onlyOnIndex(),
            EmailField::new('email'),
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class)
                ->hideOnIndex(),
            ArrayField::new('roles')
            ->setHelp('* Roles disponibles:  ROLE_ADMIN, ROLE_USER <br/> ( L\'utilisateur peut avoir plusieurs roles à la fois )'),
        ];
    }
}
