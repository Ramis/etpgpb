<?php
namespace console\controllers;

use common\models\log\Os;
use common\models\log\Urls;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Yii;
use yii\base\UserException;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\VarDumper;

class LogController extends Controller
{

    /**
     * @param String $log_on_urls
     * @param String $log_on_os
     * @return int
     * @throws UserException
     */
    public function actionParse(String $log_on_urls, String $log_on_os):int
    {

        if (!file_exists($log_on_urls)) {
           throw new FileNotFoundException('File log on urls not found');
        }
        if (!file_exists($log_on_os)) {
           throw new FileNotFoundException('File log on os not found');
        }

        $data_log_on_urls = fopen($log_on_urls,"r");
        if (!$data_log_on_urls) {
            throw new UserException('Error open log on url');
        }

        while (!feof($data_log_on_urls)) {
            try {

                $line_data_file = fgets($data_log_on_urls,1024);

                $array_data = explode('|', $line_data_file);

                if (empty($array_data[0]) or !static::validateDateTime($array_data[0])) {
                    throw new UserException("Empty date " . $array_data[0]);
                }
                if (empty($array_data[1]) or !static::validateDateTime($array_data[1],'H:i:s')) {
                    throw new UserException("Empty time " . $array_data[1]);
                }
                if (empty($array_data[2]) or !filter_var($array_data[2], FILTER_VALIDATE_IP)) {
                    throw new UserException("Empty ip " . $array_data[2]);
                }
                if (empty($array_data[3])) {
                    throw new UserException("Empty url from ");
                }
                if (empty($array_data[4])) {
                    throw new UserException("Empty url to ");
                }

                list($date, $time, $ip, $url_from, $url_to) = $array_data;
                $date_time = \DateTime::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time)->format('Y-m-d H:i:sO');
                $log_on_url = Urls::find()->where(['ip' => $ip, 'date_time' => $date_time])->one();
                if(!$log_on_url) {
                    $log_on_url = new Urls();
                    $log_on_url->ip = $ip;
                    $log_on_url->date_time = $date_time;
                }

                $log_on_url->url_from = $url_from;
                $log_on_url->url_to = $url_to;

                if(!$log_on_url->save()) {
                    throw new UserException(VarDumper::dumpAsString($log_on_url->getFirstErrors()));
                }

            } catch (\Exception $e) {
                $this->stdout('Error!' . $e->getMessage() . "\n");
            }
        }

        fclose($data_log_on_urls);

        $data_log_on_os = fopen($log_on_os,"r");
        if (!$data_log_on_os) {
            throw new UserException('Error open log on os');
        }

        while (!feof($data_log_on_os)) {
            try {

                $line_data_file = fgets($data_log_on_os,1024);

                $array_data = explode('|', $line_data_file);

                if (empty($array_data[0]) or !filter_var($array_data[0], FILTER_VALIDATE_IP)) {
                    throw new UserException("Empty ip " . $array_data[0]);
                }
                if (empty($array_data[1])) {
                    throw new UserException("Empty browser");
                }
                if (empty($array_data[2])) {
                    throw new UserException("Empty os");
                }

                list($ip, $browser, $os) = $array_data;
                $log_on_os = Os::find()->where(['ip' => $ip, 'browser' => $browser, 'os' => $os])->one();
                if (!$log_on_os) {
                    $log_on_os = new Os();
                    $log_on_os->ip = $ip;
                    $log_on_os->browser = $browser;
                    $log_on_os->os = $os;

                    if(!$log_on_os->save()) {
                        throw new UserException(VarDumper::dumpAsString($log_on_os->getFirstErrors()));
                    }

                }
            } catch (\Exception $e) {
                $this->stdout('Error!' . $e->getMessage() . "\n");
            }
        }

        fclose($data_log_on_os);

        return ExitCode::OK;
    }

    private static function validateDateTime($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
