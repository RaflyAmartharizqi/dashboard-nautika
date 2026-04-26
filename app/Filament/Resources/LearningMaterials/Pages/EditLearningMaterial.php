<?php

namespace App\Filament\Resources\LearningMaterials\Pages;

use App\Filament\Resources\LearningMaterials\LearningMaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLearningMaterial extends EditRecord
{
    protected static string $resource = LearningMaterialResource::class;

    protected array $contents = []; // 🔥 WAJIB

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $contents = $this->record->contents()
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->type,
                    'text_content' => $item->type === 'text' ? $item->content : null,
                    'image_content' => $item->type === 'image' ? $item->content : null,
                ];
            })
            ->toArray();

        $data['contents'] = $contents;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->contents = $data['contents'] ?? [];
        unset($data['contents']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->contents()->delete();

        foreach ($this->contents as $index => $item) {
            $contentValue = $item['type'] === 'image'
                ? ($item['image_content'] ?? null)
                : ($item['text_content'] ?? null);

            $this->record->contents()->create([
                'type' => $item['type'],
                'content' => $contentValue,
                'order' => $index,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}