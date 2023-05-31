<?php

namespace App\Http\Resources;

use App\Helpers\DataHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomResource extends JsonResource
{
	protected $model = null;
	protected $apiUseForApp = false;
	protected $expandFields = ['created_at', 'updated_at', 'id'];
	protected $exceptFields = [];
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request
	 * @return array
	 */

	public function toArray($request)
	{
		return DataHelper::getDataFromModel($this, $this->model, $this->apiUseForApp, $this->expandFields, $this->exceptFields);
	}
}
