<?php
namespace App\Services;

use App\Constants\Type;
use App\Models\Document;
use App\Models\Profile;
use Illuminate\Support\Collection;

class ProfileService {
    private array $data;

    public function setData(array $data = []) : ProfileService
    {
        $this->data = $data;
        return $this;
    }

    public function save() : Collection
    {
        return $this->proccessDocumentData();
    }

    public function proccessDocumentData() : Collection
    {
        $extractedData = $this->extractData();

        if($this->data['type'] == Type::CREATE){
            $extractedData['document_id'] = $this->data['id'];
            $profile = Profile::create($extractedData);
            $this->updateDocument();
            return collect($profile);
        }

        if($this->data['type'] == Type::UPDATE){
            $profile = Profile::where('id', $extractedData['id'])->first();
            unset($extractedData['id']);
            $extractedData['document_id'] = $this->data['id'];
            $profile->update($extractedData);
            $this->updateDocument();
            return collect($profile->refresh());
        }
        
        if($this->data['type'] == Type::DELETE){
            $profile = Profile::where('id', $extractedData['id'])->first()->delete();
            $this->updateDocument();
            return collect([]);
        }
    }

    public function updateDocument() : void
    {
        (new DocumentService)
        ->setData($this->data)
        ->update();
    }

    public function extractData() : array
    {
        return collect(json_decode($this->data['data']))->toArray();
    }


}