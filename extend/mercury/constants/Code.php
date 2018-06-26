<?php
/**
 * Created by PhpStorm.
 * User: Mercury
 * Date: 2017/10/27 0027
 * Time: 16:35
 *
 * 返回的code描述类
 *
 */

namespace mercury\constants;

/**
 * Class Code
 * @package mercury\constants
 *
 * 返回编码
 */
class Code
{
    //10开头，20开头，30开头，40开头,50开头
//    const CODE_FALSE    = 0;    //操作失败
//    const CODE_TRUE     = 1;    //操作成功
//    const CODE_NO_DATA  = 3;    //无数据
//
//    const CODE_NO_LOGIN    = 401;  //需要登陆
//
//    const CODE_ARRAY    = [
//        self::CODE_FALSE    => '操作失败',
//        self::CODE_TRUE     => '操作成功',
//        self::CODE_NO_DATA  => '无数据',
//        self::CODE_NO_LOGIN => '请登录',
//    ];

    /**
     * 对应HTTP状态码
     */
    const CODE_CONTINUE     = 10000;    //继续

    const CODE_SWITCHING_PROTOCOLS  = 10010;    //转换协议

    const CODE_SUCCESS      = 20000;    //请求成功

    const CODE_CREATED      = 20010;    //请求创建成功

    const CODE_ACCEPTED     = 20020;    //请求已接受

    const CODE_NON_AUTH_INFORMATION = 20030;    //请求未认证

    const CODE_NO_CONTENT   = 20040;    //无内容放回

    const CODE_RESET_CONTENT    = 20050;    //重置内容

    const CODE_PARTIAL_CONTENT  = 20060;    //局部内容

    const CODE_MULTIPLE_CHOICES = 30000;    //多重选择

    const CODE_MOVED_PERMANENTLY= 30010;    //永久重定向

    const CODE_FOUND    = 30020;    //找到临时重定向

    const CODE_SEE_OTHER= 30030;    //参见其他信息

    const CODE_NOT_MODIFIED = 30040;    //为修正【缓存】

    const CODE_USE_PROXY    = 30050;    //使用代理

    const CODE_TEMPORARY_REDIRECT   = 30070;    //临时重定向

    const CODE_BAD_REQUEST  = 40000;    //错误请求

    const CODE_UNAUTHORIZED = 40010;    //未授权

    const CODE_FORBIDDEN    = 40030;    //禁止访问

    const CODE_NOT_FOUND    = 40040;    //未找到

    const CODE_METHOD_NOT_ALLOWED   = 40050;    //方法未允许

    const CODE_NOT_ACCEPTABLE   = 40060;    //无法访问

    const CODE_PROXY_AUTHENTICATION_REQUIRED    = 40070;    //代理必须认证

    const CODE_REQUEST_TIMEOUT  = 40080;    //请求超时

    const CODE_CONFLICT = 40090;    //请求冲突

    const CODE_GONE = 40100;    //已经不存在

    const CODE_LENGTH_REQUIRED  = 40110;    //需要长数据

    const CODE_PRECONCEPTION_FAILED = 40120;    //先决条件错误

    const CODE_REQUEST_ENTITY_TOO_LARGE = 40130;    //请求实体过大

    const CODE_REQUEST_URI_TO_LONG  = 40140;    //请求URI过长

    const CODE_UNSUPPORTED_MEDIA_TYPE   = 40150;    //不支持的媒体格式

    const CODE_REQUESTED_RANGE_NO_SATISFIABLE   = 40160;    //请求访问无法满足

    const CODE_EXPECTATION_FAILED   = 40170;    //期望失败

    const CODE_INTERNAL_ERROR       = 50000;    //内部服务器错误

    const CODE_NOT_IMPLEMENTED      = 50010;    //未实现

    const CODE_BAD_GATEWAY          = 50020;    //错误的网关

    const CODE_SERVICE_UNAVAILABLE  = 50030;    //服务无法获得

    const CODE_GATEWAY_TIMEOUT      = 50040;    //网关超时

    const CODE_HTTP_VERSION_NO_SUPPORTED    = 50050;    //不支持的HTTP版本

    //应用不存在，签名错误，xxx参数不能为空，其他错误,缺少参数

    const CODE_APP_DOES_NOT_EXIST   = 60000;    //应用不存在

    const CODE_SIGN_FAIL            = 60010;    //签名错误

    const CODE_MISSING_PARAMETER    = 60020;    //缺少参数

    const CODE_OTHER_FAIL           = 60030;    //其他错误

    const CODE_PARAMETER_CAN_NOT_BE_EMPTY   = 60040;    //参数不能为空

    const CODE_TOKEN_FAIL           = 60050;    //token已失效

    const CODE_VALIDATE_FAILED      = 60060;    //验证失败

