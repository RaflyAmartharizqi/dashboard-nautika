<?php

namespace App\Filament\Resources\LearningMaterials\Pages;

use App\Filament\Resources\LearningMaterials\LearningMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLearningMaterial extends CreateRecord
{
    protected static string $resource = LearningMaterialResource::class;

    protected array $contents = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->contents = $data['contents'] ?? [];
        unset($data['contents']);

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->contents as $index => $item) {

            $contentValue = $item['type'] === 'image'
                ? ($item['image_content'] ?? null)
                : ($item['text_content'] ?? null);

            $this->record->contents()->create([
                'type' => $item['type'],
                'content' => $contentValue,
                'order' => $index
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
