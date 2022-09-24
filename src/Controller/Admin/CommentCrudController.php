<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Repository\ArticleRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextEditorField::new('content', 'Commentaire')
            ->hideOnIndex(),
            AssociationField::new('id_article', 'Pour quel article ? ')
            ->autocomplete(),
        ];
    }

}
