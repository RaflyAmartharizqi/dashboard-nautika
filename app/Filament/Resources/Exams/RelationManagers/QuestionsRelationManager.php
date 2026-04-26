<?php

namespace App\Filament\Resources\Exams\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('question_text')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->default(0),
                Repeater::make('choices')
                    ->relationship()
                    ->schema([
                        TextInput::make('answer_text')
                            ->required()
                            ->label('Jawaban'),
                        FileUpload::make('image')
                            ->image()
                            ->directory('answer_choices')
                            ->imagePreviewHeight('100')
                            ->disk('public')
                            ->nullable(),
                        Toggle::make('is_correct')
                            ->label('Benar'),
                    ])
                    ->minItems(2)
                    ->maxItems(5)
                    ->defaultItems(4)
                    ->columns(2)
                    ->label('Pilihan Jawaban'),
            ]);
            
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                TextColumn::make('question_text')
                    ->limit(50)
                    ->label('Question'),
                TextColumn::make('score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('id')
                    ->label('Choices')
                    ->formatStateUsing(function ($record) {
                        return $record->choices
                            ->values()
                            ->map(function ($choice, $index) {
                                $letter = chr(65 + $index);

                                $text = $choice->answer_text ?? '';

                                $image = $choice->image
                                    ? "<br><img src='/storage/{$choice->image}' width='80'>"
                                    : '';

                                return "<div>
                                    <strong>{$letter}.</strong> {$text}
                                    {$image}
                                </div>";
                            })
                            ->join('');
                    })
                    ->html(),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
