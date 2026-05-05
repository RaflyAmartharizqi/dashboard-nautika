<?php

namespace App\Filament\Resources\LearningMaterials\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;

class LearningMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('title')
                    ->required(),

                TextInput::make('author'),

                DatePicker::make('published_at')
                    ->required(),

                // 🔥 INI YANG KAMU BUTUH
                Repeater::make('contents')
                    ->schema([
                        Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'image' => 'Image',
                            ])
                            ->required()
                            ->reactive(),
                        RichEditor::make('text_content')
                            ->label('Isi Text')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('learning_materials')
                            ->visible(fn ($get) => $get('type') === 'text'),
                        FileUpload::make('image_content')
                            ->label('Upload Gambar')
                            ->image()
                            ->disk('public') // 🔥 WAJIB
                            ->directory('learning_materials')
                            ->visibility('public') // 🔥 WAJIB
                            ->imagePreviewHeight('150')
                            ->visible(fn ($get) => $get('type') === 'image'),
                    ])
                    ->orderable()
                    ->createItemButtonLabel('Tambah Konten')

            ]);
    }
}