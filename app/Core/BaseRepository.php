<?php namespace app\Core;

abstract class BaseRepository
{
	/**
	 * Create new obj Model
	 * @return new Model
	**/
	public function getInstance() {
		return new $this->model;
	}
	
	/**
	 * Get all items of model
	 * @return Illuminate\Support\Collection Model collections
	 */
	public function getAll()
	{
		return $this->model->all();
	}

	/**
	* Get all items vs condition
	*/
	public function getAllWithCondition($condition=[]){
		$data_width_condition = $this->model;
		//Nếu lấy bản ghi có điều kiện
		if (!empty($condition)){

			foreach ($condition as $key => $value) {
				if ($value == ''){
					unset($condition[$key]);
				}
				//Trường hợp nếu param truyền vào dạng ['column' => ['operator'=>'>','value'=> 1]]
				if(is_array($value)){
					$op     = array_get($value, 'operator');
            	$val    = array_get($value, 'value');
            	$column = $key;
            	$data_width_condition = $data_width_condition->where($column, $op, $val); 
				} else {

					//Trường hợp truyền vào simple ['column'=>'value']
					$data_width_condition = $data_width_condition->where($key,$value);
				}
			}

			return $data_width_condition->get();
		}
	}


	/**
	 * Get item of model. If model not exist then it will throw an exception
	 * Nếu không tồn tại ID thông báo not foud
	 * @param  int $id Model ID
	 * @return Model
	 */
	public function getById($id,$condition=[])
	{
		$data_by_id = $this->model;
		
		//Nếu lấy bản ghi có điều kiện
		if (!empty($condition)){

			foreach ($condition as $key => $value) {
				if ($value == ''){
					unset($condition[$key]);
				}
				//Trường hợp nếu param truyền vào dạng ['column' => ['operator'=>'>','value'=> 1]]
				if(is_array($value)){
					$op     = array_get($value, 'operator');
            	$val    = array_get($value, 'value');
            	$column = $key;
            	$data_by_id = $data_by_id->where($column, $op, $val); 
				} else {

					//Trường hợp truyền vào simple ['column'=>'value']
					$data_by_id = $data_by_id->where($key,$value);
				}
			}

			return $data_by_id->findOrFail($id);
		} else {
			return $data_by_id->findOrFail($id);
		}
	}

	/**
	 * Get items with filter & paginate
	 * @param  array  $filter
	 * @param  integer $pageSize
	 * @return Illuminate\Support\Collection Model collections
	 */
	public function getAllWithPaginate($filter = [], $pageSize = 20,$orderby = ['created_at' => 'DESC'],$with="")
	{
      $data_return = $this->model;

      $no_filter_more = false;
		if ( ! empty($filter))
		{
			foreach ($filter as $key => $value)
			{
				if ($value == ''){
					unset($filter[$key]);
				}

         	// Nếu là aray quy định
         	if (is_array($value)) {
            	$op     = array_get($value, 'operator');
            	$val    = array_get($value, 'value');
            	$column = $key;
            	$data_return = $data_return->where($column, $op, $val); 
            	$no_filter_more = true;
         	} else {
         		$data_return = $data_return->where($key,$value);
         	}
			}
		}

		if(!empty($orderby)){
			foreach ($orderby as $key => $value) {
				$data_return = $data_return->orderby($key,$value);
			}
		}

		if($with != ""){
			$data_return->with($with);
		}

		return $data_return->paginate($pageSize);
	}

	/**
	 * Get items with filter & paginate sort delete
	 * @param  array  $filter
	 * @param  integer $pageSize
	 * @return Illuminate\Support\Collection Model collections
	 */

	public function getAllWithOnlyTrashedPaginate($filter = [], $pageSize = 20,$orderby = ['created_at' => 'DESC'],$with="")
	{
      $data_return = $this->model;
      $no_filter_more = false;
		if ( ! empty($filter))
		{
			foreach ($filter as $key => $value)
			{
				if ($value == ''){
					unset($filter[$key]);
				}

         	// Nếu là aray quy định
         	if (is_array($value)) {
            	$op     = array_get($value, 'operator');
            	$val    = array_get($value, 'value');
            	$column = $key;
            	$data_return = $data_return->where($column, $op, $val); 
            	$no_filter_more = true;
         	} else {
         		$data_return = $data_return->where($key,$value);
         	}
			}
		}

		if(!empty($orderby)){
			foreach ($orderby as $key => $value) {
				$data_return = $data_return->orderby($key,$value);
			}
		}

		if($with != ""){
			$data_return->with($with);
		}

		return $data_return->onlyTrashed()->paginate($pageSize);
	}

	/**
	 * Create a new model
	 * @param  array $attributes
	 * @return Bool
	 */
	public function create($attributes)
	{
		return $this->model->create($attributes);
	}

	/**
	 * Update an exitst model
	 * @param  array $attributes
	 * @param  array $condition
	 * @return Bool
	 */
	public function update($attributes, $condition = [])
	{
		if ( ! empty($condition))
		{
			return $this->model->where($condition)->update($attributes);
		}
		return $this->model->update($attributes);
	}

	/**
	 * Select an exitst model
	 * Lấy Danh sách bản ghi có điều kiện đi kèm
	 * @param  array $condition
	 * @return Bool
	 */
	public function getCondition($condition = [], $orderby = [], $limit = 10){

		$data_condition = $this->model;

		//Nếu lấy bản ghi có điều kiện
		if (!empty($condition)){

			foreach ($condition as $key => $value) {
				if ($value == ''){
					unset($condition[$key]);
				}

				//Trường hợp nếu param truyền vào dạng ['column' => ['operator'=>'>','value'=> 1]]
				if(is_array($value)){
					$op     = array_get($value, 'operator');
            	$val    = array_get($value, 'value');
            	$column = $key;
            	$data_condition = $data_condition->where($column, $op, $val); 
				} else {

					//Trường hợp truyền vào simple ['column'=>'value']
					$data_condition = $data_condition->where($key,$value);
				}
			}
			
		}

		if(!empty($orderby)){
			foreach ($orderby as $key => $value) {
				if ($value == ''){
					unset($orderby[$key]);
				}
				$data_condition = $data_condition->orderby($key,$value);
			}
		}

		return $data_condition->take($limit)->get();
	}

	/**
	 * Delete an condition
	 * Xóa có điều kiện
	 * @return Bool
	 */

	public function deleteCondition($condition = []){
		if ( ! empty($condition)){
			return $this->model->where($condition)->delete();
		}
	}
}
?>