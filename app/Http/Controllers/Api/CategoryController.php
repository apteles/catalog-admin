<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Core\Application\UseCases\Category\ListPaginated\Input;
use Core\Application\UseCases\Category\ListPaginated\ListPaginatedCategoriesUseCase;
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

        return CategoryResource::collection($output);
    }
}
