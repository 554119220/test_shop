<?php
namespace mercury\constants\state;

interface ShopSettled
{

    const SETTLED_AUDIT = 0;
    const SETTLED_PASS = 1;
    const SETTLED_REFUSE = 2;
    const SETTLED_EDITOR = 3;

    const SETTLED_ARRAYS = [
        self::SETTLED_AUDIT => '审核中',
        self::SETTLED_PASS => '已通过',
        self::SETTLED_REFUSE => '被拒绝',
        self::SETTLED_EDITOR => '编辑中',
    ];



}
