<?php

/**
 * 账户提现
 */

namespace app\member\api;


class CashApi extends \app\member\api\MemberApi {

    protected $_middle = 'member/Cash';

    public function index() {
        $type = $this->data['type'];
        $pageLimit = $this->data['limit'] ? $this->data['limit'] : 10;
        target($this->_middle, 'middle')->setParams([
            'user_id' => $this->userId,
            'type' => $type,
            'limit' => $pageLimit,
        ])->data()->export(function ($data) use ($pageLimit) {
            if(!empty($data['pageList'])) {
                $this->success('ok', [
                    'data' => $data['pageList'],
                    'pageData' => $this->pageData($pageLimit, $data['pageList'], $data['pageData']),
                ]);
            }else {
                $this->error('暂无更多记录', 404);
            }
        }, function () {
            $this->error('暂无更多记录', 404);
        });

    }

    public function info() {
        target($this->_middle, 'middle')->setParams([
            'user_id' => $this->userInfo['user_id'],
            'no' => request('get', 'no'),
        ])->info()->export(function ($data) {
            $this->success('ok', $data['info']);
        }, function ($message, $code) {
            $this->error($message, $code);
        });
    }

    public function apply() {
        if (!isPost()) {
            target($this->_middle, 'middle')->setParams([
                'user_id' => $this->userId,
            ])->apply()->export(function ($data, $message) {
                $this->success($message, $data);
            }, function ($message, $code) {
                $this->error($message, $code);
            });
        } else {
            target($this->_middle, 'middle')->setParams(
                array_merge($this->data, ['user_id' => $this->userId])
            )->submit()->export(function ($data, $message) {
                $this->success($message, $data);
            }, function ($message, $code) {
                $this->error($message, $code);
            });
        }
    }

}