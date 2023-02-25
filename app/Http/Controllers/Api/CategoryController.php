<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use Core\Application\UseCases\Category\Create\CreateCategoryUseCase;
use Core\Application\UseCases\Category\Delete\DeleteCategoryUseCase;
use Core\Application\UseCases\Category\FindById\FindCategoryByIdUseCase;
use Core\Application\UseCases\Category\ListPaginated\Input;
use Core\Application\UseCases\Category\ListPaginated\ListPaginatedCategoriesUseCase;
use Core\Application\UseCases\Category\Update\UpdatedCategoryUseCase;
use Core\Domain\Entities\CategoryStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListPaginatedCategoriesUseCase $useCase): AnonymousResourceCollection
    {
        $output = $useCase->execute(
            input: new Input(
                filter: $request->get('filter', ''),
                order: $request->get('order','DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('total_page', 15)
            )
        );

        return CategoryResource::collection(collect($output->items))->additional([
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

    public function show(FindCategoryByIdUseCase $useCase, $id)
    {
        $category = $useCase->execute(new \Core\Application\UseCases\Category\FindById\Input($id));
        return (new CategoryResource($category))->response();
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new \Core\Application\UseCases\Category\Create\Input(
                name: $request->name,
                description: $request->description ?? '',
                status: CategoryStatus::tryFrom((bool) $request->status ?? true)
            )
        );

        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateCategoryRequest $request, UpdatedCategoryUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new \Core\Application\UseCases\Category\Update\Input(
                $id,
                $request->get('name'),
                $request->get('description'),

            )
        );

        return (new CategoryResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCategoryUseCase $useCase, $id)
    {
         $useCase->execute(
            input: new \Core\Application\UseCases\Category\Delete\Input(
                id: $id
            )
        );

        return response()->noContent();
    }
}
