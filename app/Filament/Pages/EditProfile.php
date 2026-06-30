<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Информация')->schema([
                    $this->getNameFormComponent(),
                    $this->getEmailFormComponent(),
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                    $this->getCurrentPasswordFormComponent(),
                ]),
            ]);
    }
}
