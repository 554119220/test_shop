<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/11 0011
 * Time: 9:18
 */

namespace Pheanstalk\Command;


use Pheanstalk\ResponseParser;
use Pheanstalk\Exception;
use Pheanstalk\Response;

/**
 * 通过JOB ID删除JOB
 *
 * Class DelCommand
 * @package Pheanstalk\Command
 */
class DelCommand extends AbstractCommand implements ResponseParser
{
    private $job_id;

    public function __construct($job_id)
    {
        $this->job_id   = $job_id;
    }

    /**
     * 执行删除命令
     *
     * @return string
     */
    public function getCommandLine()
    {
        return "delete {$this->job_id}";
    }

    /* (non-phpdoc)
     * @see ResponseParser::parseResponse()
     */
    public function parseResponse($responseLine, $responseData)
    {
        if ($responseLine == Response::RESPONSE_NOT_FOUND) {
            throw new Exception\ServerException(sprintf(
                'Cannot delete job %u: %s',
                $this->job_id,
                $responseLine
            ));
        }

        return $this->_createResponse($responseLine);
    }
}