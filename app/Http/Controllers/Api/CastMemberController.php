<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use App\Http\Resources\CastMemberResource;
use Core\Application\UseCases\CastMember\Create\CreateCastMemberUseCase;
use Core\Application\UseCases\CastMember\Delete\DeleteCastMemberUseCase;
use Core\Application\UseCases\CastMember\ListPaginated\Input;
use Core\Application\UseCases\CastMember\ListPaginated\ListPaginatedCastMembersUseCase;
use Core\Application\UseCases\CastMember\FindById\FindByIdCastMemberUseCase;
use Core\Application\UseCases\CastMember\Update\UpdateCastMemberUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    public function index(Request $request, ListPaginatedCastMembersUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new Input(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPerPage: (int) $request->get('total_page', 15),
            )
        );

        return CastMemberResource::collection(collect($response->items))
                                    ->additional([
                                        'meta' => [
                                            'total' => $response->total,
                                            'current_page' => $response->current_page,
                                            'last_page' => $response->last_page,
                                            'first_page' => $response->first_page,
                                            'per_page' => $response->per_page,
                                            'to' => $response->to,
                                            'from' => $response->from,
                                        ],
                                    ]);
    }

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new \Core\Application\UseCases\CastMember\Create\Input(
                name: $request->name,
                type: (int) $request->type,
            )
        );

        return (new CastMemberResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FindByIdCastMemberUseCase $useCase, $id)
    {
        $castMember = $useCase->execute(new \Core\Application\UseCases\CastMember\FindById\Input($id));

        return (new CastMemberResource($castMember))->response();
    }

    public function update(UpdateCastMemberRequest $request, UpdateCastMemberUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new \Core\Application\UseCases\CastMember\Update\Input(
                id: $id,
                name: $request->get('name', '')
            )
        );

        return (new CastMemberResource($response))
                    ->response();
    }

    public function destroy(DeleteCastMemberUseCase $useCase, $id)
    {
        $useCase->execute(new \Core\Application\UseCases\CastMember\Delete\Input($id));

        return response()->noContent();
    }
}
