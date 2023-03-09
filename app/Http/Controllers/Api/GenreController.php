<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\GenreResource;
use Core\Application\UseCases\Category\Create\CreateCategoryUseCase;
use Core\Application\UseCases\Category\Delete\DeleteCategoryUseCase;
use Core\Application\UseCases\Category\FindById\FindCategoryByIdUseCase;
use Core\Application\UseCases\Genre\Create\CreateGenreUseCase;
use Core\Application\UseCases\Genre\Delete\DeleteGenreUseCase;
use Core\Application\UseCases\Genre\FindById\FindGenreByIdUseCase;
use Core\Application\UseCases\Genre\ListPaginated\Input;
use Core\Application\UseCases\Category\Update\UpdatedCategoryUseCase;
use Core\Application\UseCases\Genre\ListPaginated\ListPaginatedGenreUseCase;
use Core\Application\UseCases\Genre\Update\UpdateGenreUseCase;
use Core\Domain\Entities\CategoryStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    public function index(Request $request, ListPaginatedGenreUseCase $useCase): AnonymousResourceCollection
    {
        $output = $useCase->execute(
            input: new Input(
                filter: $request->get('filter', ''),
                order: $request->get('order','DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15)
            )
        );

        return GenreResource::collection(collect($output->items))->additional([
            'meta' => [
                'total' => $output->total,
                'current_page' => $output->current_page,
                'last_page' => $output->last_page,
                'first_page' => $output->first_page,
                'per_page' => $output->per_page,
                'to' => $output->to,
                'from' => $output->from,
            ]
        ]);
    }

    public function show(FindGenreByIdUseCase $useCase, $id)
    {
        $category = $useCase->execute(new \Core\Application\UseCases\Genre\FindById\Input($id));
        return (new GenreResource($category))->response();
    }

    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new \Core\Application\UseCases\Genre\Create\Input(
                name: $request->name,
                isActive: (bool) $request->is_active,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new \Core\Application\UseCases\Genre\Update\Input(
                $id,
                $request->get('name'),
                $request->get('categories_ids'),

            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteGenreUseCase $useCase, $id)
    {
         $useCase->execute(
            input: new \Core\Application\UseCases\Genre\Delete\Input(
                id: $id
            )
        );

        return response()->noContent();
    }
}
