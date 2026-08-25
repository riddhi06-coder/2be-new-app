<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Auto-records create / update / delete / restore for any model it's attached to,
 * so every admin action across every module lands in the activity log.
 */
class ActivityObserver
{
    public function created(Model $model): void
    {
        $this->log('created', $model);
    }

    public function updated(Model $model): void
    {
        // Ignore no-op saves and updates that only touch timestamps.
        $changes = collect($model->getChanges())->except(['updated_at'])->keys();
        if ($changes->isEmpty()) {
            return;
        }
        $this->log('updated', $model, ['changed' => $changes->values()->all()]);
    }

    public function deleted(Model $model): void
    {
        $this->log('deleted', $model);
    }

    public function restored(Model $model): void
    {
        $this->log('restored', $model);
    }

    private function log(string $event, Model $model, ?array $properties = null): void
    {
        // Skip CLI (migrations, seeders, tinker) so we only capture real user actions.
        if (app()->runningInConsole()) {
            return;
        }

        $module = ActivityLog::moduleFor($model);
        $title  = ActivityLog::titleFor($model);
        $noun   = \Illuminate\Support\Str::singular($module);

        ActivityLog::write(
            $event,
            $module,
            ucfirst($event).' '.$noun.' — '.$title,
            $model,
            $properties
        );
    }
}
