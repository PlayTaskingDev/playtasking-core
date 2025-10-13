<?php

declare(strict_types=1);

namespace App\MoonShine\Pages\Tenant;

use MoonShine\Pages\Crud\DetailPage;
use MoonShine\Components\MoonShineComponent;
use MoonShine\Fields\Field;
use MoonShine\Fields\Date;
use MoonShine\Fields\Text;
use Throwable;

class TenantDetailPage extends DetailPage
{
    /**
     * @return list<MoonShineComponent|Field>
     */
    public function fields(): array
    {
        return [
            Text::make('Id','id')->unescape()->sortable(),
            Date::make('Created at','created_at')->sortable(),
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function topLayer(): array
    {
        return [
            ...parent::topLayer()
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function mainLayer(): array
    {
        return [
            ...parent::mainLayer()
        ];
    }

    /**
     * @return list<MoonShineComponent>
     * @throws Throwable
     */
    protected function bottomLayer(): array
    {
        return [
            ...parent::bottomLayer()
        ];
    }
}
