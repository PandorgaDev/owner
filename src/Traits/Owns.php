<?php

namespace Pandorga\Owner\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait Owns
{
    /**
     * Own the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function own(Model $model)
    {
        $ownerModel = config('owner.model');
        $checkOwner = $ownerModel::where('owner_id', $this->id)->where('owns_id', $model->id)->get();

        // Check if relationship already exists
        if (count($checkOwner) === 0) {
            $newModel = new $ownerModel;
            $newModel->owner_id = $this->id;
            $newModel->owner_model = get_class($this);
            $newModel->owns_id = $model->id;
            $newModel->owns_model = get_class($model);

            return $newModel->save();
        }

        return true;
    }

    /**
     * Disown the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function disown(Model $model)
    {
        $ownerModel = config('owner.model');

        $relationship = $ownerModel::where('owns_id', $model->id)->where('owner_id', $this->id);
        $relationship->delete();

        return true;
    }

    /**
     * Query which models the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function owns()
    {
        $ownerModel = config('owner.model');
        return $this->returnOwnsModels($ownerModel::where('owner_id', $this->id)->get());
    }

    /**
     * Determine whether the user own the model.
     *
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function ownsModel(Model $model)
    {
        $ownerModel = config('owner.model');

        return (bool) $ownerModel::where('owner_id', $this->id)->where('owns_id', $model->id)->first();
    }

    /**
     * Query which models the user owns of this type.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $modelType
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function ownsModelType($modelType)
    {
        $ownerModel = config('owner.model');

        if (gettype($modelType) === 'object') {
            $modelType = get_class($modelType);
        }

        return $this->returnOwnsModels(
            $ownerModel::where('owner_id', $this->id)->where('owns_model', $modelType)->get()
        );
    }
    /**
     * Query the owned models
     * @method returnOwnsModels
     * @param  Collection   $ownerModels
     * @return Collection
     */
    private function returnOwnsModels(Collection $ownerModels)
    {
        $outputCollection = new Collection;

        foreach ($ownerModels as $model) {
            $ownsModel = $model->owns_model;
            $outputModel = $ownsModel::find($model->owns_id);
            $outputCollection->push($outputModel);
        }

        return $outputCollection;
    }
}
