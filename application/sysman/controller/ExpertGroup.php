<?php
/*
 * @Description: 专家分组管理
 * @Author: sxg
 * @Date: 2020-04-20 15:30:44
 * @Last Editor: sxg
 * @LastEditTime: 2020-05-09 11:04:42
 */

namespace app\sysman\controller;

use app\common\model\ExpertGroup as Model;
use think\facade\Request;
use think\Validate;
use think\Db;


class ExpertGroup extends AdminBase
{
  public function initialize()
  {
    parent::initialize();
    if ($this->rolecode !== 'admin') {
      $this->error('无权访问');
    }
    $this->model = new Model();
  }

  public function index()
  {
    if (Request::isAjax()) {
      $list = $this->model->order('id desc')->select();
      
      foreach ($list as $key => $value) {
         foreach ($value['works_category'] as $k => $v) {
            $workcate[$key][] = Db::name('cate')->where('id','=',$v)->value('title');
            // $list[$key]['works_category'] = $workcate['title'];
         }
         $list[$key]['works_category'] = $workcate[$key];
      }

      $this->result($list);
    }

    return view();
  }

  public function edit()
  {
    $info = null;
    $id = input('get.id');
    $group = Db::name('cate')->where('cateid = 9')->select();
    if (!empty($id)) {
      
      $info = $this->model->field('id,name,works_category')->find($id);
      // var_dump($info);exit;
    }
    $this->assign('info', json_encode($info));
    $this->assign('group', $group);
    return $this->fetch();
  }

  public function save()
  {
    $data = input('post.');
    $this->model->isUpdate(!empty($data['id']))->allowField(true)->save($data);
    $this->success('1', null, $this->model);
  }

  public function del(int $id=0)
  {
    if(empty($id)) {
      $this->error('传入参数错误');
    }
    $this->model->where('id',$id)->delete();
    $this->success();
  }
}
