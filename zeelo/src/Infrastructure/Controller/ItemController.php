<?php

namespace App\Infrastructure\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

/**
 * Item contoller.
 *
 * @Rest\Route("/api/v1")
 */
class ItemController extends Controller
{
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
     * @param int $offset
     * @param int $count
     * @return Response
     */
    public function getItemsAction(int $offset, int $count): Response
    {
        $data = ["success" => false, "offset" => $offset, "count" => $count];

        return $this->handleView(
            $this->view($data, 200)
        );
    }

    /**
     * Return a Single Item.
     * @Rest\Get(
     *     "/item/{id}",
     *     requirements={
     *         "id" = "\d+"
     *     }
     * )
     * @param int $id
     * @return Response
     */
    public function getItemAction(int $id): Response
    {
        $data = [
            "id" => $id,
            "image" => "http://www.example.com/images/img42.png",
            "title" => "Steppenwolf",
            "author" => "Herman Hesse",
            "price" => 5.99,
        ];

        return $this->handleView(
            $this->view($data, 200)
        );
    }

    /**
     * Create a Single Item.
     * @Rest\Post("/item")
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
     */
    public function postItemAction(string $image, string $title, string $author, float $price): Response
    {
        return $this->handleView(
            $this->view([
                "image"  => $image,
                "title"  => $title,
                "author" => $author,
                "price"  => $price
            ], 200)
        );
    }
}