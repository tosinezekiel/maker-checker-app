<?php

namespace App\Http\Controllers\Api;

use App\Models\Document;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApprovalsController extends Controller
{
    use ApiResponder;
    
    private ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function approve(Document $document) : JsonResponse
    {
        $profile = $this->profileService
            ->setData($document->toArray())
            ->save(); 

        return $this->success(
            $profile, 
            'This request has been approved successfully.'
        ); 

    }

    public function decline(Document $document) : JsonResponse
    {
        $document->delete();

        return $this->success(
            collect([]), 
            'This request has been declined successfully.'
        );  
    }
}