    const CODE_ARRAY    = [
        self::CODE_CONTINUE                         => [
            'msg'   => '继续',
            'info'  => 'continue',
        ],
        self::CODE_SWITCHING_PROTOCOLS              => [
            'msg'   => '转换协议',
            'info'  => 'switching protocols',
        ],
        self::CODE_SUCCESS                          => [
            'msg'   => '请求成功',
            'info'  => 'success',
        ],
        self::CODE_CREATED                          => [
            'msg'   => '请求创建成功',
            'info'  => 'created'
        ],
        self::CODE_ACCEPTED                         => [
            'msg'   => '请求已接受',
            'info'  => 'accepted',
        ],
        self::CODE_NON_AUTH_INFORMATION             => [
            'msg'   => '请求未认证',
            'info'  => 'non auth information'
        ],
        self::CODE_NO_CONTENT                       => [
            'msg'   => '无内容返回',
            'info'  => 'no content'
        ],
        self::CODE_RESET_CONTENT                    => [
            'msg'   => '内容已重置',
            'info'  => 'reset content'
        ],
        self::CODE_PARTIAL_CONTENT                  => [
            'msg'   => '返回局部内容',
            'info'  => 'partial content'
        ],
        self::CODE_MULTIPLE_CHOICES                 => [
            'msg'   => '多重选择',
            'info'  => 'multiple choices'
        ],
        self::CODE_MOVED_PERMANENTLY                => [
            'msg'   => '永久重定向',
            'info'  => 'moved permanently'
        ],
        self::CODE_FOUND                            => [
            'msg'   => '找到临时重定向',
            'info'  => 'found'
        ],
        self::CODE_SEE_OTHER                        => [
            'msg'   => '参见其他信息',
            'info'  => 'see other information'
        ],
        self::CODE_NOT_MODIFIED                     => [
            'msg'   => '为修正【缓存】',
            'info'  => 'not modified'
        ],
        self::CODE_USE_PROXY                        => [
            'msg'   => '使用代理',
            'info'  => 'use proxy'
        ],
        self::CODE_TEMPORARY_REDIRECT               => [
            'msg'   => '临时重定向',
            'info'  => 'temporary redirect'
        ],
        self::CODE_BAD_REQUEST                      => [
            'msg'   => '错误请求',
            'info'  => 'bad request',
        ],
        self::CODE_UNAUTHORIZED                     => [
            'msg'   => '请登录',
            'info'  => 'unauthorized'
        ],
        self::CODE_FORBIDDEN                        => [
            'msg'   => '禁止访问',
            'info'  => 'forbidden'
        ],
        self::CODE_NOT_FOUND                        => [
            'msg'   => '未找到',
            'info'  => 'not found'
        ],
        self::CODE_METHOD_NOT_ALLOWED               => [
            'msg'   => '方法未允许',
            'info'  => 'method not allowed'
        ],
        self::CODE_NOT_ACCEPTABLE                   => [
            'msg'   => '无法访问',
            'info'  => 'not acceptable'
        ],
        self::CODE_PROXY_AUTHENTICATION_REQUIRED    => [
            'msg'   => '代理未认证',
            'info'  => 'proxy authentication required'
        ],
        self::CODE_REQUEST_TIMEOUT                  => [
            'msg'   => '请求超时',
            'info'  => 'request timeout'
        ],
        self::CODE_CONFLICT                         => [
            'msg'   => '请求冲突',
            'info'  => 'conflict'
        ],
        self::CODE_GONE                             => [
            'msg'   => '已经不存在',
            'info'  => 'gone'
        ],
        self::CODE_LENGTH_REQUIRED                  => [
            'msg'   => '需要长数据',
            'info'  => 'length required'
        ],
        self::CODE_PRECONCEPTION_FAILED             => [
            'msg'   => '先决条件错误',
            'info'  => 'preconception failed'
        ],
        self::CODE_REQUEST_ENTITY_TOO_LARGE         => [
            'msg'   => '请求实体过大',
            'info'  => 'request entity too large'
        ],
        self::CODE_REQUEST_URI_TO_LONG              => [
            'msg'   => '请求URI过长',
            'info'  => 'request uri to long'
        ],
        self::CODE_UNSUPPORTED_MEDIA_TYPE           => [
            'msg'   => '不支持的媒体格式',
            'info'  => 'unsupported media type'
        ],
        self::CODE_REQUESTED_RANGE_NO_SATISFIABLE   => [
            'msg'   => '请求访问无法满足',
            'info'  => 'requested range no satisfiable'
        ],
        self::CODE_EXPECTATION_FAILED               => [
            'msg'   => '期望失败',
            'info'  => 'expectation failed'
        ],
        self::CODE_INTERNAL_ERROR                   => [
            'msg'   => '内部服务器错误',
            'info'  => 'internal error'
        ],
        self::CODE_NOT_IMPLEMENTED                  => [
            'msg'   => '未实现',
            'info'  => 'not implemented'
        ],
        self::CODE_BAD_GATEWAY                      => [
            'msg'   => '错误的网关',
            'info'  => 'bad gateway'
        ],
        self::CODE_SERVICE_UNAVAILABLE              => [
            'msg'   => '服务无法获得',
            'info'  => 'service unavailable'
        ],
        self::CODE_GATEWAY_TIMEOUT                  => [
            'msg'   => '网关超时',
            'info'  => 'gateway timeout'
        ],
        self::CODE_HTTP_VERSION_NO_SUPPORTED        => [
            'msg'   => '不支持的HTTP版本',
            'info'  => 'http version no supported'
        ],
        self::CODE_APP_DOES_NOT_EXIST               => [
            'msg'   => '应用不存在',
            'info'  => 'app does no exist'
        ],
        self::CODE_SIGN_FAIL                        => [
            'msg'   => '签名错误',
            'info'  => 'sign failed'
        ],
        self::CODE_PARAMETER_CAN_NOT_BE_EMPTY       => [
            'msg'   => '参数不能为空',
            'info'  => 'parameter can not be empty'
        ],
        self::CODE_MISSING_PARAMETER                => [
            'msg'   => '缺少参数',
            'info'  => 'missing parameter'
        ],
        self::CODE_OTHER_FAIL                       => [
            'msg'   => '其他错误',
            'info'  => 'other failed'
        ],
        self::CODE_TOKEN_FAIL                       => [
            'msg'   => 'Token已失效',
            'info'  => 'token failed'
        ],
        self::CODE_VALIDATE_FAILED                  => [
            'msg'   => '验证失败',
            'info'  => 'validate failed',
        ],
    ];
}