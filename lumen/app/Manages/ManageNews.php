<?php

namespace App\Manages;

use Illuminate\Support\Facades\DB;

/**
 * 新闻
 * User: Administrator
 * Date: 2017/9/2
 * Time: 19:23
 */
class ManageNews extends BaseManage
{
	/**
	 * 表名
	 * @var string
	 */
	static $table = 'db_news';

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 通用

	/**
	 * 获取单条数据
	 * @param $id
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function get($id, $lockForUpdate = false)
	{
		return self::dbGet(self::$table, $id, $lockForUpdate);
	}

	/**
	 * 获取单条数据（根据查询条件）
	 * @param $field
	 * @param $param
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getWith($field, $param, $lockForUpdate = false)
	{
		return self::dbGetWith(self::$table, $field, $param, $lockForUpdate);
	}

	/**
	 * 获取单条数据（根据多项查询条件）
	 * @param array $wheres
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getWithWheres($wheres = array(), $lockForUpdate = false)
	{
		return self::dbGetWithWheres(self::$table, $wheres, $lockForUpdate);
	}

	/**
	 * 获取列表
	 * @param array $wheres
	 * @param array $orders
	 * @param int $pageSize
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getList($wheres = array(), $orders = array(), $pageSize = 0, $lockForUpdate = false)
	{
		return self::dbGetList(self::$table, $wheres, $orders, $pageSize, $lockForUpdate);
	}

	/**
	 * 获取数量
	 * @param array $wheres
	 * @param bool $lockForUpdate
	 * @return mixed
	 */
	static function getCount($wheres = array(), $lockForUpdate = false)
	{
		return self::dbGetCount(self::$table, $wheres, $lockForUpdate);
	}

	/**
	 * 添加
	 * @param array $params
	 * @return mixed
	 */
	static function insert($params = array())
	{
		return self::dbInsert(self::$table, $params);
	}

	/**
	 * 编辑
	 * @param $id
	 * @param array $params
	 * @return mixed
	 */
	static function update($id, $params = array())
	{
		return self::dbUpdate(self::$table, $id, $params);
	}

	/**
	 * 编辑（根据多项条件）
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function updateWithWheres($wheres = array(), $params = array())
	{
		return self::dbUpdateWithWheres(self::$table, $wheres, $params);
	}

	/**
	 * 编辑（自增）
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function increment($id, $field, $param)
	{
		return self::dbIncrement(self::$table, $id, $field, $param);
	}

	/**
	 * 编辑（根据多项条件自增）
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function incrementWithWheres($field, $param, $wheres = array(), $params = array())
	{
		return self::dbIncrementWithWheres(self::$table, $field, $param, $wheres, $params);
	}

	/**
	 * 编辑（自减）
	 * @param $id
	 * @param $field
	 * @param $param
	 * @return mixed
	 */
	static function decrement($id, $field, $param)
	{
		return self::dbDecrement(self::$table, $id, $field, $param);
	}

	/**
	 * 编辑（根据多项条件自减）
	 * @param $field
	 * @param $param
	 * @param array $wheres
	 * @param array $params
	 * @return mixed
	 */
	static function decrementWithWheres($field, $param, $wheres = array(), $params = array())
	{
		return self::dbDecrementWithWheres(self::$table, $field, $param, $wheres, $params);
	}

	/**
	 * 删除
	 * @param $id
	 * @return mixed
	 */
	static function delete($id)
	{
		return self::dbDelete(self::$table, $id);
	}

	/**
	 * 删除（根据多项条件）
	 * @param array $wheres
	 * @return mixed
	 */
	static function deleteWithWheres($wheres = array())
	{
		return self::dbDeleteWithWheres(self::$table, $wheres);
	}

    /**
	 * 获取前端新闻列表
	 * @param int $nextId
	 * @param int $pageSize
	 * @return mixed
	 */
	static function getListWithNews($nextId, $pageSize)
	{
		$order = self::dbOrderSqlByDesc($nextId, $pageSize, true);
		$where = array();
		$param = array();
		$where[] = '`id`!=0';
		return DB::select('SELECT * FROM ' . self::$table . ' WHERE ' . implode(' AND ', $where) . $order, $param);
	}
}