<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use App\Models\User;
use Filament\Tables;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MorphToSelect;
use App\Filament\Resources\CommentResource\Pages;
use Filament\Forms\Components\MorphToSelect\Type;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CommentResource\RelationManagers;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('user_id')->relationship('user', 'name')->rules('required|exists:users,id')->required()->label('User')->searchable()->preload(),
                    TextInput::make('comment')->rules('required|max:255')->required()->label('Comment')->maxLength(255),
                    MorphToSelect::make('commentable')->types([
                        Type::make(Post::class)->titleAttribute('title'),
                        Type::make(User::class)->titleAttribute('name'),
                    ])->searchable()->preload(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commentable_type')->label('Commentable Type'),
                TextColumn::make('commentable_id')->label('Commentable ID'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
