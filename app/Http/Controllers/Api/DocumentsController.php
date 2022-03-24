<?php

namespace App\Http\Controllers\Api;

use App\Constants\Type;
use App\Models\Profile;
use App\Models\Document;
use App\Constants\Status;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRequest;
use App\Services\DocumentService;
use App\Http\Resources\DocumentResource;

class DocumentsController extends Controller
{
    use ApiResponder;

    private DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function index() : JsonResponse
    {
        $pendingDocuments = Document::where('status', Status::PENDING)->get()->toArray();
        return $this->success(collect($pendingDocuments)); 
    }

    public function show(Document $document) : JsonResponse
    {
        return $this->success(collect(new DocumentResource($document))); 
    }

    public function create(CreateRequest $request)
    {
        $data = $this->documentService
            ->setData($request->only(['first_name', 'last_name', 'email']), Type::CREATE)
            ->buildData()
            ->save();

        return $this->success(
            collect(new DocumentResource($data)), 
            'Your request to create this record has been received, Kindly wait as we review this request shortly.',
        201);  
    }

    public function update(CreateRequest $request, Profile $profile) : JsonResponse
    {
        $data = $this->documentService
            ->setData($request->only(['first_name', 'last_name', 'email']), Type::UPDATE)
            ->buildData($profile->id)
            ->save();

        return $this->success(
            collect(new DocumentResource($data)), 
            'Your request to update this record has been received, Kindly wait as we review this request shortly.'
        );  
    }

    public function delete(Profile $profile) : JsonResponse
    {
        $data = $this->documentService
        ->setData([], Type::DELETE)
        ->buildData($profile->id)
        ->save();

        return $this->success(
            collect(new DocumentResource($data)), 
            'Your request to delete this record has been received, Kindly wait as we review this request shortly.'
        );  
    }

}
