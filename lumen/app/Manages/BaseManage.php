<?php

namespace App\Manages;

use Illuminate\Support\Facades\DB;

/**
 * 数据库 基类
 * User: Administrator
 * Date: 2016/7/12
 * Time: 19:23
 */
class BaseManage
{
	/**
	 * 获取单条数据
	 * @param $table
	 * @param $id
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGet($table, $id, $lockForUpdate = false)
	{
		if ($lockForUpdate) {
			return DB::table($table)->where('id', intval($id))->lockForUpdate()->first();
		} else {
			return DB::table($table)->where('id', intval($id))->first();
		}
	}

	/**
	 * 获取单条数据（根据查询条件）
	 * @param $table
	 * @param $field
	 * @param $param
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetWith($table, $field, $param, $lockForUpdate = false)
	{
		if ($lockForUpdate) {
			return DB::table($table)->where($field, $param)->lockForUpdate()->first();
		} else {
			return DB::table($table)->where($field, $param)->first();
		}
	}

	/**
	 * 获取单条数据（根据多项查询条件）
	 * @param $table
	 * @param array $wheres
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetWithWheres($table, $wheres = array(), $lockForUpdate = false)
	{
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		if ($lockForUpdate) {
			return $model->lockForUpdate()->first();
		} else {
			return $model->first();
		}
	}

	/**
	 * 获取列表
	 * @param $table
	 * @param array $wheres
	 * @param array $orders
	 * @param int $pageSize
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetList($table, $wheres = array(), $orders = array(), $pageSize = 0, $lockForUpdate = false)
	{
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		$model = self::__orders($model, $orders);
		if ($pageSize > 0) {
			$model->paginate($pageSize);
		}
		if ($lockForUpdate) {
			return $model->lockForUpdate()->get();
		} else {
			return $model->get();
		}
	}

	/**
	 * 获取数量
	 * @param $table
	 * @param array $wheres
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function dbGetCount($table, $wheres = array(), $lockForUpdate = false)
	{
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		if ($lockForUpdate) {
			return $model->lockForUpdate()->count();
		} else {
			return $model->count();
		}
	}

	/**
	 * 添加
	 * @param $table
	 * @param array $params
	 * @return mixed
	 */
	static function dbInsert($table, $params = array())
	{
		$params['create_at'] = time();
		return DB::table($table)->insertGetId($params);
	}

	/**
	 * 编辑
	 * @param $table
	 * @param $id
	 * @param array $params
	 * @return mixed
	 */
	static function dbUpdate($table, $id, $params = array())
	{
		$params['update_at'] = time();
		return DB::table($table)->where('id', $id)->update($params);
	}

	/**
	 * 编辑（根据多项条件）
	 * @param $table
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function dbUpdateWithWheres($table, $wheres = array(), $params = array())
	{
		$params['update_at'] = time();
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		return $model->update($params);
	}

	/**
	 * 编辑（自增）
	 * @param $table
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function dbIncrement($table, $id, $field, $param)
	{
		$params['update_at'] = time();
		return DB::table($table)->where('id', $id)->increment($field, $param);
	}

	/**
	 * 编辑（根据多项条件自增）
	 * @param $table
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function dbIncrementWithWheres($table, $field, $param, $wheres = array(), $params = array())
	{
		$params['update_at'] = time();
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		return $model->increment($field, $param, $params);
	}

	/**
	 * 编辑（自减）
	 * @param $table
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function dbDecrement($table, $id, $field, $param)
	{
		$params['update_at'] = time();
		return DB::table($table)->where('id', $id)->decrement($field, $param, $params);
	}

	/**
	 * 编辑（根据多项条件自减）
	 * @param $table
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function dbDecrementWithWheres($table, $field, $param, $wheres = array(), $params = array())
	{
		$params['update_at'] = time();
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		return $model->decrement($field, $param, $params);
	}

	/**
	 * 删除
	 * @param $table
	 * @param $id
	 * @return mixed
	 */
	static function dbDelete($table, $id)
	{
		return DB::table($table)->where('id', $id)->delete();
	}

	/**
	 * 删除（根据多项条件）
	 * @param $table
	 * @param array $wheres
	 * @return mixed
	 */
	static function dbDeleteWithWheres($table, $wheres = array())
	{
		$model = DB::table($table);
		$model = self::__wheres($model, $wheres);
		return $model->delete();
	}

	/**
	 * 排序处理
	 * @param $model
	 * @param array $orders
	 * @return mixed
	 */
	static function __orders($model, $orders = array())
	{
		if (empty($orders)) {
			$orders = array('id' => 'DESC');
		}
		foreach ($orders as $key => $item) {
			$model->orderBy($key, $item);
		}
		return $model;
	}

	/**
	 * 条件处理
	 * @param $model
	 * @param array $wheres
	 * @return mixed
	 */
	static function __wheres($model, $wheres = array())
	{
		if (empty($wheres)) {
			$wheres = array();
		}
		foreach ($wheres as $key => $item) {
			if (is_array($item) && count($item) === 2) {
				$model->where($key, $item[0], $item[1]);
			} else if (is_array($item) && count($item) === 3) {
				$model->where($item[0], $item[1], $item[2]);
			} else {
				$model->where($key, $item);
			}
		}
		return $model;
	}

	/**
	 * 排序DESC
	 * @param int $nextId
	 * @param int $pageSize
	 * @param bool $add
	 * @return string
	 */
	static function dbOrderSqlByDesc($nextId = 0, $pageSize = 0, $add = true)
	{
		if ($pageSize === 0) {
			return ($add ? ' AND' : '') . ' 1=1 ORDER BY id DESC';
		}
		if ($nextId) {
			return ($add ? ' AND' : '') . ' id<' . intval($nextId) . ' ORDER BY id DESC LIMIT ' . intval($pageSize);
		} else {
			return ($add ? ' AND' : '') . ' 1=1 ORDER BY id DESC LIMIT ' . intval($pageSize);
		}
	}
}