<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('duration')
                    ->required()
                    ->numeric(),
                Toggle::make('status')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
