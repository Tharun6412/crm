<?php

namespace App\Services;

use App\Models\Admin\Module;
use Illuminate\Support\Facades\Cache;

class MenuBuilder
{
    /**
     * Build and cache the menu structure.
     *
     * @param  array  $leafIds
     * @param  string|null  $cacheKey
     * @return \Illuminate\Support\Collection
     */
    public static function build(array $leafIds, ?string $cacheKey = null)
    {
        // Cache::forget('menu_tree_0781c078a89c1e3a8fb479a138313698');
        $cacheKey = $cacheKey ?? 'menu_tree_' . md5(json_encode($leafIds));

        return Cache::remember($cacheKey, now()->addHours(2), function () use ($leafIds) {
            $modules = Module::where('status', 1)->get();

            $treeIds = self::getParentTreeIds($modules, $leafIds);
            $filtered = $modules->whereIn('id', $treeIds);

            return self::buildTree($filtered);
        });
    }

    /**
     * Get all parent and child IDs from leaf nodes.
     */
    protected static function getParentTreeIds($modules, $leafIds)
    {
        $map = $modules->keyBy('id');
        $allIds = collect($leafIds);

        $toCheck = collect($leafIds);
        while ($toCheck->isNotEmpty()) {
            $next = $toCheck->map(fn($id) => $map[$id]->parent_id ?? null)
                ->filter()
                ->unique()
                ->diff($allIds);
            $allIds = $allIds->merge($next);
            $toCheck = $next;
        }

        return $allIds->unique();
    }

    /**
     * Build a nested tree structure in memory.
     */
    protected static function buildTree($modules, $parentId = null)
    {
        return $modules->where('parent_id', $parentId)
            ->map(function ($module) use ($modules) {
                $module->children = self::buildTree($modules, $module->id);
                return $module;
            })
            ->values();
    }
}
