<?php

namespace App\Relations;

use App\Events\PivotAttached;
use App\Events\PivotDetached;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToManyBase;

class BelongsToMany extends BelongsToManyBase
{
    /**
     * Attach a model to the parent.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     * @param  bool   $touch
     * @return void
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        parent::attach($id, $attributes, $touch);

        $id = $this->parse($id);

        if($id instanceof Model){
            return event(new PivotAttached($this->getParent(), $id));
        }

        if($id instanceof Collection) {
            $id->each(function($model){
                event(new PivotAttached($this->getParent(), $model));
            });
        }
    }

    /**
     * Detach models from the relationship.
     *
     * @param  mixed  $ids
     * @param  bool  $touch
     * @return int
     */
    public function detach($ids = null, $touch = true)
    {
        parent::detach($ids, $touch);

        $ids = $this->parse($ids);

        if($ids instanceof Model){
            return event(new PivotDetached($this->getParent(), $ids));
        }

        if($ids instanceof Collection) {
            $ids->each(function($model){
                event(new PivotDetached($this->getParent(), $model));
            });
        }
    }

    /**
     * Parses the given id into a Model or Collection
     *
     * @param mixed $id
     * @return mixed
     */
    private function parse($id)
    {
        if(is_string($id)){
            return $this->getRelated()->find($id);
        }

        if(is_array($id)) {
            return $this->getRelated()->findMany($id);
        }

        return $id;
    }
}
