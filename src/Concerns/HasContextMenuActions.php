<?php

namespace Guava\Calendar\Concerns;

use Filament\Actions\Action;
use Guava\Calendar\Enums\Context;
use InvalidArgumentException;

trait HasContextMenuActions
{
    protected array $cachedContextMenuActions = [];

    public function bootedHasContextMenuActions(): void
    {
        $this->cacheContextMenuActions();
    }

    public function hasContextMenu(): bool
    {
        return !empty($this->getCachedContextMenuActions());
    }

    protected function cacheContextMenuActions(): void
    {
        foreach ($this->getDateClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::DateClick);
        }
        foreach ($this->getDateSelectContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::DateSelect);
        }
        foreach ($this->getEventClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::EventClick);
        }
        foreach ($this->getNoEventsClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::NoEventsClick);
        }
        foreach ($this->getFreeEventClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::FreeEventClick);
        }
        foreach ($this->getMyEventClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::MyEventClick);
        }
        foreach ($this->getMyPastEventClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::MyPastEventClick);
        }
        foreach ($this->getClosedClickContextMenuActions() as $action) {
            $this->cacheContextMenuAction($action, Context::ClosedClick);
        }
    }

    public function getClosedClickContextMenuActions(): array
    {
        return [];
    }

    public function getCachedContextMenuActions(): array
    {
        return $this->cachedContextMenuActions;
    }

    public function getDateClickContextMenuActions(): array
    {
        return [];
    }

    public function getDateSelectContextMenuActions(): array
    {
        return [];
    }

    public function getEventClickContextMenuActions(): array
    {
        return [];
    }

    public function getFreeEventClickContextMenuActions(): array
    {
        return [];
    }

    public function getMyEventClickContextMenuActions(): array
    {
        return [];
    }

    public function getMyPastEventClickContextMenuActions(): array
    {
        return [];
    }

    public function getNoEventsClickContextMenuActions(): array
    {
        return [];
    }

    public function getCachedDateClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::DateClick->value, []);
    }

    public function getCachedDateSelectContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::DateSelect->value, []);
    }

    public function getCachedEventClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::EventClick->value, []);
    }

    public function getCachedFreeEventClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::FreeEventClick->value, []);
    }

    public function getCachedMyEventClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::MyEventClick->value, []);
    }

    public function getCachedMyPastEventClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::MyPastEventClick->value, []);
    }


    public function getCachedNoEventsClickContextMenuActions(): array
    {
        return data_get($this->getCachedContextMenuActions(), Context::NoEventsClick->value, []);
    }

    private function cacheContextMenuAction(Action $action, Context $context): void
    {
        $action = $action
            ->grouped()
            ->when(
                $context === Context::EventClick,
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.onEventClick(mountData, '{$action->getName()}')"),
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.mountAction('{$action->getName()}', mountData)")
            )
            ->when(
                $context === Context::MyEventClick,
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.onMyEventClick(mountData, '{$action->getName()}')"),
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.mountAction('{$action->getName()}', mountData)")
            )
            ->when(
                $context === Context::MyPastEventClick,
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.onMyPastEventClick(mountData, '{$action->getName()}')"),
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.mountAction('{$action->getName()}', mountData)")
            )
            ->when(
                $context === Context::FreeEventClick,
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.onFreeEventClick(mountData, '{$action->getName()}')"),
                fn($action) => $action->alpineClickHandler(fn($action) => "\$wire.mountAction('{$action->getName()}', mountData)")
            );

        if (!$action instanceof Action) {
            throw new InvalidArgumentException('Context menu actions must be an instance of ' . Action::class . '.');
        }

        $this->cacheAction($action);
        $cachedActions = data_get($this->cachedContextMenuActions, $context->value, []);
        $this->cachedContextMenuActions[$context->value] = [
            ...$cachedActions,
            $action,
        ];
    }
}
