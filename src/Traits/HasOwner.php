<?php

namespace Pandorga\Owner\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasOwner
{
    /**
     * Check if model is owned by another model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    public function isOwnedBy(Model $model)
    {
        $ownerModel = config('owner.model');

        return (bool) $ownerModel::where('owner_id', $model->id)->where('owns_id', $this->id)->first();
    }

    /**
     * Return a collection of all the model's owner.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function owners()
    {
        $ownerModel = config('owner.model');

        return $this->returnOwnerModels(
            $ownerModel::where('owns_id', $this->id)->where('owns_model', get_class($this))->get()
        );
    }

    /**
     * Add an owner to a model
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    public function addOwner(Model $model)
    {
        $ownerModel = config('owner.model');
        $checkOwner = $ownerModel::where('owner_id', $this->id)->where('owns_id', $model->id)->get();

        // Check if relationship already exists
        if (count($checkOwner) === 0)  {
            $newModel = new $ownerModel;
            $newModel->owner_id = $model->id;
            $newModel->owner_model = get_class($model);
            $newModel->owns_id = $this->id;
            $newModel->owns_model = get_class($this);

            return $newModel->save();
        }

        return true;
    }

    /**
     * Remove an owner from a model
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return boolean
     */
    public function removeOwner(Model $model)
    {
        $ownerModel = config('owner.model');

        $relationship = $ownerModel::where('owns_id', $this->id)->where('owner_id', $model->id);
        $relationship->delete();

        return true;
    }

    /**
     * Query the owned models.
     *
     * @param  \Illuminate\Support\Collection  $ownerModels
     * @return \Illuminate\Support\Collection
     */
    private function returnOwnerModels(Collection $ownerModels)
    {
        $outputCollection = new Collection;

        foreach ($ownerModels as $model) {
            $ownerModel = $model->owner_model;
            $outputModel = $ownerModel::find($model->owner_id);
            $outputCollection->push($outputModel);
        }

        return $outputCollection;
    }
}
