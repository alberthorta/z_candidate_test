<?php

namespace App\Infrastructure\Controller;

use App\Application\ItemUseCases;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandlerInterface;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use App\Domain\Entity\Item;

/**
 * Item contoller.
 *
 * @Rest\Route("/api/v1")
 */
class ItemController extends Controller
{
    const RENDER_GROUP_LISTITEMS = 'itemList';
    const RENDER_GROUP_QUERYITEMS = 'entityFields';

    /**
     * Lists Items, receives offset and count parameters.
     * @Rest\Get("/items")
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="Number of items to skip"
     * )
     * @Rest\QueryParam(
     *     name="count",
     *     requirements="\d+",
     *     default="10",
     *     description="Number of items to return"
     * )
     * @param ItemUseCases $itemUseCases
     * @param int $offset
     * @param int $count
     * @return Response
     */
    public function getItemsAction(ItemUseCases $itemUseCases, int $offset, int $count): Response
    {
        $items = $itemUseCases->listItems($offset, $count);

        return $this->renderItems($items, static::RENDER_GROUP_LISTITEMS);
    }

    /**
     * Return a Single Item.
     * @Rest\Get(
     *     "/items/{id}",
     *     requirements={
     *         "id" = "\d+"
     *     }
     * )
     * @param ItemUseCases $itemUseCases
     * @param int $id
     * @return Response
     */
    public function getItemAction(ItemUseCases $itemUseCases, int $id): Response
    {
        $items = $itemUseCases->getItem($id);

        return $this->renderItems($items);
    }

    /**
     * Create a Single Item.
     * @Rest\Post("/items")
     * @Rest\RequestParam(
     *     name="image",
     *     requirements="\S.*",
     *     description="The URL to the item cover-art",
     *     strict=true,
     *     nullable=false
     * )
     * @Rest\RequestParam(
     *     name="title",
     *     requirements="\S.*",
     *     description="The title of the item",
     *     strict=true,
     *     nullable=false
     * )
     * @Rest\RequestParam(
     *     name="author",
     *     requirements="\S.*",
     *     description="The author of the item",
     *     strict=true,
     *     nullable=false
     * )
     * @Rest\RequestParam(
     *     name="price",
     *     requirements="\d*(\.\d\d?)?",
     *     description="The price of the item",
     *     strict=true,
     *     nullable=false
     * )
     * @param ItemUseCases $itemUseCases
     * @param string $image
     * @param string $title
     * @param string $author
     * @param float $price
     * @return Response
     * @throws \App\Domain\Exceptions\InvalidUrlException
     */
    public function postItemAction(ItemUseCases $itemUseCases, string $image, string $title, string $author, float $price): Response
    {
        $item = $itemUseCases->createItem($image, $title, $author, $price);

        return $this->renderItems($item);
    }

    /**
     * Helper used to render the items depending on the renderGroup and generate the JSON output
     *   using JMSSerializer.
     *
     * @param $items
     * @param string $renderGroup
     * @return Response
     */
    private function renderItems($items, $renderGroup = self::RENDER_GROUP_QUERYITEMS)
    {
        $view = $this->view($items, 200);
        $context = $view->getContext();
        $context->setGroups([$renderGroup]);
        $view->setContext($context);

        return $this->handleView($view);
    }
}