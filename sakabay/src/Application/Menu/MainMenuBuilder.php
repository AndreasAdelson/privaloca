<?php

namespace App\Application\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuBuilder
{
    private $factory;
    private $authorizationChecker;

    private $menuStructure = [
        //Category
        "menu_admin_actions" => [
            "menu_role_actions" => [
                "menu_all_role" => [
                    "route" => "role_index",
                    "roles" => ["ROLE_ADMIN"]
                ],
                "menu_all_group" => [
                    "route" => "group_index",
                    "roles" => ["ROLE_RGROUP"]
                ],
                "menu_all_user" => [
                    "route" => "user_index",
                    "roles" => ["ROLE_ADMIN"]
                ],
                "menu_all_function" => [
                    "route" => "fonction_index",
                    "roles" => ["ROLE_RFONCTION"]
                ]
            ],
        ],
        "menu_company_actions" => [
            "menu_company_page" => [
                "route" => "example_new",
                "roles" => ["ROLE_RUTILISATEUR"]
            ],
        ]
    ];

    /**
     * Add any other dependency you need...
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $this->fillCategoryMenu($menu);

        return $menu;
    }

    private function fillCategoryMenu($menu)
    {

        foreach ($this->menuStructure as $categoryName => $categoryContent) {
            $menu->addChild($categoryName);
            //This variable is here show/hide the category if any of his childrens have to been printed.
            $printCategory = false;
            foreach ($categoryContent as $subCategoryName => $subCategoryContent) {

                $fillContentResultArray = $this->fillContentMenu(
                    $menu,
                    $printCategory,
                    $categoryName,
                    $subCategoryContent,
                    $subCategoryName
                );
                $menu = $fillContentResultArray[0];
                $printCategory = $fillContentResultArray[1];
            }

            if (!$printCategory) {
                $menu->removeChild($categoryName);
            }
        }
    }


    private function fillContentMenu($menu, $printCategory, $categoryName, $subCategoryContent, $subCategoryName)
    {

        //If the subCategory is a Link
        if (in_array("route", array_keys($subCategoryContent))) {
            if ($this->authorizationChecker->isGranted($subCategoryContent["roles"])) {
                $printCategory = true;
                $menu[$categoryName]->addChild(
                    $subCategoryName,
                    ["route" => $subCategoryContent["route"]]
                );
            }
        } else {
            $menu[$categoryName]->addChild($subCategoryName);
            $printSubCategory = false;
            foreach ($subCategoryContent as $link_name => $link_content) {
                if ($this->authorizationChecker->isGranted($link_content["roles"])) {
                    $printSubCategory = true;
                    $printCategory = true;
                    $menu[$categoryName][$subCategoryName]->addChild(
                        $link_name,
                        ["route" => $link_content["route"]]
                    );
                }
            }
            if (!$printSubCategory) {
                $menu[$categoryName]->removeChild($subCategoryName);
            }
        }

        return [$menu, $printCategory];
    }
}
