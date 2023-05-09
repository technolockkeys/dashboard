<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use HasTranslations;
    use LogsActivity;

    public $translatable = ['name', 'description', 'meta_title', 'meta_description'];

    public $fillable = ['slug', 'parent', 'type', 'name', 'description', 'meta_title', 'meta_description'];

    function level_select()
    {
        $view = '';
        if (!empty($this->parent_id)) {
            $is_child = true;
            $parent_id = $this->parent_id;
            while ($is_child) {
                $category = Category::find($parent_id);
                $parent_id = $category->parent_id;
                $view .= '-';
                if (empty($parent_id)) {
                    break;
                }
            }
        }
        return $view;
    }

    static public function select2($selected = null, $id = 0, $level = 0, $invisible_ids = [])
    {
        $view = "";
        $view_root = '';
        $options = Category::query()->where(function ($q) use ($id) {
//            $q->where('parent_id', $id);
            if ($id != 0) {
                $q->where('parent_id', $id);
            } else {
                $q->whereNull('parent_id');
                $q->orWhere('parent_id', $id);
            }
        })->where('status', 1)->whereNotIn('id', $invisible_ids)->get();

        for ($i = 0; $i < $level; $i++) {
            $view_root .= '-';
        }

        foreach ($options as $option) {
            $select = '';
            if ((is_array($selected) && in_array($option->id, $selected)) || (!is_array($selected) && $selected == $option->id)) {
                $select = 'selected';
            }
            $view .= '<option ' . $select . ' value="' . $option->id . '">' . $view_root . ' ' . $option->name . '</option>';
            $view .= Category::select2($selected, $option->id, $level + 1);
        }
        return $view;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function get_children()
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[] = [
                'name' => $child->name,
                'slug' => $child->slug,
                'image' => media_file($child->icon),
            ];
        }
        return $children;
    }    public function get_children_with_transaltions()
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[] = [
                'name' => $child->getTranslations('name'),
                'slug' => $child->slug,
                'image' => get_multisized_image($child->icon)['s']['url'],
            ];
        }
        return $children;
    }

    public function shop_get_children()
    {
        $children = [];
        foreach ($this->children as $child){
            $children[] = [
                'name' => $child->name,
                'slug' => $child->slug,
                'id' => $child->id,
                'description' => $child->description,
                'icon' => media_file($child->icon),
                'banner' => media_file($child->banner),
                'children' => $child->shop_get_children()
            ];
        }
        return $children;

    }

    public function get_parents()
    {
        $parent = Category::find($this->parent_id);

        if ($parent == null) {
            return [['slug' => $this->slug, 'name' => $this->name]];
        } else {
            $array = $parent->get_parents();
            $array[] = ['slug' => $this->slug, 'name' => $this->name];
            return $array;
        }
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function api_children_ids(): array
    {
        $children =array($this->id);
        foreach ($this->children as $child) {
            $children = array_merge($children, $child->api_children_ids());
        }
        return $children;
    }
}
