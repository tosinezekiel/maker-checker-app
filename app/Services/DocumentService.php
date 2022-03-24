<?php
namespace App\Services;

use App\Models\Document;
use App\Constants\Status;
use Illuminate\Support\Str;

class DocumentService {
    private array $data = [];
    private ?string $type;

    public function setData(array $data = [], ?string $type = null) : self
    {
        $this->data = $data;
        $this->type = $type;
        
        return $this;
    }

    private function setDataId(int $id) : void
    {
        $this->data['id'] = $id;
    }

    public function buildData(?int $id = null) : self
    {
        if($id){
            $this->setDataId($id); 
        }
        $this->data['data'] = json_encode($this->data);

        return $this;
    }

    public function save() : Document
    {
        $document = auth()->user()->documents()->create([
            'type' => $this->type,
            'data' => $this->data['data'],
            'uuid' => Str::uuid(),
            'status' => Status::PENDING
        ]);

        return $document;
    }

    public function update() : void
    {
        $document = Document::where('id', $this->data['id'])->first();
        $document->update([
            'approver_id' => auth()->user()->id,
            'status' => Status::APPROVED
        ]);
    }

    public function extractData() : string
    {
        return json_decode($this->data['data']);
    }


}